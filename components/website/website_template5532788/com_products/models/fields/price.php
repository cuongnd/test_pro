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
class JFormFieldPrice extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'price';

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
        $website=JFactory::getWebsite();
        $config=productsconfig::get_com_products_config();
        $default_currency_id=$config->params->get('default_currency',0);;
        $query = $db->getQuery(true);
        $query->clear()
            ->select('currency.*')
            ->from('#__ecommerce_currency AS currency')
            ->where('id='.(int)$default_currency_id)
        ;
        $currency=$db->setQuery($query)->loadObject();
        $doc=JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root().'media/system/js/select2-4.0.0/dist/css/select2.css');
        $doc->addScript(JUri::root().'/media/system/js/select2-4.0.0/dist/js/select2.full.js');
        $doc->addScript(JUri::root().'/media/system/js/BobKnothe-autoNumeric/autoNumeric.js');
        $doc->addScript(JUri::root().'/components/website/website_template5532788/com_products/models/fields/jquery.price.js');
        $script_id = "script_field_parent_product_category_" . $this->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $this->id; ?>').field_price({
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
        <div id="<?php echo $this->id ?>" class="<?php echo $this->id ?>"  >
            <input type="text"  class="auto price form-control input-xxlarge input-large-text" data-a-sign="<?php echo $currency->currency_symbol ?> " value="<?php echo $this->value ?>"   placeholder="<?php echo $currency->currency_symbol ?>"
                   data-v-min="0" data-v-max="999999">
            <input class="form-control input-xxlarge input-large-text" type="hidden" value="<?php echo $this->value ?>" name="<?php echo $this->name ?>" >
        </div>
        <?php
        $html=ob_get_clean();
        return $html;
    }
}
