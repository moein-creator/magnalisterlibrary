<?php if (!class_exists('ML', false))
    throw new Exception();
?>
<?php
/** @var $this ML_I18n_Controller_Main_Tools_I18n_TranslationTool */
?>


<nav aria-label="Page navigation">
    <div class="pagination" style="width: fit-content; margin: 10px auto;">
        <?php
        $range = 15; // Number of pages to show around the current page

        $aStatistic = $this->getStatistic();
        for ($i = 1; $i <= $aStatistic['total'] / $this->getLimit(); $i++) { ?>
            <?php if ($i == 1 || $i == $aStatistic['total'] || ($i >= $aStatistic['current'] - $range && $i <= $aStatistic['current'] + $range)) { ?>
                <div class="page-item <?php echo($i == $aStatistic['current'] ? 'active' : '') ?>">

                    <input type="submit" class="page-link" value="<?php echo $i ?>"
                           name="<?php echo MLHttp::gi()->parseFormFieldName('page') ?>"/>


                </div>
            <?php } else if ($i == $aStatistic['current'] - $range - 1 || $i == $aStatistic['current'] + $range + 1) { ?>
                <div class="page-item <?php echo($i == $aStatistic['current'] ? 'active' : '') ?>">
                    <input type="button" class="page-link" style="pointer-events: none" value="...">
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</nav>