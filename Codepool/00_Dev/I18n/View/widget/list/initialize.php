<div class="row mb-3" style="height: 72px">
    By initializing, all changes of i18n files in current plugin will be overwritten with live i18n files from
    magnalister update server
</div>
<div class="row">
    <a style="width: fit-content" id="ml-initialize-translation-table"
       title="Import all existing translation in I18n files into database table"
       class="btn btn-danger mb-3 global-ajax ml-js-noBlockUi"
       href="<?php echo MLHttp::gi()->getCurrentUrl(array('ajax' => true, 'method' => 'InitializeTranslationTable')); ?>"
    >
        File &nbsp; >> &nbsp; Database
    </a>
</div>




<script type="text/javascript">
    //js for loader needs to be added on the new popup
    jqml('#ml-initialize-translation-table').click(function () {
        const form = jqml(this).closest('form');
        let currentA = jqml(this);
        currentA.magnalisterRecursiveAjax({
            sOffset: '<?php echo MLHttp::gi()->parseFormFieldName('offset') ?>',
            sAddParam: '<?php echo MLHttp::gi()->parseFormFieldName('ajax') ?>=true',
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