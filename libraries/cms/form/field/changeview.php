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
class JFormFieldChangeView extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    public $type = 'ChangeView';

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
        $doc = JFactory::getDocument();
        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');


        $doc->addLessStyleSheet(JUri::root() . "/libraries/cms/form/field/changeview.less");
        $doc->addScript(JUri::root().'/media/system/js/base64.js');
        $doc->addScript(JUri::root().'/libraries/cms/form/field/changeview.js');
        $db = JFactory::getDbo();

        $tables = $db->getTableList();
        $list_table = array();
        for ($i=0;$i<count($tables);$i++) {
            $table=$tables[$i];
            $list_table[] = str_replace($db->getPrefix(), '#__', $table);
            if($i==10)
            {
                break;
            }
        }
        $website=JFactory::getWebsite();
        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_menus/models');
        $modal_menu_type= JModelLegacy::getInstance('Menutypes','MenusModel');
        $types = $modal_menu_type->getTypeOptionsByWebsiteId($website->website_id);

        $list = array();
        $o = new JObject;
        $o->title = 'COM_MENUS_TYPE_EXTERNAL_URL';
        $o->type = 'url';
        $o->description = 'COM_MENUS_TYPE_EXTERNAL_URL_DESC';
        $o->request = null;
        $list[] = $o;

        $o = new JObject;
        $o->title = 'COM_MENUS_TYPE_ALIAS';
        $o->type = 'alias';
        $o->description = 'COM_MENUS_TYPE_ALIAS_DESC';
        $o->request = null;
        $list[] = $o;

        $o = new JObject;
        $o->title = 'COM_MENUS_TYPE_SEPARATOR';
        $o->type = 'separator';
        $o->description = 'COM_MENUS_TYPE_SEPARATOR_DESC';
        $o->request = null;
        $list[] = $o;

        $o = new JObject;
        $o->title = 'COM_MENUS_TYPE_HEADING';
        $o->type = 'heading';
        $o->description = 'COM_MENUS_TYPE_HEADING_DESC';
        $o->request = null;
        $list[] = $o;

        $types['COM_MENUS_TYPE_SYSTEM']['backend'] = $list;
        $types['COM_MENUS_TYPE_SYSTEM']['frontend'] = $list;
        $sortedTypes = array();
        foreach ($types as $name => $a_list) {

            if (!count($a_list))
                continue;


            foreach ($a_list as $key => $b_list) {
                if (!count($b_list))
                    continue;
                $tmp = array();

                foreach ($b_list as $item) {
                    $tmp[JText::_($item->title)] = $item;
                }
                ksort($tmp);
                $sortedTypes[$key][JText::_($name)] = $tmp;
            }
        }
        ksort($sortedTypes);

        $types = $sortedTypes;
        $user=JFactory::getUser();
        $show_popup_control=$user->getParam('option.webdesign.show_popup_control',false);
        $show_popup_control=JUtility::toStrictBoolean($show_popup_control);
        $app=JFactory::getApplication();
        $menu_item_id=$app->input->get('id',0,'int');
        $scriptId = "script_field_change_view_" . $menu_item_id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.field-change-view-config-item-<?php echo $menu_item_id ?>').field_change_view({
                    show_popup_control:<?php echo json_encode($show_popup_control) ?>,
                    menu_item_id:<?php echo $menu_item_id ?>
                });


            });
        </script>
        <?php
        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $doc->addScriptDeclaration($script, "text/javascript", $scriptId);



        $listFunction = JFormFieldDatasource::getListAllFunction();

        $html = '';
        ob_start();
        ?>
        <div class="field-change-view-config-item-<?php echo $menu_item_id ?>">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="row-fluid">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <?php $i=0 ?>
                            <?php
                            foreach ($types as $key => $type) {
                                if($key=='backend')
                                {
                                    continue;
                                }
                                ?>
                                <li class="<?php echo $i==0?'active':'' ?>">
                                    <a href="#<?php echo $key ?>" role="tab" data-toggle="tab">
                                        <icon class="fa fa-home"></icon> <?php echo $key ?>
                                    </a>
                                </li>
                                <?php $i++ ?>
                            <?php } ?>


                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <?php $i = 0; ?>
                            <?php $j = 0; ?>
                            <?php
                            foreach ($types as $key => $type) {
                                if($key=='backend')
                                {
                                    continue;
                                }
                                ?>

                                <div class="tab-pane fade <?php echo $i==0?'active':'' ?>  in" id="<?php echo $key ?>">


                                    <?php echo JHtml::_('bootstrap.startAccordion', 'collapseTypes-'.$key, array('active' => 'slide1')); ?>

                                    <?php foreach ($type as $name => $list) : ?>
                                        <?php echo JHtml::_('bootstrap.addSlide', 'collapseTypes', $name, 'collapse' . $j); ?>
                                        <ul class="nav nav-tabs nav-stacked">
                                            <?php
                                            foreach ($list as $title => $item) :

                                                ?>

                                                <li>
                                                    <a class="choose_type" href="javascript:void(0)" title="<?php echo JText::_($item->description); ?>"
                                                       data-seleted="<?php echo base64_encode(json_encode(array('title' => (isset($item->type) ? $item->type : $item->title), 'request' => $item->request))); ?>">
                                                        <?php echo $title; ?>
                                                        <small class="muted"><?php echo JText::_($item->description); ?></small>
                                                    </a>
                                                    <?php if(!$item->request){ ?>
                                                    <ul>
                                                        <?php if($item->type=='url'){ ?>
                                                            <li><label>link<input type="text" value="<?php echo $item->link ?>"> </label></li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php echo JHtml::_('bootstrap.endSlide'); ?>

                                        <?php $j++; ?>
                                    <?php endforeach; ?>
                                    <?php echo JHtml::_('bootstrap.endAccordion'); ?>


                                </div>
                                <?php $i++; ?>
                            <?php } ?>


                        </div>

                    </div>

                </div>
            </div>
            <input type="hidden" class="input_link" value="<?php echo trim($this->value) ?>" name="<?php echo $this->name ?>" />
            <button class="btn btn-danger save-view-config pull-right" >
                <i class="fa-save"></i>Save&close                </button>
            &nbsp;&nbsp;
            <button class="btn btn-danger apply-view-config pull-right"><i
                    class="fa-save"></i>Save
            </button>
            &nbsp;&nbsp;
            <button class="btn btn-danger cancel-view-config pull-right" ><i
                    class="fa-save"></i>Cancel
            </button>

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
