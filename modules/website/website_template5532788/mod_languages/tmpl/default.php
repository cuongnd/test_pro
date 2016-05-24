<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_languages
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
JHtml::_('jquery.framework');
defined('_JEXEC') or die;
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/media/system/js/Polyglot-Language-Switcher-2-master/js/jquery/jquery-polyglot.language.switcher.js');
$doc->addStyleSheet(JUri::root().'/media/system/js/Polyglot-Language-Switcher-2-master/css/polyglot-language-switcher-2.css');
$doc->addScript(JUri::root().'/modules/website/website_websitetemplatepro/mod_languages/assets/js/mod_languages.js');
$doc->addLessStyleSheet(JUri::root().'/modules/website/website_websitetemplatepro/mod_languages/assets/less/mod_languages.less');
$scriptId = "script_module_change_language_" . $module->id;
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#mod_languages_<?php echo $module->id ?>').mod_languages();
    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);


?>

<div id="mod_languages_<?php echo $module->id  ?>" class="polyglot-language-switcher pull-right">
    <ul style="display: none">
        <li><a href="#" title="English (US)"  data-lang-id="en_US"><img src="<?php echo JUri::root() ?>/media/system/js/Polyglot-Language-Switcher-2-master/images/flags/us.png" alt="United States"> English (US)</a></li>
        <li><a href="#" title="French (France)" data-lang-id="fr_FR"><img src="<?php echo JUri::root() ?>/media/system/js/Polyglot-Language-Switcher-2-master/images/flags/fr.png" alt="France"> Français (France)</a></li>
        <li><a href="#" title="German" data-lang-id="de_DE"><img src="<?php echo JUri::root() ?>/media/system/js/Polyglot-Language-Switcher-2-master/images/flags/de.png" alt="Germany"> Deutsch</a></li>
        <li><a href="#" title="Spanish" data-lang-id="es_ES"><img src="<?php echo JUri::root() ?>/media/system/js/Polyglot-Language-Switcher-2-master/images/flags/es.png" alt="Spain"> Español</a></li>
        <li><a href="#" title="Italian" data-lang-id="it_IT"><img src="<?php echo JUri::root() ?>/media/system/js/Polyglot-Language-Switcher-2-master/images/flags/it.png" alt="Italy"> Italiano</a></li>
        <li><a href="#" title="Portuguese (Portugal)" data-lang-id="pt_PT"><img src="<?php echo JUri::root() ?>/media/system/js/Polyglot-Language-Switcher-2-master/images/flags/pg.png" alt="Portugal"> Português (Portugal)</a></li>
        <li><a href="#" title="Dutch" data-lang-id="nl_NL"><img src="<?php echo JUri::root() ?>/media/system/js/Polyglot-Language-Switcher-2-master/images/flags/nl.png" alt="Netherlands"> Nederlands</a></li>
        <li><a href="#" title="Greek" data-lang-id="gr_GR"><img src="<?php echo JUri::root() ?>/media/system/js/Polyglot-Language-Switcher-2-master/images/flags/gr.png" alt="Greece"> Ελληνικά</a></li>
        <li><a href="#" title="Japanese" data-lang-id="jp_JP"><img src="<?php echo JUri::root() ?>/media/system/js/Polyglot-Language-Switcher-2-master/images/flags/jp.png" alt="Japan"> 日本語</a></li>
        <li><a href="#" title="Simplified Chinese (China)" data-lang-id="cn_CN"><img src="<?php echo JUri::root() ?>/media/system/js/Polyglot-Language-Switcher-2-master/images/flags/cn.png" alt="China"> 中文(简体)</a></li>
        <li><a href="#" title="Hindi" data-lang-id="hi_IN"><img src="<?php echo JUri::root() ?>/media/system/js/Polyglot-Language-Switcher-2-master/images/flags/in.png" alt="India"> हिन्दी</a></li>
    </ul>
</div>
