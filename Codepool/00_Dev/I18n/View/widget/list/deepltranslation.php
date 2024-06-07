<?php if (!class_exists('ML', false))
    throw new Exception();
?>
<?php
/** @var $this ML_I18n_Controller_Main_Tools_I18n_TranslationTool */
?>

<div class="row mb-3">
    <label for="ml-tools-i18n-deepltoken" class="form-label">DeepL Token</label>
    <input type="text" class="form-control" value="<?php echo $this->getDeeplToken() ?>" style="width: 100%"
           id="ml-tools-i18n-deepltoken"
           name="<?php echo MLHttp::gi()->parseFormFieldName('deepltoken'); ?>" \>
</div>
<div class="row">
    <a style="width: fit-content" id="ml-deepl-translation"
       title="Translate missing translation via DeepL"
       class="btn btn-danger mb-3 global-ajax ml-js-noBlockUi"
       href="<?php echo MLHttp::gi()->getCurrentUrl(array('ajax' => true, 'method' => 'DeeplTranslation')); ?>">
        Translate missing translation
    </a>
</div>


<script type="text/javascript">
    //js for loader needs to be added on the new popup
    jqml('#ml-deepl-translation').click(function () {
        var currentA = jqml(this);
        let values = '<?php echo MLHttp::gi()->parseFormFieldName('ajax') ?>=true';
        const form = jqml(this).closest('form');
        jqml(this).closest('form').find('.ml-submit-form').each(function () {
            values += '&' + jqml(this).attr('name') + '=' + jqml(this).val();
        });
        values += '&' + jqml('#ml-tools-i18n-deepltoken').attr('name') + '=' + jqml('#ml-tools-i18n-deepltoken').val();

        currentA.magnalisterRecursiveAjax({
            sOffset: '<?php echo MLHttp::gi()->parseFormFieldName('offset') ?>',
            sAddParam: values,
            oI18n: {
                sProcess: <?php echo json_encode($this->__('ML_STATUS_FILTER_SYNC_CONTENT')) ?>,
                sError: 'A problem by deepl translation',
                sErrorLabel: <?php echo json_encode($this->__('ML_ERROR_LABEL'))?>,
                sSuccess: 'Deepl translation is done',
                sSuccessLabel: <?php echo json_encode($this->__('ML_SUCCESS_LABEL')) ?>,
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