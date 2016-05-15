<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

JFormHelper::loadFieldClass('text');

/**
 * Form Field class for the Joomla Platform.
 * Provides and input field for e-mail addresses
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.email.html#input.email
 * @see         JFormRuleEmail
 * @since       11.1
 */
class JFormFieldfolderextension extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'folderextension';

    /**
     * Method to get the field input markup for e-mail addresses.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {
        $doc=JFactory::getDocument();
        JHtml::_('jquery.framework');
        $doc->addLessStyleSheet(JUri::root().'media/system/js/select2-4.0.0/dist/css/select2.css');
        $doc->addScript(JUri::root().'/media/system/js/select2-4.0.0/dist/js/select2.full.js');
        $doc->addScript(JUri::root().'components/website/website_supper_admin/com_supperadmin/models/fields/jquery.select_folder.js');
        $scriptId = "script_field_select_folder_" . $this->id;
        $element_id='field_select_folder_'.$this->id;
        $folder_type=$this->element['folder_type'];
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $element_id ?>').field_select_folder({

                });


            });
        </script>
        <?php
        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $folder_id=$this->form->getValue('folder_id');

        $doc->addScriptDeclaration($script, "text/javascript", $scriptId);
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('website.id,website.name,folder.id AS folder_id')
            ->from('#__website AS website')
            ->leftJoin('#__folders AS folder ON folder.website_id=website.id AND folder.id='.(int)$folder_id)
            ;

        $db->setQuery($query);

        $list_website=$db->loadObjectList();


        $list_folder=[];




        ob_start();
        ?>
        <div id="<?php echo $element_id ?>">
            <select   class="list-website" disableChosen="true" >
                <option value=""><?php echo JText::_('please select website') ?></option>
                <?php foreach($list_website AS $website){ ?>
                <option <?php echo ($this->value&&$website->folder_id==$this->value)?'selected':'' ?>  value="<?php echo $website->id ?>"><?php echo $website->name ?></option>
                <?php } ?>
            </select>
            <select disableChosen="true" name="<?php echo $this->name?>" id="<?php echo $this->id ?>">
                <option value=""><?php echo JText::_('please select folder') ?></option>
                <?php foreach($list_folder as $folder){ ?>
                    <option data-element="<?php echo $folder->element ?>" <?php echo ($this->value&&$folder->id==$this->value)?'selected':'' ?> value="<?php echo $folder->id ?>"><?php echo $folder->element ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html=ob_get_clean();
        return $html;
    }
}
