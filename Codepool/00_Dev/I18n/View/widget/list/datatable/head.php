<?php if (!class_exists('ML', false))
    throw new Exception();
?>
<?php
/** @var $this ML_I18n_Controller_Main_Tools_I18n_TranslationTool */
?>

<thead>
<th>
    <div class="form-label">Key</div>
    <input placeholder="Search translation key" class="form-control form-input ml-submit-form"
           name="<?php echo MLHttp::gi()->parseFormFieldName('key_search') ?>"
           value="<?php echo htmlentities($this->getKeySearch()) ?>"/>
    <div style="font-size: 10px">To search for an exact word, enclose the search term within double quotation marks
        (""). e.g. "ebay"
    </div>
    <div style="font-size: 10px">use "not:" first to search not containing. e.g. "not:ebay"</div>
</th>
<th>
    <div class="form-label">Filepath</div>
    <input placeholder="Search translation path" class="form-control form-input ml-submit-form"
           name="<?php echo MLHttp::gi()->parseFormFieldName('filepath_search') ?>"
           value="<?php echo htmlentities($this->getFilepathSearch()) ?>"/>
    <div style="font-size: 10px">To search for an exact word, enclose the search term within double quotation marks
        (""). e.g. "ebay"
    </div>
    <div style="font-size: 10px">use "not:" first to revert search. e.g. "not:ebay"</div>
</th>
<th>
    <div class="row">
        <div class="form-label">From Language</div>
    </div>
    <div class="row">
        <div class="col-sm">
            <select name="<?php echo MLHttp::gi()->parseFormFieldName('base_language') ?>"
                    class="form-select ml-submit-form">
                <?php foreach ($this->getListOfLang() as $lang) { ?>
                    <option <?php echo $lang === $this->getBaseLanguage() ? 'selected=selected' : '' ?>
                            value="<?php echo $lang ?>"><?php echo $lang ?></option>
                <?php } ?>
            </select></div>
        <div class="col-sm">
            <select id="ml-translation-tools-base-language-option" class="form-select ml-submit-form"
                    name="<?php echo MLHttp::gi()->parseFormFieldName('base_option') ?>">
                <?php foreach ($this->getListOfSearchOption() as $key => $option) { ?>
                    <option <?php echo $this->getBaseOption() === $key ? 'selected=selected' : '' ?>
                            value="<?php echo $key ?>"><?php echo $option ?></option>
                <?php } ?>
            </select></div>
    </div>
    <div class="row">
        <div class="col-sm">
            <input placeholder="Search content" class="form-control mt-2 form-input ml-submit-form"
                   name="<?php echo MLHttp::gi()->parseFormFieldName('from_lang_search') ?>"
                   value="<?php echo htmlentities($this->getFromLanguageSearch()) ?>"/>
            <div style="font-size: 10px">To search for an exact word, enclose the search term within double quotation
                marks (""). e.g. "ebay"
            </div>
        </div>
    </div>
</th>
<th>
    <div class="row">
        <div class="form-label">To Language</div>
    </div>
    <div class="row">
        <div class="col-sm">
            <select name="<?php echo MLHttp::gi()->parseFormFieldName('target_language') ?>"
                    class="form-select ml-submit-form">
                <?php
                $aListOfLanguage = $this->getListOfLang();
                foreach ($aListOfLanguage as $lang) { ?>
                    <option <?php echo $lang === $this->getTargetLanguage() ? 'selected=selected' : '' ?>
                            value="<?php echo $lang ?>"><?php echo $lang ?></option>
                <?php } ?>
            </select></div>
        <div class="col-sm">
            <select id="ml-translation-tools-target-language-option" class="form-select ml-submit-form"
                    name="<?php echo MLHttp::gi()->parseFormFieldName('target_option') ?>">
                <?php foreach ($this->getListOfSearchOption() as $key => $option) { ?>
                    <option <?php echo $this->getTargetOption() === $key ? 'selected=selected' : '' ?>
                            value="<?php echo $key ?>"><?php echo $option ?></option>
                <?php } ?>
            </select></div>
    </div>
    <div class="row">
        <div class="col-sm">
            <input placeholder="Search content" class="form-control mt-2 form-input ml-submit-form"
                   name="<?php echo MLHttp::gi()->parseFormFieldName('to_lang_search') ?>"
                   value="<?php echo htmlentities($this->getToLanguageSearch()) ?>"/>
            <div style="font-size: 10px">To search for an exact word, enclose the search term within double quotation
                marks (""). e.g. "ebay"
            </div>

        </div>
    </div>

</th>

<th>
    <div class="row">
        <div class="form-label"><?php echo $this->getTargetLanguage(); ?> Changes
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
        <select id="ml-translation-tools-base-language-option" class="form-select ml-submit-form"
                name="<?php echo MLHttp::gi()->parseFormFieldName('updated_option') ?>">
            <?php foreach ($this->getListOfSearchOption() as $key => $option) { ?>
                <option <?php echo $this->getUpdatedOption() === $key ? 'selected=selected' : '' ?>
                        value="<?php echo $key ?>"><?php echo $option ?></option>
            <?php } ?>
        </select></div>
    </div>
    <div class="row mt-2">
        <div class="col-sm">
        <select class="form-select ml-submit-form"
                name="<?php echo MLHttp::gi()->parseFormFieldName('changes_table') ?>">
            <?php foreach (MLDatabase::getDbInstance()->getAvailableTables('/magnalister_translation_.*/') as $option) { ?>
                <option <?php echo $this->getChangesTable() === $option ? 'selected=selected' : '' ?>
                        value="<?php echo $option ?>"><?php echo $option ?></option>
            <?php } ?>
        </select>
        </div>
    </div>
</th>

<th>
    <div class="row">
        <div class="form-label">Status
        </div>
    </div>
    <div class="row">
        <div class="form-label"> Comments
        </div>
    </div>
    <div class="row">
        <select name="<?php echo MLHttp::gi()->parseFormFieldName('translation_status') ?>"
                class="form-select ml-submit-form">
            <?php
            $selectedStatus = $this->getTranslationStatus();
            foreach ($this->getListOfStatuses() as $key => $option) { ?>
                <option <?php echo $selectedStatus === $key ? 'selected=selected' : '' ?>
                        value="<?php echo $key ?>"><?php echo $option ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="row">
        <div class="col-sm">
            <input placeholder="Search Comment" class="form-control mt-2 form-input ml-submit-form"
                   name="<?php echo MLHttp::gi()->parseFormFieldName('translation_comment') ?>"
                   value="<?php echo htmlentities($this->getTranslationComment()) ?>"/>
            <div style="font-size: 10px">To search for an exact word, enclose the search term within double quotation
                marks (""). e.g. "ebay"
            </div>

        </div>
    </div>
</th>
</thead>