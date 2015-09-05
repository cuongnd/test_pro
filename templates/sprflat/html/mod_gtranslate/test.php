<?php
/**
 * @version   $Id: default.php 164 2012-08-05 17:25:27Z edo888 $
 * @package   GTranslate
 * @copyright Copyright (C) 2008-2012 Edvard Ananyan. All rights reserved.
 * @license   GNU/GPL v3 http://www.gnu.org/licenses/gpl.html
 */

defined('_JEXEC') or die('Restricted access');

if($pro_version or $enterprise_version)
    $method = 'standard';

$lang_array = array('en'=>'English','ar'=>'Arabic','bg'=>'Bulgarian','zh-CN'=>'Chinese (Simplified)','zh-TW'=>'Chinese (Traditional)','hr'=>'Croatian','cs'=>'Czech','da'=>'Danish','nl'=>'Dutch','fi'=>'Finnish','fr'=>'French','de'=>'German','el'=>'Greek','hi'=>'Hindi','it'=>'Italian','ja'=>'Japanese','ko'=>'Korean','no'=>'Norwegian','pl'=>'Polish','pt'=>'Portuguese','ro'=>'Romanian','ru'=>'Russian','es'=>'Spanish','sv'=>'Swedish','ca'=>'Catalan','tl'=>'Filipino','iw'=>'Hebrew','id'=>'Indonesian','lv'=>'Latvian','lt'=>'Lithuanian','sr'=>'Serbian','sk'=>'Slovak','sl'=>'Slovenian','uk'=>'Ukrainian','vi'=>'Vietnamese','sq'=>'Albanian','et'=>'Estonian','gl'=>'Galician','hu'=>'Hungarian','mt'=>'Maltese','th'=>'Thai','tr'=>'Turkish','fa'=>'Persian','af'=>'Afrikaans','ms'=>'Malay','sw'=>'Swahili','ga'=>'Irish','cy'=>'Welsh','be'=>'Belarusian','is'=>'Icelandic','mk'=>'Macedonian','yi'=>'Yiddish','hy'=>'Armenian','az'=>'Azerbaijani','eu'=>'Basque','ka'=>'Georgian','ht'=>'Haitian Creole','ur'=>'Urdu');
$flag_map = array();
$i = $j = 0;
foreach($lang_array as $lang => $lang_name) {
    $flag_map[$lang] = array($i*100, $j*100);
    if($i == 7) {
        $i = 0;
        $j++;
    } else {
        $i++;
    }
}

$flag_map_vertical = array();
$i = 0;
foreach($lang_array as $lang => $lang_name) {
    $flag_map_vertical[$lang] = $i*16;
    $i++;
}
JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');
$document=JFactory::getDocument();

asort($lang_array);
// Move the default language to the first position
$lang_array = array_merge(array($language => $lang_array[$language]), $lang_array);
?>
<div class="btn btn-change-language"><?php echo JText::_('Change language') ?></div>
<div id="change_langueage_popover_content_wrapper" title="change language Bootstrap Popover" style="display: none">
    hello
</div>

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
<div id="google_translate_element">hello</div>

<script type="text/javascript">

    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'vn',
                layout: google.translate.TranslateElement.FloatPosition.TOP_LEFT},
            'google_translate_element');
    }

</script>

<script type="text/javascript" src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>