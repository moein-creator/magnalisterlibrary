<?php
/*
 * 888888ba                 dP  .88888.                    dP
 * 88    `8b                88 d8'   `88                   88
 * 88aaaa8P' .d8888b. .d888b88 88        .d8888b. .d8888b. 88  .dP  .d8888b.
 * 88   `8b. 88ooood8 88'  `88 88   YP88 88ooood8 88'  `"" 88888"   88'  `88
 * 88     88 88.  ... 88.  .88 Y8.   .88 88.  ... 88.  ... 88  `8b. 88.  .88
 * dP     dP `88888P' `88888P8  `88888'  `88888P' `88888P' dP   `YP `88888P'
 *
 *                          m a g n a l i s t e r
 *                                      boost your Online-Shop
 *
 * -----------------------------------------------------------------------------
 * (c) 2010 - 2023 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

    /* @var $this  ML_Productlist_Controller_Widget_ProductList_Abstract */
    /* @var $oList ML_Productlist_Model_ProductList_Abstract */
    /* @var $oProduct ML_Shop_Model_Product_Abstract */
     if (!class_exists('ML', false))
         throw new Exception();
?>
<?php if ($this instanceof ML_Productlist_Controller_Widget_ProductList_Abstract) { ?>
    <?php 
        $oPrepare=$this->getPrepareData($oProduct);
        $aI18n=$this->__('Ebay_CheckinForm');
    ?>
      <table style="width:100%">
        <tr>
            <th>
                <?php echo $aI18n['Price']?>:
            </th>
            <td>
                 <?php echo $this->getPrice($oProduct) ?>
            </td>
            <td style="color:gray;font-style: italic;float:right;">
                (
                <?php echo $aI18n['ShopPrice']?>: <?php echo $oProduct->getShopPrice(true,false);?>, 
                <?php echo $aI18n['SuggestedPrice']?>: <?php echo $oProduct->getSuggestedMarketplacePrice($this->getPriceObject($oProduct),true,false)?>, 
                )
            </td>
        </tr>
        <tr>
            <th><?php echo $aI18n['Amount'] ?>:</th>
            <td><?php echo $this->getStock($oProduct)  ?></td>
            <td style="color:gray;font-style: italic;float:right;">
                (
                <?php echo $aI18n['AvailibleAmount']?>: <?php echo $oProduct->getStock() ?>, 
                <?php echo $aI18n['SuggestedAmount']?>: <?php echo $this->getStock($oProduct) ?>
                )
            </td>
        </tr>
        <tr>
            <th>
                <?php echo $aI18n['ShippingTime'] ?>: 
            </th>
            <td>
                <select style="width:100%" name="<?php echo MLHttp::gi()->parseFormFieldName('selection[data][leadtimetoship]') ?>">
                    <?php $iShipping = MLModule::gi()->getConfig('dispatchtimemax') ?>
                    <option>—</option>
                    <?php for($i=1;$i<31;$i++){?>
                        <option <?php echo($iShipping==$i?'selected="selected" ':'') ?>value="<?php echo $i ?>"><?php echo $i?></option>
                    <?php } ?>    
                </select>
            </td>
        </tr>
    </table>
<?php } ?>