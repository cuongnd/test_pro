<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Form Field class for the Joomla Platform.
 * Supports a one line text field.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.text.html#input.text
 * @since       11.1
 */
class JFormFieldAdvancedBindingSourceSelect2 extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     *
     * @since  11.1
     */
    protected $type = 'advancedbindingsourceselect2';

    /**
     * The allowable maxlength of the field.
     *
     * @var    integer
     * @since  3.2
     */
    protected $maxLength;

    /**
     * The mode of input associated with the field.
     *
     * @var    mixed
     * @since  3.2
     */
    protected $inputmode;

    /**
     * The name of the form field direction (ltr or rtl).
     *
     * @var    string
     * @since  3.2
     */
    protected $dirname;

    /**
     * Method to get certain otherwise inaccessible properties from the form field object.
     *
     * @param   string $name The property name for which to the the value.
     *
     * @return  mixed  The property value or null.
     *
     * @since   3.2
     */
    public function __get($name)
    {
        switch ($name) {
            case 'maxLength':
            case 'dirname':
            case 'inputmode':
                return $this->$name;
        }

        return parent::__get($name);
    }

    /**
     * Method to set certain otherwise inaccessible properties of the form field object.
     *
     * @param   string $name The property name for which to the the value.
     * @param   mixed $value The value of the property.
     *
     * @return  void
     *
     * @since   3.2
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'maxLength':
                $this->maxLength = (int)$value;
                break;

            case 'dirname':
                $value = (string)$value;
                $value = ($value == $name || $value == 'true' || $value == '1');

            case 'inputmode':
                $this->name = (string)$value;
                break;

            default:
                parent::__set($name, $value);
        }
    }

    /**
     * Method to attach a JForm object to the field.
     *
     * @param   SimpleXMLElement $element The SimpleXMLElement object representing the <field /> tag for the form field object.
     * @param   mixed $value The form field value to validate.
     * @param   string $group The field name group control value. This acts as as an array container for the field.
     *                                      For example if the field has name="foo" and the group value is set to "bar" then the
     *                                      full field name would end up being "bar[foo]".
     *
     * @return  boolean  True on success.
     *
     * @see     JFormField::setup()
     * @since   3.2
     */
    public function setup(SimpleXMLElement $element, $value, $group = null)
    {
        $result = parent::setup($element, $value, $group);

        if ($result == true) {
            $inputmode = (string)$this->element['inputmode'];
            $dirname = (string)$this->element['dirname'];

            $this->inputmode = '';
            $inputmode = preg_replace('/\s+/', ' ', trim($inputmode));
            $inputmode = explode(' ', $inputmode);

            if (!empty($inputmode)) {
                $defaultInputmode = in_array('default', $inputmode) ? JText::_("JLIB_FORM_INPUTMODE") . ' ' : '';

                foreach (array_keys($inputmode, 'default') as $key) {
                    unset($inputmode[$key]);
                }

                $this->inputmode = $defaultInputmode . implode(" ", $inputmode);
            }

            // Set the dirname.
            $dirname = ((string)$dirname == 'dirname' || $dirname == 'true' || $dirname == '1');
            $this->dirname = $dirname ? $this->getName($this->fieldname . '_dir') : false;

            $this->maxLength = (int)$this->element['maxlength'];
        }

        return $result;
    }

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {
        $doc = JFactory::getDocument();
        $lessInput = JPATH_ROOT . '/libraries/joomla/form/fields/advancedbindingsourceselect2.less';
        $cssOutput = JPATH_ROOT . '/libraries/joomla/form/fields/advancedbindingsourceselect2.css';
        JUtility::compileLess($lessInput, $cssOutput);

        $doc->addStyleSheet(JUri::root().'/libraries/joomla/form/fields/advancedbindingsourceselect2.css');
        $doc->addScript(JUri::root().'/libraries/joomla/form/fields/advancedbindingsourceselect2.js');
        // Translate placeholder text
        $hint = $this->translateHint ? JText::_($this->hint) : $this->hint;

        // Initialize some field attributes.
        $size = !empty($this->size) ? ' size="' . $this->size . '"' : '';
        $maxLength = !empty($this->maxLength) ? ' maxlength="' . $this->maxLength . '"' : '';
        $class = 'form-control';
        $class = !empty($this->class) ? $class . ' ' . $this->class : $class;
        $class = 'class="' . $class . '"';
        $readonly = $this->readonly ? ' readonly' : '';
        $disabled = $this->disabled ? ' disabled' : '';
        $required = $this->required ? ' required aria-required="true"' : '';
        $hint = $hint ? ' placeholder="' . $hint . '"' : '';
        $app = JFactory::getApplication();
        $autocomplete = !$this->autocomplete ? ' autocomplete="off"' : ' autocomplete="' . $this->autocomplete . '"';
        $autocomplete = $autocomplete == ' autocomplete="on"' ? '' : $autocomplete;
        $autofocus = $this->autofocus ? ' autofocus' : '';
        $spellcheck = $this->spellcheck ? '' : ' spellcheck="false"';
        $pattern = !empty($this->pattern) ? ' pattern="' . $this->pattern . '"' : '';
        $inputmode = !empty($this->inputmode) ? ' inputmode="' . $this->inputmode . '"' : '';
        $dirname = !empty($this->dirname) ? ' dirname="' . $this->dirname . '"' : '';
        $maximumSelectionLength = $this->element['maximumSelectionLength'] ? (string)$this->element['maximumSelectionLength'] : 10;
        $maximumSelectionSize = $this->element['maximumSelectionSize'] ? (string)$this->element['maximumSelectionSize'] : 10;
        $tags = $this->element['tags'] ? (boolean)$this->element['tags'] : false;
        $selectOPtion = array(
            maximumSelectionLength => $maximumSelectionLength,
            maximumSelectionSize => $maximumSelectionSize
        );

        // Initialize JavaScript field attributes.
        $onchange = !empty($this->onchange) ? ' onchange="' . $this->onchange . '"' : '';

        // Including fallback code for HTML5 non supported browsers.
        JHtml::_('jquery.framework');
        JHtml::_('script', 'system/html5fallback.js', false, true);
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_phpmyadmin/models');
        $dataSourceModal = JModelLegacy::getInstance('DataSources', 'phpMyAdminModel');
        $currentDataSource = $dataSourceModal->getCurrentDataSources();
        foreach ($currentDataSource as $item) {
            $a_item = new stdClass();
            $a_item->id = $item->datasource->id;
            $a_item->text = $item->datasource->title;
            $options[] = $a_item;
            foreach ($item->listField as $key => $field) {
                $a_item = new stdClass();
                $a_item->id = "{$item->datasource->id}.{$key}";
                $a_item->text = "{$item->datasource->title}.{$key}";
                $options[] = $a_item;
            }
        }
        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/models');
        $dataSourceModal=JModelLegacy::getInstance('DataSources','phpMyAdminModel');
        $currentDataSource=$dataSourceModal->getCurrentDataSources();
        ob_start();
        $scriptId="lib_joomla_form_fields_advancedbindingsourceselect2".JUserHelper::genRandomPassword();
        ?>
        <script type="text/javascript">
                $('select.select_data_source').chosen();
        </script>
        <?php
        $script=ob_get_clean();
        $script=JUtility::remove_string_javascript($script);
        $doc->addAjaxCallFunction($scriptId,$script,$scriptId);



        $selectOPtion['tags'] = $options;
        $block_id = $app->input->get('block_id', 0, 'int');
        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_utility/models');
        $app->input->set('id', $block_id);
        $modelPosition = JModelLegacy::getInstance('Position', 'UtilityModel');
        $item = $modelPosition->getItem();
        $form = $modelPosition->getForm();
        $options = $form->getFieldsets();
        ob_start();
        ?>
        <div class="form-horizontal ">
            <div class="form-group">
                <div class="col-xs-5 control-label">
                    Filter
                </div>
                <div class="col-xs-7">
                    <div class="input-group">
                        <input class="form-control" value="" name="filter_label">
                    </div>
                </div>
            </div>
        </div>

        <div class="properties block" data-object-id="<?php echo $block_id ?>">
            <div class="panel-group" id="accordion<?php echo $block_id ?>" role="tablist" aria-multiselectable="true">
                <?php
                foreach ($options as $key => $option) {
                    $fieldSet = $form->getFieldset($key);

                    ?>

                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="heading<?php echo $key ?>">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion<?php echo $block_id ?>"
                                   href="#collapse<?php echo $key ?>" aria-expanded="true"
                                   aria-controls="collapse<?php echo $key ?>">
                                    <?php echo $option->label ?>
                                </a>
                            </h4>
                        </div>
                        <div id="collapse<?php echo $key ?>" class="panel-collapse collapse in" role="tabpanel"
                             aria-labelledby="heading<?php echo $key ?>">
                            <div class="panel-body">

                                <?php
                                foreach ($fieldSet as $field) {
                                    ?>
                                    <div class="form-horizontal property-item">
                                        <?php

                                        echo $field->renderField(array(), true,true,$currentDataSource);
                                        ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
        <?php

        $html = ob_get_clean();


        return $html;
    }

    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
     *
     * @since   3.4
     */
    protected function getOptions()
    {
        $options = array();

        foreach ($this->element->children() as $option) {
            // Only add <option /> elements.
            if ($option->getName() != 'option') {
                continue;
            }

            // Create a new option object based on the <option /> element.
            $options[] = JHtml::_(
                'select.option', (string)$option['value'],
                JText::alt(trim((string)$option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text'
            );
        }

        return $options;
    }

    /**
     * Method to get the field suggestions.
     *
     * @return  array  The field option objects.
     *
     * @since       3.2
     * @deprecated  4.0  Use getOptions instead
     */
    protected function getSuggestions()
    {
        return $this->getOptions();
    }
}
