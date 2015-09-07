<?php
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
class mod_virtuemartSearchHelper
{
    static function treeReCurseCategories($virtuemart_category_id, &$html, $treeCategory,$category_selected=0, $maxlevel = 9999, $level = 0,$live1_attr='')
    {
        if ($treeCategory[$virtuemart_category_id] && $level <= $maxlevel) {

            foreach ($treeCategory[$virtuemart_category_id] as $v) {

                $virtuemart_category_id = $v->virtuemart_category_id;
                if(count($treeCategory[$virtuemart_category_id]))
                {
                    $html .= '<optgroup label="'.str_repeat (' - ', ($level - 1)).$v->category_name.'">' ;
                }else
                {
                    $html .= '<option '.($category_selected==$virtuemart_category_id?' selected ':'').' value="'.$virtuemart_category_id.'">' . $v->category_name ;
                }
                mod_virtuemartSearchHelper::treeReCurseCategories($virtuemart_category_id, $html, $treeCategory,$category_selected, $maxlevel, $level + 1);
                if(count($treeCategory[$virtuemart_category_id]))
                {
                    $html .= '</optgroup>' ;
                }else
                {
                    $html .='</option>';
                }

            }
        }

        return $html ;

    }
}

?>
