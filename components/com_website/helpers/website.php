<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_website
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * website component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_website
 * @since       1.6
 */
class websiteHelperFrontEnd
{
    public static $extension = 'com_website';

    public static function get_website_name_by_website_id($website_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('name')
            ->from('#__website')
            ->where('id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $website_name=$db->loadResult();
        return $website_name;
    }

    public static function get_list_website()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__website');
        $query->select('*');
        $db->setQuery($query);
        $list_website=$db->loadObjectList();
        return $list_website;
    }

    /**
     * Configure the Linkbar.
     *
     * @param   string $vName The name of the active view.
     *
     * @return  void
     *
     * @since   1.6
     */
    function getGenericlistWebsite($name = 'website_id', $attribute = '', $selected)
    {
        require_once JPATH_ROOT . '/components/com_website/helpers/website.php';
        $listWebsite = websiteHelperFrontEnd::getWebsites();
        $option = array();
        $option[] = JHTML::_('select.option', '-1', JText::_("Run for all"));
        $option[] = JHTML::_('select.option', '0', JText::_("None"));
        foreach ($listWebsite as $website) {
            $option[] = JHTML::_('select.option', $website->id, $website->title);

        }
        $select = JHTML::_('select.genericlist', $option, $name, 'class = "btn btn-default inputbox" size = "1" ' . $attribute, 'value', 'text', $selected);

        return $select;
    }
    public static function get_category(){
        $website=JFactory::getWebsite();
        $db = JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('categories.virtuemart_category_id,categories_en_gb.category_name,category_categories.category_parent_id,categories_en_gb2.category_name AS parent_category_name')
            ->from('#__virtuemart_categories AS categories')
            ->leftJoin('#__virtuemart_categories_en_gb AS categories_en_gb  USING(virtuemart_category_id)')
            ->leftJoin('#__virtuemart_category_categories AS category_categories ON category_categories.category_child_id=categories.virtuemart_category_id')
            ->leftJoin('#__virtuemart_categories_en_gb AS categories_en_gb2 ON categories_en_gb2.virtuemart_category_id=category_categories.category_parent_id')
            ->where('categories.website_id='.(int)$website->website_id)
        ;

        $data=$db->setQuery($query)->loadObjectList();
        $list_category_parent = array();
        foreach ($data as $key => $category) {
            if (!$category->category_parent_id) {
                $category->tree_category = $category->category_name;
                $list_category_parent[] = $category;
                unset($data[$key]);
            }
        }
        usort($list_category_parent, function ($category1, $cagory2) {
            if ($category1->ordering == $cagory2->ordering) return 0;
            return $category1->ordering < $cagory2->ordering ? -1 : 1;
        });
        $a_list_category=array();
        foreach ($list_category_parent as $category) {
            $return_list = array();
            $return_list[] = $category;
            self::create_tree_category_list($return_list,  $category->virtuemart_category_id, $data, 0);
            $a_list_category=array_merge($a_list_category,$return_list);
        }
        return $a_list_category;

    }
    function create_tree_category_list(&$return_list_category = array(), $category_parent_id, $list_category, $level = 0)
    {
        $list_category1 = array();
        foreach ($list_category as $key => $category) {
            if ($category->category_parent_id==$category_parent_id) {
                $list_category1[] = $category;
                unset($list_category[$key]);
            }
        }
        usort($list_category1, function ($category1, $category2) {
            if ($category1->ordering == $category2->ordering) return 0;
            return $category1->ordering < $category2->ordering ? -1 : 1;
        });

        $level1 = $level + 1;
        foreach ($list_category1 as $category) {
            $category->tree_category = str_repeat('--', $level1) . $category->category_name;
            $return_list_category[] = $category;
            self::create_tree_category_list($return_list_category, $category->virtuemart_category_id, $list_category, $level1);
        }
    }
    public  function  get_list_websie_enable_create_sub_domain()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__domain_website')
            ->where('enable_create_subdomain=1')
        ;
        return $db->setQuery($query)->loadObjectList();
    }
    public static function copy_rows_table($website_id,JTable $table_instance, $root_id, $id_name, $parent_id_name)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('t_table.*')
            ->from($table_instance->getTableName() . ' As t_table ')
            ->order('t_table.ordering');
        $db->setQuery($query);
        $list_rows = $db->loadObjectList($id_name);
        $children = array();

// First pass - collect children
        foreach ($list_rows as $v) {
            $pt = $v->$parent_id_name;
            $list = @$children[$pt] ? $children[$pt] : array();
            if ($v->$id_name != $v->$parent_id_name) {
                array_push($list, $v);
            }
            $children[$pt] = $list;
        }
        $list_parent_id=array();
        self::execute_copy_rows_table($website_id,$table_instance,$id_name,$parent_id_name,$root_id,$children,$list_parent_id);

    }

    function treeRecurse($id, &$html, &$children, $maxLevel = 9999, $level = 0, $enableEditWebsite = true, $active_menu_item_id = 0, $is_main_frame = false)
    {
        $doc = JFactory::getDocument();
        if (!$active_menu_item_id) {
            $app = JFactory::getApplication();
            $menu = $app->getMenu();
            $active_menu = $menu->getActive();
            $params = $active_menu->params;
            if ($params) {
                $is_main_frame = $params->get('is_main_frame');
                $is_main_frame = JUtility::toStrictBoolean($is_main_frame);
                $active_menu_item_id = $active_menu->id;
            }

        }
        if ($enableEditWebsite) {
            if (@$children[$id] && $level <= $maxLevel) {
                $items = @$children[$id];
                for ($j = 0; $j < count($items); $j++) {
                    $v = $items[$j];
                    $id = $v->id;

                    $css_class = $v->css_class;
                    $css_class = explode(',', $css_class);
                    $css_class = implode(' ', $css_class);
                    $prevV = $items[$j - 1];
                    $params = new JRegistry;
                    $params->loadString($v->params);
                    $axis = $params->get('axis', 'false');
                    $float = $params->get('float', 'none');
                    $cell_height = $params->get('cell_height', 80);
                    $vertical_margin = $params->get('vertical_margin', 0);
                    $amount_of_columns = $params->get('amount_of_columns', 12);
                    $setClass = $v->css_class ? ' ' . $v->css_class . ' ' : '';
                    $v->active_menu_item_id = $active_menu_item_id;
                    $v->is_main_frame = $is_main_frame;
                    $v->block_level = $level;
                    //foreach (@$children[$id] as $key=> $v) {
                    if ($v->type == 'row') {
                        $html .= websiteHelperFrontEnd::getHeaderHtml($v, $enableEditWebsite, $prevV);
                    } elseif ($v->type == 'column') {

                        $offset = $v->gs_x;
                        if ($prevV) {
                            $offset = $v->gs_x - ($prevV->gs_x + $prevV->width);
                        }
                        $html .= websiteHelperFrontEnd::getHeaderHtml($v, $enableEditWebsite, $prevV);
                        $html .= '
                        <div class="grid-stack-item   show-grid-stack-item block-item grid-stack-item_' . $v->parent_id . '" data-ordering="' . $v->ordering . ' ' . $css_class . '" data-block-parent-id="' . $v->parent_id . '" data-position="' . $v->position . '"  data-bootstrap-type="' . $v->type . '" data-screensize="' . $v->screensize . '"  data-block-id="' . $v->id . '" data-gs-x="' . $v->gs_x . '" data-gs-y="' . $v->gs_y . '" data-gs-width="' . $v->width . '" data-gs-height="' . $v->height . '" element-type="' . $v->type . '">
                            <div class="grid-stack-item-content edit-style allow-edit-style" data-block-parent-id="' . $v->parent_id . '" data-block-id="' . $v->id . '">
                                <div class="item-row" data-block-parent-id="' . $v->parent_id . '" data-block-id="' . $v->id . '">col(<span class="offset-width">o:' . $offset . '-w:' . $v->width . '</span>)</div>
                                <span class="drag label label-default move-column " data-block-parent-id="' . $v->parent_id . '" data-block-id="' . $v->id . '"><i class="glyphicon glyphicon-move "></i></span>
                                <a class="remove label label-danger remove-column" data-block-parent-id="' . $v->parent_id . '" data-block-id="' . $v->id . '" href="javascript:void(0)"><i class="glyphicon-remove glyphicon"></i></a>
                                <a class="add label label-danger add-row" data-block-parent-id="' . $v->parent_id . '" data-block-id="' . $v->id . '" href="javascript:void(0)"><i class="glyphicon glyphicon-plus"></i></a>
                                <a href="javascript:void(0)" class="menu label label-danger menu-list config-block" data-block-parent-id="' . $v->parent_id . '" data-block-id="' . $v->id . '"><i class="im-menu2"></i></a>
                                <div id="position_content_' . $v->id . '" data-block-parent-id="' . $v->parent_id . '" data-axis="' . $axis . '" data-block-id="' . $v->id . '" class="position-content' . $setClass . ' block-item block-item-column' . ($v->position == ' position-component ' ? ' position-component ' : '') . ' ' . ($enableEditWebsite ? ' control-element ' : '') . ' "  element-type="' . $v->type . '">';
                        if ($v->position == 'position-component') {
                            $html .= '<div class="error"><jdoc:include type="message" /></div><jdoc:include type="component"/>';
                        } else {
                            $html .= '<jdoc:include type="modules" name="position-' . $v->id . '"/>';

                        }

                    } else {
                        $html .= websiteHelperFrontEnd::getHeaderHtml($v, $enableEditWebsite, $prevV);
                    }


                    websiteHelperFrontEnd::treeRecurse($id, $html, $children, $maxLevel, $level + 1, $enableEditWebsite, $active_menu_item_id, $is_main_frame);
                    if ($v->type == 'row') {
                        $html .= '
                                </div>
                                    </div>
                                    ';
                    } elseif ($v->type == 'column') {
                        $html .= '</div>
                                    </div>
                                        </div>
                                            ';
                    } else {
                        $html .= websiteHelperFrontEnd::getFooterHtml($v, $enableEditWebsite, $prevV);
                    }


                }

            }
            return $html;
        } else {
            if (@$children[$id] && $level <= $maxLevel) {
                $items = @$children[$id];
                for ($j = 0; $j < count($items); $j++) {
                    $v = $items[$j];
                    $css_class = $v->css_class;
                    $css_class = explode(',', $css_class);
                    $css_class = implode(' ', $css_class);

                    $prevV = $items[$j - 1];
                    $offset = $v->gs_x;

                    $params = new JRegistry;
                    $params->loadString($v->params);
                    $float = $params->get('float', 'none');
                    $doc->addStyleDeclaration('
                    .div[data-block-id="' . $v->id . '"]
                    {
                       float:' . $float . '
                    }
                    ');

                    $offset = $v->gs_x - ($prevV->gs_x + $prevV->width);
                    $classRow = 'row row-bootstrap form-group';
                    $classColumn = array();
                    //$bootstrapColumnType='col-md-';
                    $bootstrapColumnType = $v->bootstrap_column_type;
                    $classColumn[] = $bootstrapColumnType . $v->width;
                    $classColumn[] = $bootstrapColumnType . 'offset-' . $offset;
                    $classColumn = ' ' . implode(' ', $classColumn);
                    $setClass = $v->css_class ? ' ' . $v->css_class . ' ' : '';
                    if ($v->type == 'row' || $v->type == 'column') {
                        //$html .= websiteHelperFrontEnd::getHeaderHtml($v, $enableEditWebsite, $prevV);
                        $html .= '<div data-screensize="' . $v->screensize . '" class=" block-item block-item-' . $v->type . ' ' . ($v->type == 'row' ? $classRow : $classColumn) . $setClass . ' ' . $css_class . '" data-block-id="' . $v->id . '" data-block-parent-id="' . $v->parent_id . '" data-column-type="' . $bootstrapColumnType . '" data-block-type="' . ($v->position == 'position-component' ? 'block-component' : '') . '" >';
                        if ($v->position == 'position-component') {
                            $html .= $v->type == 'column' ? '<div class="error"><jdoc:include type="message" /></div><jdoc:include type="component"/>' : '';
                        } else {
                            $html .= $v->type == 'column' ? '<jdoc:include type="modules" name="position-' . $v->id . '"/>' : '';
                        }
                    } else {
                        $html .= websiteHelperFrontEnd::getHeaderHtml($v, $enableEditWebsite, $prevV);

                    }
                    $id = $v->id;
                    websiteHelperFrontEnd::treeRecurse($id, $html, $children, $maxLevel, $level + 1, $enableEditWebsite);
                    if ($v->type == 'row' || $v->type == 'column') {
                        $html .= '</div>';
                    } else {
                        $html .= websiteHelperFrontEnd::getFooterHtml($v, $enableEditWebsite, $prevV);
                    }
                }
            }
            return $html;
        }
    }

    function  getHeaderHtml($block, $enableEditWebsite, $prevV = null)
    {
        require_once JPATH_ROOT . '/media/elements/ui/element.php';
        if ($block->type == 'row') {
            $block->ui_path = 'media/elements/ui/row.php';
        }
        if ($block->type == 'column') {
            $block->ui_path = 'media/elements/ui/column.php';
        }
        $path = JPATH_ROOT . '/' . $block->ui_path;
        jimport('joomla.filesystem.file');
        if (JFile::exists($path)) {
            require_once $path;
        }
        $path_parts = pathinfo($block->ui_path);
        $type = $path_parts['filename'];
        $classElementHelper = 'element' . $type . 'Helper';
        if (class_exists($classElementHelper)) {
            $classElementHelper = new $classElementHelper($block, $enableEditWebsite);

            $classElementHelper::initElement($block);
            return $classElementHelper::getHeaderHtml($block, $enableEditWebsite, $prevV);
        } else {
            return elementHelper::getHeaderHtml($block, $enableEditWebsite, $prevV);
        }

    }

    function  getFooterHtml($block, $enableEditWebsite, $prevV = null)
    {
        require_once JPATH_ROOT . '/media/elements/ui/element.php';
        if ($block->ui_path != '' && file_exists(JPATH_ROOT . '/' . $block->ui_path)) {
            require_once JPATH_ROOT . '/' . $block->ui_path;
            $path_parts = pathinfo($block->ui_path);
            $type = $path_parts['filename'];
            $classElementHelper = 'element' . $type . 'Helper';
            $classElementHelper = new $classElementHelper;
            return $classElementHelper::getFooterHtml($block, $enableEditWebsite, $prevV);
        } else {
            return elementHelper::getFooterHtml($block, $enableEditWebsite, $prevV);
        }

    }

    function treeNodeObjectToCss($object, &$css = '')
    {

        foreach ($object as $key => $item) {

            if (is_object($item)) {
                websiteHelperFrontEnd::treeNodeObjectToCss($item, $css);
            } else if ($item != '') {
                $key = str_replace('_', '-', $key);
                $css .= "$key:$item !important;\n";
            }
        }

    }

    public function getBlocksCss($listPositionsSetting = array())
    {

        $listPositionsSetting = count($listPositionsSetting) ? $listPositionsSetting : UtilityHelper::getListPositionsSetting();
        $cssBlocks = '';
        require_once JPATH_ROOT . '/components/com_utility/helper/utility.php';
        foreach ($listPositionsSetting as $position) {
            $params = new JRegistry;
            $params->loadString($position->params);

            $style = $params->get('style.style_element', '');
            if (!is_object($style) && trim($style) != '') {
                $style = UtilityHelper::get_build_css($style);

            }
            websiteHelperFrontEnd::treeNodeObjectToCss($style, $css);
            $css = $css ? ".main-container .block-item[data-block-id=\"$position->id\"][data-block-parent-id=\"$position->parent_id\"]{\n$css\n}\n" : '';
            $cssBlocks .= $css;
            $css3_gradient_generator = $params->get('element.css3_gradient_generator', '');
            $str_class = ".main-container .block-item[data-block-id=\"$position->id\"][data-block-parent-id=\"$position->parent_id\"]";
            $css3_gradient_generator = str_replace('.gradient', $str_class, $css3_gradient_generator);
            $cssBlocks .= "\n" . $css3_gradient_generator . "\n";
            $style_controller = $params->get('style.style_controler', '');
            $css_controller = '';
            websiteHelperFrontEnd::treeNodeObjectToCss($style_controller, $css_controller);
            switch ($position->type) {
                case 'row':
                    //code to be executed if n=label1;
                    break;
                case 'column':
                    $css_controller = $css_controller ? ".position-content[data-block-id=\"$position->id\"][data-block-parent-id=\"$position->parent_id\"]{\n$css_controller\n}\n" : '';
                    break;
                default:
                    $css_controller = $css_controller ? ".control-element[data-block-id=\"$position->id\"][data-block-parent-id=\"$position->parent_id\"]{\n$css_controller\n}\n" : '';
            }


            $cssBlocks .= $css_controller;

        }
        return $cssBlocks;
    }

    public function getModulesCss($listModule = array())
    {

        $cssModules = '';
        foreach ($listModule as $module) {
            $params = new JRegistry;
            $params->loadString($module->params);
            $style = $params->get('less_style', '');
            $css = '';
            websiteHelperFrontEnd::treeNodeObjectToCss($style, $css);
            $css = $css ? ".block-item[data-module-id=\"$module->id\"][element-type=\"module\"]{\n$css\n}\n" : '';
            $cssModules .= $css;
        }
        return $cssModules;
    }

    static function displayLayout($this_layout, $enableEditWebsite = 0)
    {


        $app = JFactory::getApplication();
        if ($enableEditWebsite) {
            $currentScreenSize = UtilityHelper::getCurrentScreenSizeEditing();
        } else {
            $currentScreenSize = UtilityHelper::getScreenSize();
        }
        $currentScreenSize = UtilityHelper::getSelectScreenSize($currentScreenSize);
        //$listPositionsSetting=UtilityHelper::getListPositionsSetting($currentScreenSize);
        $menu = $app->getMenu();

        $menuItemActive = $menu->getActive();
        if (!$menuItemActive) {
            $menuItemActive = $menu->getDefault('*');
        }

        $website = JFactory::getWebsite();
        $params = $menuItemActive->params;
        if (!$params) {

            throw new Exception(JText::_('the are no active menu '), 404);

        }
        $use_main_frame = $params->get('use_main_frame', 0);
        $listPositionsSetting = array();
        require JPATH_ROOT . '/libraries/cms/version/version.php';
        $version = new JVersion;
        $os_version = $version->get_os_version();

        $os = $app->input->get('os', '', 'String');
        $os_software_version = $app->input->get('version', '', 'String');
        if ($os != '' && $os_software_version == $os_version) {
            ob_get_clean();
            $return_children = array(
                'version' => $os_version,
            );
            ob_clean();
            header('Content-Type: application/json');
            //echo json_encode($return);
            echo json_encode($return_children, JSON_NUMERIC_CHECK);
            die;

        }

        $listPositionsSetting = UtilityHelper::getPositionByPage($enableEditWebsite);

        //$listPositionsSetting = UtilityHelper::getListPositionsSetting('',$use_main_frame);
        $cssBlocks = websiteHelperFrontEnd::getBlocksCss($listPositionsSetting);
        $modules =& JModuleHelper::_load();
        $cssModules = websiteHelperFrontEnd::getModulesCss($modules);
        $styles = $cssBlocks . "\n" . $cssModules;

        $data_source = array();
        $children = array();
        if (!empty($listPositionsSetting)) {

            $children = array();

            // First pass - collect children
            foreach ($listPositionsSetting as $v) {
                $pt = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                if ($os == 'android') {
                    require_once JPATH_ROOT . '/media/elements/ui/element.php';
                    $file_helper = JPATH_ROOT . "/" . $v->ui_path;
                    if ($file_helper != JPATH_ROOT . "/" && file_exists($file_helper)) {
                        require_once $file_helper;
                    }

                    $class_element = "element" . $v->type . "Helper";
                    if (class_exists($class_element) && method_exists($class_element, "android_set_data_source")) {
                        $params = new JRegistry;
                        $params->loadString($v->params);
                        $class_element::android_set_data_source($v, $params, $data_source);
                    }
                }
                array_push($list, $v);
                $children[$pt] = $list;
            }

        }

        $website = JFactory::getWebsite();
        JTable::addIncludePath(JPATH_ROOT . '/components/com_utility/tables/');
        $tablePosition = JTable::getInstance('positionnested');
        $tablePosition->screenSize = $currentScreenSize;
        $tablePosition->website_id = $website->website_id;
        $menu = JMenu::getInstance('site');
        $menuItemActiveId = $menu->getActive()->id;


        $rootId = $tablePosition->getRootId();
        if ($os == 'android') {

            $modules = JModuleHelper::load();
            foreach ($modules as $key => $module) {
                $params = new JRegistry;
                $params->loadString($module->params);
                $module = $module->module;
                $file_helper = JPATH_ROOT . "/modules/$module/helper.php";
                if (file_exists($file_helper)) {
                    require_once $file_helper;
                }
                $module = str_replace("_", "", $module);
                $class_module = $module . "Helper";
                if (class_exists($class_module)) {
                    $class_module::android_set_data_source($modules[$key], $params, $data_source);
                }
            }
            ob_get_clean();
            $return_children = array(
                'modules' => (array)$modules,
                'root_id' => $rootId,
                'version' => $os_version,
                'children' => $children
            );
            ob_clean();
            header('Content-Type: application/json');
            //echo json_encode($return);
            echo json_encode($return_children, JSON_NUMERIC_CHECK);
            die;
        }
        $cache = JFactory::getCache('_system', 'callback');
        /*$cache_id=md5($website->webite_id.$menuItemActive->id.$rootId);
        $html=$cache->cache->get($cache_id);
        if(!$html)
        {

            $cache->store($html,$cache_id);
        }*/
        $html = '';
        websiteHelperFrontEnd::treeRecurse($rootId, $html, $children, 999, 0, $enableEditWebsite, $os);
        /*echo htmlspecialchars($html);
        die;*/
        $doc = JFactory::getDocument();
        $fileStyle = "style_{$website->website_id}_{$menuItemActiveId}.css";
        JFile::write(JPATH_ROOT . '/layouts/website/css/' . $fileStyle, $styles);
        $doc->addStyleSheet(JUri::root() . '/layouts/website/css/' . $fileStyle);

        if ($enableEditWebsite) {
            $html = '<div class="container-website" data-bootstrap-class="container container_' . ($use_main_frame ? $use_main_frame : $menuItemActiveId) . '">
                    <div class="main-container" data-block-id="' . $rootId . '">
                        <a href="javascript:void(0)"  class="add label label-danger add-row"><i class="glyphicon glyphicon-plus"></i></a>
                        <a href="javascript:void(0)" class="menu label label-danger menu-list config-block"><i class="im-menu2"></i></a>
                        ' . $html . '
                    </div>
                </div>';
        } else {
            $html = '<div class="container container_' . ($use_main_frame ? $use_main_frame : $menuItemActiveId) . '">
                        <div class="main-container">
                        ' . $html . '
                        </div>
                    </div>';
        }
        return $html;

        ob_start();
        ?>
        <?php if (!$enableEditWebsite) { ?>
        <div class="edit_website">
            <i class="btn glyphicon glyphicon-cog"></i>
        </div>
    <?php } ?>
        <div data-bootstrap-class="container">
            <div class="main-container">
                <div class="row-content">
                    <div class="item-row bootstrap-row">row</div>
                    <span class="drag label label-default"><i class="glyphicon glyphicon-move"></i> drag</span>
                    <a class="add label label-danger add-column-in-row" href="javascript:void(0)">
                        <i class="glyphicon glyphicon-plus"></i>
                        add column
                    </a>

                    <div class="grid-stack"
                         style="<?php echo websiteHelperFrontEnd::renderStyle($this_layout->styles['body']); ?>">

                        <?php
                        $debugScreen = $this_layout->debugScreen;
                        foreach ($listPositionsSetting as $positionItem) {
                            $position = $positionItem->position;
                            $screensize = $positionItem->screensize;
                            $id = $positionItem->id;
                            $gs_x = $positionItem->gs_x;
                            $gs_y = $positionItem->gs_y;
                            $width = $positionItem->width;
                            $height = $positionItem->height;
                            include JPATH_ROOT . '/components/com_utility/views/module/tmpl/default.php';


                        }

                        ?>

                    </div>
                </div>
            </div>
        </div>

        <?php
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public static function getOneTemplateWebsite()
    {
        return 128;
    }

    public function compileLess($input, $output)
    {
        $cssTemplate = basename($output);

        if (strtolower($cssTemplate) == 'template.css') {
            return;
        }
        $app = JFactory::getApplication();
        if (!defined('FOF_INCLUDED')) {
            require_once JPATH_ROOT . '/libraries/f0f/include.php';
        }
        require_once JPATH_ROOT . '/libraries/f0f/less/less.php';
        $less = new F0FLess;
        $less->setFormatter(new F0FLessFormatterJoomla);

        try {
            $less->compileFile($input, $output);

            return true;
        } catch (Exception $e) {
            $app->enqueueMessage($e->getMessage(), 'error');
        }

    }

    public static function getWebsites()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__website');
        $query->select('id,title');
        $db->setQuery($query);
        $listWebsite = $db->loadObjectList();
        $query = $db->getQuery(true);
        $query->from('#__domain_website');
        $query->select('id,domain,website_id');
        $db->setQuery($query);
        $listWebsiteDomain = $db->loadObjectList();
        foreach ($listWebsiteDomain as $domainWebsite) {
            foreach ($listWebsite as $key => $website) {
                if ($website->id == $domainWebsite->website_id) {
                    $listWebsite[$key]->listSite[] = $domainWebsite->domain;
                }
            }
        }
        foreach ($listWebsite as $key => $website) {
            $listWebsite[$key]->title .= '(' . implode(',', $website->listSite) . ')';
        }
        return $listWebsite;
    }

    function getOptionListWebsite($task = 'quick_assign_website')
    {
        $listWebsite= websiteHelperFrontEnd::getWebsites();
        $option=array();
        $option[] = JHTML::_('select.option', '',  "-- ".JText::_("SELECT_WEBSITR_ASSIGN")." --");
        $option[] = JHTML::_('select.option', '-1',  JText::_("Run for all"));
        $option[] = JHTML::_('select.option', '0',  JText::_("None"));
        foreach($listWebsite as $website)
        {
            $option[] = JHTML::_('select.option',$website->id,  $website->title);

        }
        ob_start();
        ?>
        <div class="form-group">
            <label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
            <div class="input-group">
                <div class="input-group-addon">$</div>
                <input type="text" class="form-control" id="exampleInputAmount" placeholder="Amount">
                <div class="input-group-addon">.00</div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Transfer cash</button>

        <?php
        $html=ob_get_clean();
        return $html;
        $js=<<<javascript
			if (document.adminForm.boxchecked.value==0){
				alert('Please first make a selection from the list');
			}else if (document.adminForm.website_id.value==''){
				alert('Please first make a selection from website from list website');
			}else{
				 Joomla.submitbutton('{$task}')
			}
javascript;
        $change=Jtext::_('Change');
        $option= JHTML::_('select.genericlist', $option,  'website_id',  'class = "btn btn-default inputbox" size = "1"',  'value',  'text' );
        $html=<<<HTML
		<div class="btn-group group-assign-website">
		  <div  class="btn btn-default checkbox">
			<label>
			  <input name="copy" value="1" type="checkbox"> Copy and
			</label>
		  </div>
		  {$option}
		  <button type="button" onclick="{$js}" name="change" class="btn btn-default">{$change}</button>
		</div>
HTML;
        $style=<<<style
		<style type="text/css">
		 .group-assign-website .active.btn-success
		 {
			background: none;
			color:#000;
			text-shadow:white !important;
		 }
		 .group-assign-website .checkbox label
		 {
			margin-bottom: 3px;
			margin-top: 2px;
			text-shadow: inherit;
		 }
		 .group-assign-website .checkbox input[type="checkbox"]
		{
			margin-left: 0;
			margin-right: 4px;
			margin-top: 2px;
		}
		.group-assign-website #website_id_chzn a.chzn-single
		{
			border-left: 0 none;
			border-radius: 0;
			border-right: 0 none;
			padding: 4px;
		}
		.group-assign-website button[name="change"]
		{
			padding: 6px 8px 7px 3px;
		}
</style>
style;

        $html.=$style;
        return $html;
    }

    function setKeyWebsite($items)
    {
        $listWebsite = websiteHelperFrontEnd::getWebsites();

        $listWebsite = JArrayHelper::pivot($listWebsite, 'id');

        foreach ($items as $key => $item) {

            if ($items[$key]->website_id == -1) {
                $items[$key]->website = 'All';
            } elseif ($items[$key]->website_id == 0) {
                $items[$key]->website = 'None';
            } else {
                $items[$key]->website = $listWebsite[$item->website_id]->title;
            }
        }
        return $items;
    }

    function renderStyle($listStyle)
    {
        $txt = '';
        foreach ($listStyle as $key => $item) {
            $txt .= "$key:$item;";
        }
        return $txt;
    }

    public static function getSupperAdminWebsite()
    {
        $domainSupper = array(
            'supper.hoteclick.com'
        , 'supper.websitetemplatepro.com'
        , 'supper.shoponline123.net'
        , 'supper.asianventure.com'
        );
        return $domainSupper;

    }

}
