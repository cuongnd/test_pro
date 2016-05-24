<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

JFormHelper::loadFieldClass('textarea');

/**
 * Form Field class for the Joomla CMS.
 * A textarea field for content creation
 *
 * @package     Joomla.Libraries
 * @subpackage  Form
 * @see         JEditor
 * @since       1.6
 */
class JFormFieldanimationgenerator extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    public $type = 'animationgenerator';

    /**
     * The JEditor object.
     *
     * @var    JEditor
     * @since  1.6
     */
    protected $editor;

    /**
     * The height of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $height;

    /**
     * The width of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $width;

    /**
     * The assetField of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $assetField;

    /**
     * The authorField of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $authorField;

    /**
     * The asset of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $asset;

    /**
     * The buttons of the editor.
     *
     * @var    mixed
     * @since  3.2
     */
    protected $buttons;

    /**
     * The hide of the editor.
     *
     * @var    array
     * @since  3.2
     */
    protected $hide;

    /**
     * The editorType of the editor.
     *
     * @var    array
     * @since  3.2
     */
    protected $editorType;

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
            case 'height':
            case 'width':
            case 'assetField':
            case 'authorField':
            case 'asset':
            case 'buttons':
            case 'hide':
            case 'editorType':
                return $this->$name;
        }

        return parent::__get($name);
    }

    public function get_attribute_config()
    {
        return array(
            element_apply_style => ''
        );
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
            case 'height':
            case 'width':
            case 'assetField':
            case 'authorField':
            case 'asset':
                $this->$name = (string)$value;
                break;

            case 'buttons':
                $value = (string)$value;

                if ($value == 'true' || $value == 'yes' || $value == '1') {
                    $this->buttons = true;
                } elseif ($value == 'false' || $value == 'no' || $value == '0') {
                    $this->buttons = false;
                } else {
                    $this->buttons = explode(',', $value);
                }
                break;

            case 'hide':
                $value = (string)$value;
                $this->hide = $value ? explode(',', $value) : array();
                break;

            case 'editorType':
                // Can be in the form of: editor="desired|alternative".
                $this->editorType = explode('|', trim((string)$value));
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
            $this->height = $this->element['height'] ? (string)$this->element['height'] : '500';
            $this->width = $this->element['width'] ? (string)$this->element['width'] : '100%';
            $this->assetField = $this->element['asset_field'] ? (string)$this->element['asset_field'] : 'asset_id';
            $this->authorField = $this->element['created_by_field'] ? (string)$this->element['created_by_field'] : 'created_by';
            $this->asset = $this->form->getValue($this->assetField) ? $this->form->getValue($this->assetField) : (string)$this->element['asset_id'];

            $buttons = (string)$this->element['buttons'];
            $hide = (string)$this->element['hide'];
            $editorType = (string)$this->element['editor'];

            if ($buttons == 'true' || $buttons == 'yes' || $buttons == '1') {
                $this->buttons = true;
            } elseif ($buttons == 'false' || $buttons == 'no' || $buttons == '0') {
                $this->buttons = false;
            } else {
                $this->buttons = !empty($hide) ? explode(',', $buttons) : array();
            }

            $this->hide = !empty($hide) ? explode(',', (string)$this->element['hide']) : array();
            $this->editorType = !empty($editorType) ? explode('|', trim($editorType)) : array();
        }

        return $result;
    }

    /**
     * Method to get the field input markup for the editor area
     *
     * @return  string  The field input markup.
     *
     * @since   1.6
     */


    protected function getInput()
    {
        $app = JFactory::getApplication();
        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');
        JHtml::_('bootstrap.modal');
        $doc = JFactory::getDocument();
        $doc->addLessStyleSheetTest(JUri::root() . '/libraries/cms/form/field/animationgenerator.less');
         $doc->addStyleSheet(JUri::root() . '/media/system/js/animate.css-master/animate.css');
        $doc->addScript(JUri::root() . '/libraries/cms/form/field/animationgenerator.js');
        $scriptId = "script_lib_cms_form_fields_animation_generator" . $this->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $this->id ?>').animation_generator({
                    field:{
                        name:"<?php echo $this->name ?>"
                    }
                });

            });
        </script>
        <?php
        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $doc->addScriptDeclaration($script, "text/javascript", $scriptId);
        $db = JFactory::getDbo();
        $html = '';
        ob_start();
        ?>
        <div id="<?php echo $this->id ?>">
            <div class="row">

                <div class="col-md-12 animationgenerator">
                    <div class="wrap">
                        <span style="display: block;" id="animationSandbox" class="<?php echo $this->value ?> animated"><h1 class="site__title mega">
                                Animate.css</h1></span>
                        <span class="beta subhead">Just-add-water CSS animations</span>
                    </div>

                    <form>
                        <select disableChosen="true" class="input input--dropdown js--animations">
                            <optgroup label="Attention Seekers">
                                <option value="bounce">bounce</option>
                                <option value="flash">flash</option>
                                <option value="pulse">pulse</option>
                                <option value="rubberBand">rubberBand</option>
                                <option value="shake">shake</option>
                                <option value="swing">swing</option>
                                <option value="tada">tada</option>
                                <option value="wobble">wobble</option>
                                <option value="jello">jello</option>
                            </optgroup>

                            <optgroup label="Bouncing Entrances">
                                <option value="bounceIn">bounceIn</option>
                                <option value="bounceInDown">bounceInDown</option>
                                <option value="bounceInLeft">bounceInLeft</option>
                                <option value="bounceInRight">bounceInRight</option>
                                <option value="bounceInUp">bounceInUp</option>
                            </optgroup>

                            <optgroup label="Bouncing Exits">
                                <option value="bounceOut">bounceOut</option>
                                <option value="bounceOutDown">bounceOutDown</option>
                                <option value="bounceOutLeft">bounceOutLeft</option>
                                <option value="bounceOutRight">bounceOutRight</option>
                                <option value="bounceOutUp">bounceOutUp</option>
                            </optgroup>

                            <optgroup label="Fading Entrances">
                                <option value="fadeIn">fadeIn</option>
                                <option value="fadeInDown">fadeInDown</option>
                                <option value="fadeInDownBig">fadeInDownBig</option>
                                <option value="fadeInLeft">fadeInLeft</option>
                                <option value="fadeInLeftBig">fadeInLeftBig</option>
                                <option value="fadeInRight">fadeInRight</option>
                                <option value="fadeInRightBig">fadeInRightBig</option>
                                <option value="fadeInUp">fadeInUp</option>
                                <option value="fadeInUpBig">fadeInUpBig</option>
                            </optgroup>

                            <optgroup label="Fading Exits">
                                <option value="fadeOut">fadeOut</option>
                                <option value="fadeOutDown">fadeOutDown</option>
                                <option value="fadeOutDownBig">fadeOutDownBig</option>
                                <option value="fadeOutLeft">fadeOutLeft</option>
                                <option value="fadeOutLeftBig">fadeOutLeftBig</option>
                                <option value="fadeOutRight">fadeOutRight</option>
                                <option value="fadeOutRightBig">fadeOutRightBig</option>
                                <option value="fadeOutUp">fadeOutUp</option>
                                <option value="fadeOutUpBig">fadeOutUpBig</option>
                            </optgroup>

                            <optgroup label="Flippers">
                                <option value="flip">flip</option>
                                <option value="flipInX">flipInX</option>
                                <option value="flipInY">flipInY</option>
                                <option value="flipOutX">flipOutX</option>
                                <option value="flipOutY">flipOutY</option>
                            </optgroup>

                            <optgroup label="Lightspeed">
                                <option value="lightSpeedIn">lightSpeedIn</option>
                                <option value="lightSpeedOut">lightSpeedOut</option>
                            </optgroup>

                            <optgroup label="Rotating Entrances">
                                <option value="rotateIn">rotateIn</option>
                                <option value="rotateInDownLeft">rotateInDownLeft</option>
                                <option value="rotateInDownRight">rotateInDownRight</option>
                                <option value="rotateInUpLeft">rotateInUpLeft</option>
                                <option value="rotateInUpRight">rotateInUpRight</option>
                            </optgroup>

                            <optgroup label="Rotating Exits">
                                <option value="rotateOut">rotateOut</option>
                                <option value="rotateOutDownLeft">rotateOutDownLeft</option>
                                <option value="rotateOutDownRight">rotateOutDownRight</option>
                                <option value="rotateOutUpLeft">rotateOutUpLeft</option>
                                <option value="rotateOutUpRight">rotateOutUpRight</option>
                            </optgroup>

                            <optgroup label="Sliding Entrances">
                                <option value="slideInUp">slideInUp</option>
                                <option value="slideInDown">slideInDown</option>
                                <option value="slideInLeft">slideInLeft</option>
                                <option value="slideInRight">slideInRight</option>

                            </optgroup>
                            <optgroup label="Sliding Exits">
                                <option value="slideOutUp">slideOutUp</option>
                                <option value="slideOutDown">slideOutDown</option>
                                <option value="slideOutLeft">slideOutLeft</option>
                                <option value="slideOutRight">slideOutRight</option>

                            </optgroup>

                            <optgroup label="Zoom Entrances">
                                <option value="zoomIn">zoomIn</option>
                                <option value="zoomInDown">zoomInDown</option>
                                <option value="zoomInLeft">zoomInLeft</option>
                                <option value="zoomInRight">zoomInRight</option>
                                <option value="zoomInUp">zoomInUp</option>
                            </optgroup>

                            <optgroup label="Zoom Exits">
                                <option value="zoomOut">zoomOut</option>
                                <option value="zoomOutDown">zoomOutDown</option>
                                <option value="zoomOutLeft">zoomOutLeft</option>
                                <option value="zoomOutRight">zoomOutRight</option>
                                <option value="zoomOutUp">zoomOutUp</option>
                            </optgroup>

                            <optgroup label="Specials">
                                <option value="hinge">hinge</option>
                                <option value="rollIn">rollIn</option>
                                <option value="rollOut">rollOut</option>
                            </optgroup>
                        </select>

                        <button type="button" class="butt js--triggerAnimation">Animate it</button>
                    </form>

                </div>

            </div>
            <input type="hidden" name="<?php echo $this->name ?>" value="<?php echo $this->value ?>"/>
        </div>


        <?php
        $html .= ob_get_clean();
        return $html;
    }

    /**
     * Method to get a JEditor object based on the form field.
     *
     * @return  JEditor  The JEditor object.
     *
     * @since   1.6
     */
    public function getListAllFunction()
    {
        $listFunction = array(
            'aggregate' => array(
                'avg(expr)'
            , 'count(expr)'
            , 'group_concat(expr)'
            ),
            'cast' => array(
                'cast(expr)'

            ),
            'date and time' => array(
                'adddate(date,interval,exprunit)'
            , 'adddate(expr,days)'
            , 'addtime(expr1,expr2)'
            ),
            'mathematical' => array(),
            'other' => array(),
            'string' => array()
        );
        return $listFunction;
    }

    protected function getEditor()
    {
        // Only create the editor if it is not already created.
        if (empty($this->editor)) {
            $editor = null;

            if ($this->editorType) {
                // Get the list of editor types.
                $types = $this->editorType;

                // Get the database object.
                $db = JFactory::getDbo();

                // Iterate over teh types looking for an existing editor.
                foreach ($types as $element) {
                    // Build the query.
                    $query = $db->getQuery(true)
                        ->select('element')
                        ->from('#__extensions')
                        ->where('element = ' . $db->quote($element))
                        ->where('folder = ' . $db->quote('editors'))
                        ->where('enabled = 1');

                    // Check of the editor exists.
                    $db->setQuery($query, 0, 1);
                    $editor = $db->loadResult();

                    // If an editor was found stop looking.
                    if ($editor) {
                        break;
                    }
                }
            }

            // Create the JEditor instance based on the given editor.
            if (is_null($editor)) {
                $conf = JFactory::getConfig();
                $editor = $conf->get('editor');
            }

            $this->editor = JEditor::getInstance($editor);
        }

        return $this->editor;
    }

    /**
     * Method to get the JEditor output for an onSave event.
     *
     * @return  string  The JEditor object output.
     *
     * @since   1.6
     */
    public function save()
    {
        return $this->getEditor()->save($this->id);
    }
}
