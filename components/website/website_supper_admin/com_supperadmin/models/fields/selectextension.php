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
class JFormFieldSelectExtension extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'selectextension';

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
        $doc->addScript(JUri::root().'components/website/website_supper_admin/com_supperadmin/models/fields/jquery.select_extension.js');
        $scriptId = "script_field_select_extension_" . $this->id;
        $element_id='field_select_extension_'.$this->id;
        $extension_type=$this->element['extension_type'];
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $element_id ?>').field_selectextension({
                    extension_type:"<?php echo $extension_type ?>",
                    element_id:"<?php echo $this->id ?>",
                    element_name:"<?php echo $this->name ?>",
                    extension:{
                        id:<?php echo ($id=$this->form->getValue('id'))?$id:0 ?>,
                        extension_id:<?php echo ($extension_id=$this->value)?$extension_id:0 ?>



                    }
                });


            });
        </script>
        <?php
        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $extension_id=$this->form->getValue('extension_id');

        $doc->addScriptDeclaration($script, "text/javascript", $scriptId);
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('website.id,website.name,extension.id AS extension_id')
            ->from('#__website AS website')
            ->leftJoin('#__extensions AS extension ON extension.website_id=website.id AND extension.id='.(int)$extension_id)
            ;

        $db->setQuery($query);

        $list_website=$db->loadObjectList();


        $list_extension=[];
        if($this->value) {
            $query = $db->getQuery(true);
            $query->select('extension.*')
                ->from('#__extensions AS extension')
                ->innerJoin('#__website AS website ON website.id=extension.website_id ')
                ->innerJoin('#__extensions AS extension2 ON extension2.website_id=website.id')
                ->where('extension2.id='.(int)$this->value)
                ->where('extension.type='.$query->q($extension_type))
            ;
            $db->setQuery($query);
            $list_extension=$db->loadObjectList();
        }



        ob_start();
        ?>
        <div id="<?php echo $element_id ?>">
            <select   class="list-website" disableChosen="true" >
                <option value=""><?php echo JText::_('please select website') ?></option>
                <?php foreach($list_website AS $website){ ?>
                <option <?php echo ($this->value&&$website->extension_id==$this->value)?'selected':'' ?>  value="<?php echo $website->id ?>"><?php echo $website->name ?></option>
                <?php } ?>
            </select>
            <select disableChosen="true" name="<?php echo $this->name?>" id="<?php echo $this->id ?>">
                <option value=""><?php echo JText::_('please select extension') ?></option>
                <?php foreach($list_extension as $extension){ ?>
                    <option data-element="<?php echo $extension->element ?>" <?php echo ($this->value&&$extension->id==$this->value)?'selected':'' ?> value="<?php echo $extension->id ?>"><?php echo $extension->element ?></option>
                <?php } ?>
            </select>
        </div>
        <?php
        $html=ob_get_clean();
        return $html;
    }
}
