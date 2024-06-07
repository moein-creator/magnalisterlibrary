<?php
if (!class_exists('ML', false))
    throw new Exception();
?>
<?php
/** @var $this ML_I18n_Controller_Main_Tools_I18n_TranslationTool */
if (
    $this->getRequest('lang') === null
    || $this->getRequest('lang') == ''
    || !is_string($this->getRequest('lang'))
) {
    ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <form id="ml-form-translation" action="<?php echo $this->getCurrentUrl() ?>" method="post">
        <?php foreach (MLHttp::gi()->getNeededFormFields() as $sName => $sValue) { ?>
            <input type="hidden" name="<?php echo $sName ?>" value="<?php echo $sValue ?>"/>
        <?php } ?>
        <table style="table-layout: fixed;" class="attributesTable">
            <tr>
                <td class="px-md-5">
                    <?php $this->includeView('widget_list_initialize') ?>
                </td>
                <td class="px-md-5">
                    <?php $this->includeView('widget_list_deepltranslation') ?>
                </td>
                <td class="px-md-5">
                    <?php $this->includeView('widget_list_applychanges') ?>
                </td>
                <td class="px-md-5">
                    <?php if (MLSetting::gi()->blDebug) $this->includeView('widget_list_checkkeys'); ?>
                </td>
            </tr>
        </table>
        <div style="margin: 20px">
            <?php $this->includeView('widget_list_pagination') ?>
            <?php $this->includeView('widget_list_datatable') ?>
            <?php $this->includeView('widget_list_pagination') ?>
        </div>
    </form>
    <?php
}
