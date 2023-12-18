<?php
/**
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
 * (c) 2010 - 2019 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */
 
    /* @var $this  ML_Productlist_Controller_Widget_ProductList_Abstract */
    /* @var $oList ML_Productlist_Model_ProductList_Abstract */
    /* @var $aStatistic array */
    class_exists('ML', false) or die();
?>
<?php if ($this instanceof ML_Productlist_Controller_Widget_ProductList_Abstract) { ?>
    <table class="actions">
        <tbody class="firstChild">
            <tr>
                <td>
                    <div class="actionBottom right">
                        <table class="upload-buttons">
                            <tr>
                                <td class="textleft">
                                    <form  class="right" action="<?php echo $this->getCurrentUrl() ?>" method="post" title="<?php echo ML_STATUS_FILTER_SYNC_ITEM ?>">
                                        <?php foreach (MLHttp::gi()->getNeededFormFields() as $sName => $sValue) { ?>
                                            <input type="hidden" name="<?php echo $sName ?>" value="<?php echo $sValue ?>" />
                                        <?php } ?>
                                        <input type="hidden" name="<?php echo MLHttp::gi()->parseFormFieldName('method') ?>" value="checkinAdd" />
                                        <input type="submit" value="<?php echo $this->__('ML_BUTTON_LABEL_CHECKIN_ADD') ?>" class="ml-js-noBlockUi js-marketplace-upload mlbtn action" />
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td class="textleft">
                                    <form  class="right" action="<?php echo $this->getCurrentUrl() ?>" method="post" title="<?php echo ML_STATUS_FILTER_SYNC_ITEM ?>">
                                        <?php foreach (MLHttp::gi()->getNeededFormFields() as $sName => $sValue) { ?>
                                            <input type="hidden" name="<?php echo $sName ?>" value="<?php echo $sValue ?>" />
                                        <?php } ?>
                                        <input type="hidden" name="<?php echo MLHttp::gi()->parseFormFieldName('method') ?>" value="checkinPurge" />
                                        <input type="submit" value="<?php echo $this->__('ML_BUTTON_LABEL_CHECKIN_PURGE') ?>" class="ml-js-noBlockUi js-marketplace-upload mlbtn" />
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>            
                </td>
            </tr>
        </tbody>
    </table>
<?php $this->includeView('widget_upload_ajax', array(
   'sProcess'  => $this->__('ML_STATUS_FILTER_SYNC_CONTENT'),
   'sError'  => $this->__('ML_ERROR_SUBMIT_PRODUCTS'),
   'sSuccess'  => $this->__('ML_STATUS_FILTER_SYNC_SUCCESS'))
        ); ?>
<?php }