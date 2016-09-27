<?php
return;
defined('_JEXEC') or  die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');
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
    static function treeReCurseCategories($virtuemart_category_id, &$html, $treeCategory, $maxlevel = 9999, $level = 0,$live1_attr='')
    {

        if ($treeCategory[$virtuemart_category_id] && $level <= $maxlevel) {

            $html .= '<ul'.($level==0?$live1_attr:'').'>';
            foreach ($treeCategory[$virtuemart_category_id] as $v) {

                $virtuemart_category_id = $v->virtuemart_category_id;
                $caturl = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $v->virtuemart_category_id);
                $html .= '<li><a class="e-change-lang" title="'.$v->category_name.'" href="'.$caturl.'">' . $v->category_name.'</a>' ;
                mod_virtuemartCategoryHelper::treeReCurseCategories($virtuemart_category_id, $html, $treeCategory, $maxlevel, $level + 1);
                $html .='</li>';
            }
            $html .= '</ul>';
        }

        return $html ;

    }

    public static function get_item($params,$html)
    {
        $return=new stdClass();
        echo "<pre>";
        print_r($params);
        echo "</pre>";

        $return->category_title=$params->get('category_name','');
        $return->sub_category="sdfsdfsdfsdfsdfdsdf";
        foreach($return as $key=>$value)
        {
            $html=str_replace('{'.$key.'}',$value,$html);
        }
        return $html;
    }
}

?>
