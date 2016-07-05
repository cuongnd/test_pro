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
class JFormFieldCode extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'code';

    /**
     * Method to get the field input markup for e-mail addresses.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $query = $db->getQuery(true);
        $doc=JFactory::getDocument();
        $doc->addScript(JUri::root().'/components/website/website_template5532788/com_phatthanhnghean/models/fields/jquery.code.js');
        $script_id = "script_field_code_" . $this->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $this->id; ?>').field_code({
                    field:{
                        name:"<?php echo $this->name ?>"
                    }
                });


            });
        </script>
        <?php
        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $doc->addScriptDeclaration($script, "text/javascript", $script_id);



        ob_start();
        ?>
        <div id="<?php echo $this->id ?>" class="<?php echo $this->id ?>">
            <div class="input-group">
                <input type="text" class="form-control readonly" name="<?php echo $this->name ?>"  value="<?php echo $this->value ?>" readonly placeholder="<?php echo JText::_('Code') ?>">
                  <span class="input-group-btn">
                    <button class="btn btn-primary generator-code" type="button"><?php echo JText::_('Generator') ?></button>
                  </span>
            </div>
        </div>
        <?php
        $html=ob_get_clean();
        return $html;
    }
}
