<div class="row mb-3" style="height: 14px">

</div>
<div class="row mb-3">

    <select name="<?php echo MLHttp::gi()->parseFormFieldName('apply_language') ?>"
            id="ml-apply-change-language">
        <?php foreach ($this->getListOfLang() as $lang) { ?>
            <option <?php echo $lang === $this->getTargetLanguage() ? 'selected=selected' : '' ?>
                    value="<?php echo $lang ?>"><?php echo $lang ?></option>
        <?php } ?>
    </select>
</div>
<div class="row">
    <a style="width: fit-content" id="ml-apply-translation"
       title="Applying translatin update into i18n files and export them as Zip-File"
       class="btn btn-danger mb-3 global-ajax ml-js-noBlockUi"
       href="<?php echo MLHttp::gi()->getCurrentUrl(array('ajax' => true, 'method' => 'ApplyTranslationChanges')); ?>">
        Database Changes &nbsp; >> &nbsp; File and Current Test System
    </a>
</div>
<script type="text/javascript">
    //js for loader needs to be added on the new popup
    jqml('#ml-apply-translation').click(function () {
        let currentA = jqml(this);
        const form = jqml(this).closest('form');
        let values = '<?php echo MLHttp::gi()->parseFormFieldName('ajax') ?>=true';
        values += '&' + jqml('#ml-apply-change-language').attr('name') + '=' + jqml('#ml-apply-change-language').val();
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
                            form.submit();
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
<?php
$sZipFile = $this->getNameOfI18ZipFile();
$aCacheFile = MLFilesystem::getCachePath($sZipFile);
if (file_exists($aCacheFile)) {
    ?>
    <div class="row">
        <a class="col  ml-js-noBlockUi link-primary" href="<?php echo MLHttp::gi()->getCacheUrl($sZipFile); ?>">
        Download Changes
    </a>

        <span class="col">Generated on <?php echo date('Y-m-d H:i:s', filectime($aCacheFile)) ?></span>
    </div>
<?php }