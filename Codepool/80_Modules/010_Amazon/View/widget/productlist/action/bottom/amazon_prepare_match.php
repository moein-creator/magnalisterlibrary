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
 * (c) 2010 - 2021 RedGecko GmbH -- http://www.redgecko.de
 *     Released under the MIT License (Expat)
 * -----------------------------------------------------------------------------
 */

    /* @var $this  ML_Productlist_Controller_Widget_ProductList_Abstract */
    /* @var $oList ML_Productlist_Model_ProductList_Abstract */
    /* @var $aStatistic array */
    class_exists('ML',false) or die();
?>
<?php if ($this instanceof ML_Productlist_Controller_Widget_ProductList_Abstract) { ?>
        <table class="actions">
            <tbody class="firstChild">
                <tr>
                    <td>
                        <div class="actionBottom">
                            <div class="left">
                                <div>
                                    <form action="<?php echo $this->getCurrentUrl() ?>" method="post">
                                        <?php foreach (MLHttp::gi()->getNeededFormFields() as $sName => $sValue) { ?>
                                            <input type="hidden" name="<?php echo $sName ?>" value="<?php echo $sValue ?>" />
                                        <?php } ?>
                                        <input type="hidden" name="<?php echo MLHttp::gi()->parseFormFieldName('execute') ?>" value="unprepare" />
                                        <input class="mlbtn" type="submit" value="<?php echo $this->__('ML_AMAZON_BUTTON_MATCHING_DELETE'); ?>">
                                    </form>
                                </div>
                            </div>
                            <div class="right" style="padding-right: 6px">
                                <div>
                                    <a class="mlbtn action" style="display: block; margin: 0px -3px 0px 3px;" href="<?php echo $this->getUrl(array('controller' => $this->getRequest('controller') . '_manual')); ?>">
                                       <?php echo $this->__('ML_AMAZON_LABEL_MANUAL_MATCHING') ?>
                                    </a>
                                </div>
                                <?php if ($this->useAutoMatching() && MLShop::gi()->getShopSystemName() !== 'woocommerce') { ?>
                                    <div>
                                        <form action="<?php echo $this->getUrl(array('controller' => $this->getRequest('controller') . '_auto')) ?>" method="post" id="js-amazon-auto">
                                            <?php foreach (MLHttp::gi()->getNeededFormFields() as $sName => $sValue) { ?>
                                                <input type="hidden" name="<?php echo $sName ?>" value="<?php echo $sValue ?>" />
                                            <?php } ?>
                                            <input  style="width:100%;" type="submit" value="<?php echo $this->__('ML_AMAZON_LABEL_AUTOMATIC_MATCHING')?>" class="mlbtn action ml-js-noBlockUi" />
                                            <script type="text/javascript">/*<![CDATA[*/
                                                (function($) {
                                                    jqml(document).ready( function() {
                                                        jqml('#js-amazon-auto').click(function(){
                                                            var eForm=this;
                                                            jqml('#ML-Note-UseAuto-Dialog').jDialog({
                                                                buttons: {
                                                                    <?php echo $this->__('ML_BUTTON_LABEL_OK')?> : function(){
                                                                        eForm.submit();
                                                                    },
                                                                    <?php echo $this->__('ML_BUTTON_LABEL_ABORT');?> : function(){
                                                                        jqml(this).dialog('close');
                                                                    }
                                                                }
                                                            });
                                                            return false;
                                                        });
                                                    });
                                                })(jqml);
                                            /*]]>*/</script>
                                        </form>
                                        <div  id="ML-Note-UseAuto-Dialog" class="dialog2" title="<?php echo $this->__('ML_LABEL_NOTE');?>">
                                            <?php echo $this->__('ML_AMAZON_TEXT_AUTOMATIC_MATCHING_CONFIRM');?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
<?php } ?>
