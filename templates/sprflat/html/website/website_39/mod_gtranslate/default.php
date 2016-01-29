<?php
$listLanguage=modGTranslateHelper::getLanguages();
JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
$doc=JFactory::getDocument();

$doc->addStyleSheet(JUri::root().'/templates/ja_bookshop/html/mod_gtranslate/assets/css/style.css');
$doc->addScript(JUri::root().'/templates/ja_bookshop/html/mod_gtranslate/assets/js/javascript.js');
$column=4;
$session =JFactory::getSession();
$language_id=$session->get('language_id', 14);
$language=modGTranslateHelper::getLanguageById($language_id);
?>
<div class="gtranslate pull-right <?php echo $moduleclass_sfx; ?>">
    <div class="widget-change-language-loading"></div>
    <div data-class="<?php echo $language->iso639code ?>-language" class="btn btn-change-language <?php echo $language->iso639code ?>-language  lang-icon"><?php echo $language->title ?></div>

    <div id="change_langueage_popover_content_wrapper" title="change language Bootstrap Popover" style="display: none">
        <div class="div-change-language row-fluid">
            <?php for($i=0;$i<count($listLanguage);$i=$i+$column){ ?>
                <div class="row">
                    <?php for($j=0;$j<$column;$j++){ ?>
                        <?php $language=$listLanguage[$i+$j] ?>
                        <?php if($language){ ?>
                        <div class="col-md-<?php echo 12/$column ?>">

                            <a class="list-group-item <?php echo strtolower(trim($language->iso639code)) ?>-language  lang-icon" data-lang="<?php echo $language->id ?>" href="javascript:void(0)"><b><?php echo $language->title ?></b></a>
                        </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php

$config=JFactory::getConfig();
$primaryLanguage=$config->get('primaryLanguage',14,'int');
$session =JFactory::getSession();

$language_id=$session->get('language_id',$primaryLanguage);
$js='
var global_language_id='.$language_id.';
var primaryLanguage='.$primaryLanguage.';'
;
$doc->addScriptDeclaration($js);
?>
<script type="text/javascript">
    jQuery( document ).ready(function($) {
        $('.btn-change-language').popover({
            html : true,
            trigger: 'click',
            placement:'bottom',
            content: function() {
                return $('#change_langueage_popover_content_wrapper').html();
            }
        });


    });
</script>



