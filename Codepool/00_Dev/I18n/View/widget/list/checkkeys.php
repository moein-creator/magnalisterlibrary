<?php
/** @var $this ML_I18n_Controller_Main_Tools_I18n_TranslationTool */
?>
<div class="row mb-3" style="height: 14px">

</div>
<div class="row mb-3">

    <select name="<?php echo MLHttp::gi()->parseFormFieldName('tablename') ?>" id="ml-checkkey-tablename">
        <?php foreach (['magnalister_translation', 'magnalister_translation_update'] as $tableName) { ?>
            <option <?php echo $tableName === $this->getTargetLanguage() ? 'selected=selected' : '' ?>
                    value="<?php echo $tableName ?>"><?php echo $tableName ?></option>
        <?php } ?>
    </select>
</div>
<div class="row">

</div>
<div class="row">
    <a style="width: fit-content" id="ml-check-translation-keys" title="Check translation keys"
       class="btn btn-danger mb-3 global-ajax ml-js-noBlockUi"
       href="<?php echo MLHttp::gi()->getCurrentUrl(array('ajax' => true, 'method' => 'CheckKeys')); ?>">
        Check Keys
    </a>
</div>
<script type="text/javascript">
    //js for loader needs to be added on the new popup
    jqml('#ml-check-translation-keys').click(function () {
        let currentA = jqml(this);
        const form = jqml(this).closest('form');
        let values = '<?php echo MLHttp::gi()->parseFormFieldName('ajax') ?>=true';
        values += '&' + jqml('#ml-checkkey-tablename').attr('name') + '=' + jqml('#ml-checkkey-tablename').val();
        currentA.magnalisterRecursiveAjax({
            sOffset: '<?php echo MLHttp::gi()->parseFormFieldName('offset') ?>',
            sAddParam: values,
            oI18n: {
                sProcess: '<?php echo $this->__s('ML_STATUS_FILTER_SYNC_CONTENT', array('\'')) ?>',
                sError: '<?php echo $this->__s('ML_ERROR_LABEL', array('\'')) ?>',
                sSuccess: 'success'
            },
            oFinalButtons: {
                oError: [
                    {
                        text: 'Ok', click: function () {
                            jqml(this).dialog('close');
                        }
                    }
                ],
                oSuccess: [
                    {
                        text: 'Ok', click: function () {
                            form.submit();
                        }
                    }
                ]
            },
            onFinalize: function () {
            },
            onProgessBarClick: function (data) {
                console.dir({data: data});
            },
            blDebug: <?php echo MLSetting::gi()->get('blDebug') ? 'true' : 'false' ?>,
            sDebugLoopParam: "<?php echo MLHttp::gi()->parseFormFieldName('saveSelection') ?>=true"
        });
        return false;
    });

</script>
