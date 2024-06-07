<?php if (!class_exists('ML', false))
    throw new Exception();
?>
<?php
/** @var $this ML_I18n_Controller_Main_Tools_I18n_TranslationTool */
?>
<style>
    .magna table thead tr th {
        /* Important */
        background-color: #dc3545;
        color: white;
        font-weight: bold;
    <?php if(MLShop::gi()->getShopSystemName() === 'prestashop'){ ?> top: 130px;
    <?php }else{ ?> top: 0;
    <?php } ?>
        position: sticky;
        z-index: 100;
        font-size: 16px;
    }

    .magna textarea {
        width: 100%;
    }
</style>
<table class="attributesTable">
    <?php $this->includeView('widget_list_datatable_head') ?>
    <tbody>

    <?php $i = 0;

    foreach ($this->getListOfContent() as $item) {
        ?>
        <tr>
            <td style="word-wrap: anywhere;"><span><?php echo $item['TranslationKey'] ?></span></td>
            <td><span><?php echo $item['FileRelativePath'] ?></span></td>
            <td><textarea disabled=disabled style="width:100%"
                          name=""><?php echo $item[$this->getBaseLanguage()] ?></textarea></td>
            <td><textarea id="ml-<?php echo $item['SHA3256Key'] ?>" class="ml-translation-tools-update"
                          style="width:100%"
                          data-<?php echo MLHttp::gi()->parseFormFieldName('sha3256key') ?>="<?php echo $item['SHA3256Key'] ?>"
                          data-<?php echo MLHttp::gi()->parseFormFieldName('translationkey') ?>="<?php echo $item['TranslationKey'] ?>"
                          data-<?php echo MLHttp::gi()->parseFormFieldName('filerelativepath') ?>="<?php echo $item['FileRelativePath'] ?>"
                          data-<?php echo MLHttp::gi()->parseFormFieldName('language') ?>="<?php echo $this->getTargetLanguage() ?>"
                          data-<?php echo MLHttp::gi()->parseFormFieldName('changes_table') ?>="<?php echo $this->getChangesTable() ?>"
                          name="<?php echo MLHttp::gi()->parseFormFieldName('value') ?>"
                ><?php echo $item[$this->getTargetLanguage()] ?></textarea>
            </td>
            <td>
                <textarea  <?php echo isset($item['updated']) ? 'style="background-color: pink"' : '' ?> readonly="readonly"><?php echo $item['updated'] ?></textarea>
            </td>
            <td>
                <div class="row">
                    <div class="col"><select id="ml-translation-tools-status"
                                             class="form-select ml-translation-tools-update"
                                             data-<?php echo MLHttp::gi()->parseFormFieldName('sha3256key') ?>="<?php echo $item['SHA3256Key'] ?>"
                                             data-<?php echo MLHttp::gi()->parseFormFieldName('translationkey') ?>="<?php echo $item['TranslationKey'] ?>"
                                             data-<?php echo MLHttp::gi()->parseFormFieldName('filerelativepath') ?>="<?php echo $item['FileRelativePath'] ?>"
                                             data-<?php echo MLHttp::gi()->parseFormFieldName('language') ?>="<?php echo $this->getTargetLanguage() ?>"
                                             data-<?php echo MLHttp::gi()->parseFormFieldName('changes_table') ?>="<?php echo $this->getChangesTable() ?>"
                                             name="<?php echo MLHttp::gi()->parseFormFieldName('status') ?>">
                            <?php foreach ($this->getListOfStatuses() as $key => $option) { ?>
                                <option <?php echo $item['Status'] === $key ? 'selected=selected' : '' ?>
                                        value="<?php echo $key ?>"><?php echo $option ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col">
                        <?php
                        $aHistory = [];
                        if (!empty($item['StatusHistory'])) {
                            $aHistory = MLHelper::getEncoderInstance()->decode($item['StatusHistory']);
                        }
                        if (is_array($aHistory)) {
                            foreach ($aHistory as $date => $status) {
                                echo $date;
                                break;
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div><textarea style="width:100%" class="ml-translation-tools-update"
                                   data-<?php echo MLHttp::gi()->parseFormFieldName('sha3256key') ?>="<?php echo $item['SHA3256Key'] ?>"
                                   data-<?php echo MLHttp::gi()->parseFormFieldName('translationkey') ?>="<?php echo $item['TranslationKey'] ?>"
                                   data-<?php echo MLHttp::gi()->parseFormFieldName('filerelativepath') ?>="<?php echo $item['FileRelativePath'] ?>"
                                   data-<?php echo MLHttp::gi()->parseFormFieldName('language') ?>="<?php echo $this->getTargetLanguage() ?>"
                                   data-<?php echo MLHttp::gi()->parseFormFieldName('changes_table') ?>="<?php echo $this->getChangesTable() ?>"
                                   name="<?php echo MLHttp::gi()->parseFormFieldName('comment') ?>"><?php echo $item['Comment'] ?></textarea>
                    </div>
                </div>
            </td>
        </tr>
        <?php
        $i++;
        if ($i > 1000) {
            break;
        }
    } ?>
    </tbody>

</table>
<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            $('.ml-submit-form').on('change', function () {
                $(this).closest('form').submit();
            });
        });

        $(document).ready(function () {
            // Select all text inputs with the class "myInput"
            const $textInputs = jqml('.ml-translation-tools-update');

            // Handle the change event for all text inputs
            $textInputs.on('change', function () {

                let values = $(this).data();
                values[$(this).attr('name')] = $(this).val();
                $.ajax({
                    url: '<?php echo $this->getCurrentUrl(['method' => 'updateTranslation'])?>',
                    type: 'POST',
                    data: values,
                    success: function (response) {
                        let text;
                        if (confirm("The change is save successfully do you want to refresh?") == true) {
                            $('div.page-item.active input').click();
                        }

                    },
                    error: function () {

                    }
                });
            });
        });
    })(jqml);
</script>