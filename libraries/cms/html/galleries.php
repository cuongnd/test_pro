<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  HTML
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Utility class for icons.
 *
 * @package     Joomla.Libraries
 * @subpackage  HTML
 * @since       2.5
 */
abstract class JHtmlGalleries
{
    /**
     * Method to generate html code for a list of buttons
     *
     * @param   array $buttons Array of buttons
     *
     * @return  string
     *
     * @since   2.5
     */
    public static function edit_gallery($class_right, $name, $list_image = array(), $attr = array())
    {

        $class = $attr['class'] . ' form-control';
        unset($attr['class']);
        $str_attr = array();
        foreach ($attr as $key => $value) {
            $str_attr[] = "$key=\"$value\"";
        }
        $str_attr = implode(' ', $str_attr);
        $a_list_image = array();
        $doc = JFactory::getDocument();
        JHtml::_('jquery.framework');
        $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/core.js');
        $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/widget.js');
        $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/mouse.js');
        $doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/sortable.js');
        $doc->addScript(JUri::root() . '/libraries/cms/html/galleries.js');
        $doc->addScript(JUri::root() . 'media/system/js/aviary_editor.js');
        $doc->addStyleSheet(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/themes/base/all.css');
        $doc->addStyleSheet(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/themes/base/sortable.css');
        $doc->addLessStyleSheetTest(JUri::root() . '/libraries/cms/html/galleries.less');
        $scriptId = "script_$name";
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#jhtml_galleries_<?php echo $name ?>').jhtml_galleries();

            });
        </script>
        <?php
        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $doc->addScriptDeclaration($script, "text/javascript", $scriptId);


        ob_start();
        ?>
        <div id="jhtml_galleries_<?php echo $name ?>" class="<?php echo $class_right ?> jhtml_galleries">
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <button class="btn btn-primary" type="button"><i class="im-back"></i><?php echo JText::_('add Image from url') ?></button>
                        <button class="btn btn-primary" type="button"><i class="im-back"></i><?php echo JText::_('add Image from computer') ?></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="sortable">
                        <?php
                        for ($i = 0; $i < 5; $i++) {
                            $image = $list_image[$i];
                            $image->src=($src=$image->src)?$src:JUri::root().'/images/stories/no_image.png';
                            ?>
                            <div class="item" data-id="<?php echo $image->virtuemart_media_id ?>">
                                <div class="featured primary">featured</div>
                                <img id="image_<?php echo $i ?>" class="image-item img-responsive"
                                    src="<?php echo $image->src ?>">
                                <div class="control">
                                    <button type="button" class="btn pull-left edit-image"><i class="im-pencil"></i></button>
                                    <button type="button" class="btn pull-right"><i class="fa-list-alt"></i></button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $html = ob_get_clean();
        return $html;
    }


}
