<?php
/* @var $this  ML_Productlist_Controller_Widget_ProductList_Abstract */
/* @var $oList ML_Productlist_Model_ProductList_Abstract */
/* @var $aStatistic array */
if (!class_exists('ML', false))
    throw new Exception();
?>
<?php if ($this instanceof ML_Productlist_Controller_Widget_ProductList_Abstract) { ?>
    <table class="actions">
        <tbody class="firstChild">
        <tr>
            <td>
                <div class="actionBottom right">
                    <table class="upload-buttons">
                        <tr>
                            <td class="textleft lastChild autoWidth">
                                <form class="right" action="<?php echo $this->getCurrentUrl() ?>" method="post" title="<?php echo ML_STATUS_FILTER_SYNC_ITEM ?>">
                                    <?php foreach (MLHttp::gi()->getNeededFormFields() as $sName => $sValue) { ?>
                                        <input type="hidden" name="<?php echo $sName ?>" value="<?php echo $sValue ?>"/>
                                    <?php } ?>
                                    <input type="hidden" name="<?php echo MLHttp::gi()->parseFormFieldName('method') ?>" value="checkinAdd"/>
                                    <input type="submit" value="<?php echo $this->__('ML_BUTTON_LABEL_CHECKIN_ADD') ?>" class="js-marketplace-upload mlbtn action ml-js-noBlockUi"/>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td class="textleft lastChild autoWidth">
                                    <form class="right" action="<?php echo $this->getCurrentUrl() ?>" method="post" title="<?php echo ML_STATUS_FILTER_SYNC_ITEM ?>">
                                        <?php foreach (MLHttp::gi()->getNeededFormFields() as $sName => $sValue) { ?>
                                            <input type="hidden" name="<?php echo $sName ?>" value="<?php echo $sValue ?>" />
                                        <?php } ?>
                                        <input type="hidden" name="<?php echo MLHttp::gi()->parseFormFieldName('method') ?>" value="checkinPurge" />
                                        <input type="submit" value="<?php echo $this->__('ML_BUTTON_LABEL_CHECKIN_PURGE') ?>" class="js-marketplace-upload mlbtn ml-js-noBlockUi" />
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="clear"></div>
                </td>
            </tr>
        </tbody>
    </table>
<?php $this->includeView('widget_upload_ajax', array(
   'sProcess'  => $this->__('ML_STATUS_FILTER_SYNC_CONTENT'),
   'sError'  => $this->__('ML_ERROR_SUBMIT_PRODUCTS'),
   'sSuccess'  => $this->__('ML_STATUS_FILTER_SYNC_SUCCESS'),
   'sInfo' => $this->__('tradoria_upload_explanation')
    )); ?>
<?php }