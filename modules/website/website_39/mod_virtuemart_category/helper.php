<?php
defined('_JEXEC') or die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
/*
* Module Helper
*
* @package VirtueMart
* @copyright (C) 2010 - Patrick Kohl
* @ Email: cyber__fr|at|hotmail.com
*
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* VirtueMart is Free Software.
* VirtueMart comes with absolute no warranty.
*
* www.virtuemart.net
*/

if (!class_exists('VmConfig')) require(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');
$config = VmConfig::loadConfig();
if (!class_exists('VirtueMartModelVendor')) require(JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'vendor.php');

//if (!class_exists( 'VmImage' )) require(JPATH_VM_ADMINISTRATOR.DS.'helpers'.DS.'image.php');
//if (!class_exists( 'shopFunctionsF' )) require(JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'helpers'.DS.'shopfunctionsf.php');
if (!class_exists('TableMedias')) require(JPATH_VM_ADMINISTRATOR . DS . 'tables' . DS . 'medias.php');

if (!class_exists('TableCategories')) require(JPATH_VM_ADMINISTRATOR . DS . 'tables' . DS . 'categories.php');

if (!class_exists('VirtueMartModelCategory')) require(JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'category.php');

class mod_virtuemartCategoryHelper
{
    static function treeReCurseCategories($virtuemart_category_id, &$html, $treeCategory, $maxlevel = 9999, $level = 0, $live1_attr = '')
    {

        if ($treeCategory[$virtuemart_category_id] && $level <= $maxlevel) {

            $html .= '<ul' . ($level == 0 ? $live1_attr : '') . '>';
            foreach ($treeCategory[$virtuemart_category_id] as $v) {

                $virtuemart_category_id = $v->virtuemart_category_id;
                $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $v->virtuemart_category_id);
                $html .= '<li><a class="e-change-lang" title="' . $v->category_name . '" href="' . $caturl . '">' . $v->category_name . '</a>';
                mod_virtuemartCategoryHelper::treeReCurseCategories($virtuemart_category_id, $html, $treeCategory, $maxlevel, $level + 1);
                $html .= '</li>';
            }
            $html .= '</ul>';
        }

        return $html;

    }

    public static function get_item($params, $html)
    {
        $return = new stdClass();
        echo "<pre>";
        print_r($params);
        echo "</pre>";

        $return->category_title = $params->get('category_name', '');
        $return->sub_category = "sdfsdfsdfsdfsdfdsdf";
        foreach ($return as $key => $value) {
            $html = str_replace('{' . $key . '}', $value, $html);
        }
        return $html;
    }

    static function render_vertical_mega_menu($attributes_level0 = '', $list_category=array(), $virtuemart_category_id=0, $level = 0,$menu_item_id=0)
    {
        if ($level == 0) {
            $categoryModel = VmModel::getModel('category');


            $categoryModel->_noLimit = TRUE;
            // $app = JFactory::getApplication ();

            $list_category = $categoryModel->getCategories(false, $virtuemart_category_id);
        }
        $list_item1 = array();
        if ($virtuemart_category_id == 0) {
            foreach ($list_category as $key => $item) {
                if (!$item->category_parent_id) {
                    $list_item1[] = $item;
                    unset($list_category[$key]);
                }
            }
        } else {
            foreach ($list_category as $key => $item) {
                if ((int)$item->category_parent_id == (int)$virtuemart_category_id) {
                    $list_item1[] = $item;
                    unset($list_category[$key]);
                }
            }
        }
        usort($list_item1, function ($item1, $item2) {
            if ($item1->ordering == $item2->ordering) return 0;
            return $item1->ordering < $item2->ordering ? -1 : 1;
        });
        if (count($list_item1)) {
            ob_start();
            ?>
            <ul <?php echo $level == 0 ? $attributes_level0 : '' ?> >
                <?php foreach ($list_item1 as $category) { ?>
                    <li id="menu-item-<?php echo $category->virtuemart_category_id ?>">
                        <a href="index.php?option=com_virtuemart&view=category&virtuemart_category_id=<?php echo $category->virtuemart_category_id ?>&Itemid=<?php echo $menu_item_id ?>"><?php echo $category->category_name ?></a>
                        <?php
                        $level1 = $level + 1;
                        ?>
                        <?php echo mod_virtuemartCategoryHelper::render_vertical_mega_menu($attributes_level0, $list_category, $category->virtuemart_category_id, $level1,$menu_item_id) ?>
                    </li>

                <?php } ?>
            </ul>
            <?php
            $html = ob_get_clean();
            return $html;
        }
    }
}

?>
