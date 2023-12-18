<?php
/* @var $this  ML_Productlist_Controller_Widget_ProductList_Abstract */
/* @var $aField array array('name'=>'', 'value'=>'', 'values'=>array('key'=> 'value'), 'shopMatchingValue'=>'') */
class_exists('ML', false) or die();
?>
<?php if (isset($aField)) {
    $selectid = isset($aField['id']) ? 'id="'.$aField['id'].'"' : uniqid('autoprefix-option');
    ?>
    <div style="width: 100%" class='ml-searchable-select' lang="<?php echo strtolower(MLLanguage::gi()->getCurrentIsoCode()); ?>" >
        <select style="width: 100%"
                class="<?php echo isset($aField['shopBrand']) ? $aField['shopBrand'] : ''; ?>"
                data-css="<?php echo isset($aField['cssclass']) ? $aField['cssclass'] : ''; ?>"
                data-shopmatchingvalue="<?php echo isset($aField['shopMatchingValue']) ? $aField['shopMatchingValue'] : ''; ?>"
                data-variationvalue="<?php echo isset($aField['variationValue']) ? $aField['variationValue'] : ''; ?>"
                data-customidentifier="<?php echo isset($aField['customIdentifier']) ? $aField['customIdentifier'] : ''; ?>"
                data-mpattributecode="<?php echo isset($aField['mpAttributeCode']) ? $aField['mpAttributeCode'] : ''; ?>"
                name="<?php echo empty($aField['name']) ? '' : MLHttp::gi()->parseFormFieldName($aField['name']);?>"
            <?php echo 'id="'.$selectid.'"'?>>
            <?php foreach ($aField['values'] as $aKey => $aValue) {?>
                <option value="<?php echo $aKey ?>"<?php echo $aField['value'] == $aKey ? ' selected="selected"' : '' ?>><?php echo $aValue ?></option>
            <?php }?>
        </select>
    </div>
<?php
    MLSettingRegistry::gi()->addJs('select2/select2.min.js');
    MLSettingRegistry::gi()->addJs('select2/i18n/' . strtolower(MLLanguage::gi()->getCurrentIsoCode() . '.js'));
    MLSetting::gi()->add('aCss', array('select2/select2.min.css'), true);

    $sPostNeeded = '';
    foreach (MLHttp::gi()->getNeededFormFields() as $sKey => $sValue) {
        $sPostNeeded .= "'$sKey' : '$sValue' ,";
    }
    ?>
<script type="text/javascript">
/*<![CDATA[*/
    (function(jqml) {
        jqml(document).ready(function() {
            jqml.ajax({
                url : "<?php echo $this->getCurrentURl(array('ajax' => 'true', 'method' => 'GetBrands')) ?>",
                data: {
                    'ml[brandmatching]' : 'PreloadBrandCache',
                }
            });

            jqml("#<?php echo $selectid ?>").select2({
                dropdownAutoWidth : true,
                width: '100%',
                ajax: {
                    type: 'POST',
                    delay: 250, // wait 250 milliseconds before triggering the request
                    url : "<?php echo $this->getCurrentURl(array('ajax' => 'true', 'method' => 'GetBrands')) ?>",
                    data: function (params) {
                        return {
                            <?php echo $sPostNeeded ?>
                            'ml[brandmatching]' : 'GetBrands',
                            'ml[brandmatchingSearch]': params.term,
                            'ml[brandmatchingPage]': params.page || 1,
                            'ml[brandmatchingShopMatchingValue]': jqml("#<?php echo $selectid ?>").data('shopmatchingvalue'),
                            'ml[brandmatchingVariationValue]': jqml("#<?php echo $selectid ?>").data('variationvalue'),
                            'ml[brandmatchingCustomIdentifier]': jqml("#<?php echo $selectid ?>").data('customidentifier'),
                            'ml[brandmatchingMpAttributeCode]': jqml("#<?php echo $selectid ?>").data('mpattributecode')
                        };
                    },
                    dataType: 'json'
                }
            });
            var css = jqml("#<?php echo $selectid ?>").data('css')

            // validation error
            if (css === 'error') {
                jqml("#<?php echo 'select2-' . $selectid . '-container' ?>").addClass('select2__rendered_error');
                jqml("#<?php echo 'select2-' . $selectid . '-container' ?>").parent().addClass('select2__rendered_error');
            }

            jqml("#<?php echo 'select2-' . $selectid . '-container' ?>").addClass('select2__rendered_matching_fix');
        });
    })(jqml);
/*]]>*/
</script>

<style>
    body.magna .select2-container {
         margin-top: 0px; !important;
    }
    .select2-selection__rendered {
        box-sizing: border-box !important;
    }
    .select2__rendered_error{
        color: red !important;
        border-color: red !important;
    }
    
    body.magna .select2-container--open .select2-dropdown--above, body.magna .select2-container--open .select2-dropdown--below {
        margin-top: 0px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        margin-left: -11px;
    }
    .select2__rendered_matching_fix::before {
        top: 2px !important;
    }
    .select2-search--dropdown::before {
        content: "";
        background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABcAAAAVCAYAAACt4nWrAAAAAXNSR0IArs4c6QAAAGJlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAABJKGAAcAAAASAAAAUKABAAMAAAABAAEAAKACAAQAAAABAAAAF6ADAAQAAAABAAAAFQAAAABBU0NJSQAAAFNjcmVlbnNob3TDWIViAAAB1GlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS40LjAiPgogICA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPgogICAgICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgICAgICAgICB4bWxuczpleGlmPSJodHRwOi8vbnMuYWRvYmUuY29tL2V4aWYvMS4wLyI+CiAgICAgICAgIDxleGlmOlBpeGVsWERpbWVuc2lvbj4yMzwvZXhpZjpQaXhlbFhEaW1lbnNpb24+CiAgICAgICAgIDxleGlmOlVzZXJDb21tZW50PlNjcmVlbnNob3Q8L2V4aWY6VXNlckNvbW1lbnQ+CiAgICAgICAgIDxleGlmOlBpeGVsWURpbWVuc2lvbj4yMTwvZXhpZjpQaXhlbFlEaW1lbnNpb24+CiAgICAgIDwvcmRmOkRlc2NyaXB0aW9uPgogICA8L3JkZjpSREY+CjwveDp4bXBtZXRhPgpzAuHgAAACXUlEQVQ4EcVUS2siQRD+jO8IEbwEgyKKRBAVxBySUy45eFM86sWfJghCEATx4E08BQRREcwtEBWfiG98oe5uNTvDtDrZwLJsw9DdNVVfPfqrUvz4tfCPluoSLvlbrVaYTqdYr9c4HA5QKBTQaDQwGo24ubmBUqm8ZMrJzsCPxyP6/T4qlQpqtRrG4zEDJ6vr62u4XC48Pj7CbrdDq9VyYKcXhbQsFHGr1UImk8Hn5yfcbjc8Hg9MJhPL4OPjA6VSiWUQiUTg9Xq/dMCBz2YzJBIJdLtdhMNh+P1+6HQ6LqBms4lkMonNZoN4PA6Hw4GrqytOR7hw0nq9ziIOBoMXgcnIZrMhGo2yN6HSLZdLAets58CpxlarFff392cRSy0pWp/Ph/f3d1C2cosDn0wmMJvNMBgMcvqinBzM53PsdjtRdnrgwOlB1Wq1bA2lxkRL0qdPbnHger2ecXu73crpi/LhcMiYolKdsVnU4cCJu8SGwWAgclvUlBwWiwUajQYsFsuXJeTAHx4esN/v8fb2hk6nc9EBAReLRRZAIBBgHSvxyx25nIhmLy8vyOfzoE59enrC7e0taxoaAUS7arWKbDbL2OJ0Otk/DlFy4cCpGZ6fn9mDFgoFvL6+4u7ujkVHTdPr9UCR09sQBdvtNpszcmOA61DBKTGAylIul1lT0fCiQUVZ0FwhpqTTaeYoFArJjoGL4IITYacS0VSkT1gUdSqVYqWSc8A9qGB4ulO5pMD0nzqZxgA1XC6Xw2g0OjXDt8DPrH4LyEEsFmPlOnVOKt8qixz4n+R/Ffl/Bf8JNhgHSVHJrxIAAAAASUVORK5CYII=);
        background-repeat: no-repeat;
        background-position: 0 0;
        width: 9%;
        height: 18px;
        display: inline-block;
        position: relative;
        left: 0px;
        top: 0px !important;
        margin: 0 auto;
        right: 0px;
        float: left;
    }

    .select2-search--dropdown .select2-search__field {
        width: 91%;
    }

    .select2-search__field, .select2-search__field::focus, .select2-search__field::active {
        outline: none !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 4px;
    }

    .select2-search--dropdown {
        padding: 4px 0 6px 0 !important;
        border-bottom: 1px solid #ededed;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 0;
        margin-left: 20px;
        display: block;
    }

    #modal-otto_prepare_variations_field_variationgroups_value {
        overflow: hidden !important;
    }

    #select2-mlfilter-container {
        line-height: 28px;
    }

    .select2-selection__rendered {
        padding-right: 0px !important;
    }

    .ml-category-selecr2-search span {
        width: 404px !important;
        height: 30px !important;
        border-radius: 0 !important;
        overflow-x: none !important;
    }

    .select2-selection__arrow b {
        left: 97% !important;
    }

    .select2-selection--single {
        height: 28px !important;
    }

    .select2-results__options--nested {
        border-bottom: 1px solid #ededed;
    }

</style>

<?php }
