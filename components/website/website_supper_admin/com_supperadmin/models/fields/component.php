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
class JFormFieldcomponent extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'component';

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
        $doc->addScript(JUri::root().'/media/system/js/jquery.inputmask-3.x/js/jquery.inputmask.js');
        $doc->addScript(JUri::root().'/media/system/js/jquery.inputmask-3.x/js/inputmask.js');
        $doc->addScript(JUri::root().'components/website/website_supper_admin/com_supperadmin/models/fields/jquery.component.js');
        $scriptId = "script_field_component_" . $this->id;
        $element_id='field_component_'.$this->id;
        $data=$this->form->getData();
        $data=$data->toObject();
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $element_id ?>').field_component({
                    field:{
                        name:"<?php echo $this->name ?>",
                        id:"<?php echo $this->id ?>",
                        data:<?php echo json_encode($data) ?>
                    }

                });


            });
        </script>
        <?php
        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $doc->addScriptDeclaration($script, "text/javascript", $scriptId);

        ob_start();
        ?>
        <div id="<?php echo $element_id ?>">
            <input class="form-control" type="text" name="<?php echo $this->name?>" id="<?php echo $this->id ?>" value="<?php echo $this->value ?>">
        </div>
        <?php
        $html=ob_get_clean();
        return $html;
    }
}
