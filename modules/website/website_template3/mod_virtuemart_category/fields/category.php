<?php

/**
 *
 * @package	VirtueMart
 * @subpackage   Models Fields
 * @author Valérie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2011 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: $
 */

/**
 * Supports a modal product picker.
 *
 *
 */
class JFormFieldCategory extends JFormField
{
	protected $type = 'category';

	/**
	 * Method to get the field input markup.
	 *
         * @author      Valerie Cartan Isaksen
	 * @return	string	The field input markup.
	 * @since	1.6
	 */

     function getInput() {

        $key = ($this->element['key_field'] ? $this->element['key_field'] : 'value');
        $val = ($this->element['value_field'] ? $this->element['value_field'] : $this->name);
		$category_id_select=$this->value;
		$vendorId = '1';

		if (!class_exists( 'VmcConfig' )) require(JPATH_SITE . DS . 'components' . DS . 'com_virtuemart_client'.DS.'helpers'.DS.'config.php');
		return;
		$categoryModel = VmcModel::getModel('Category');
		print_r($categoryModel);
		$categorylist =$categoryModel->getChildCategoryList($vendorId);
        $class = '';
        $html = '<select class="inputbox"   name="' . $this->name . '" >';
		$app=JFactory::getApplication();
		$siteselect = $app->getUserStateFromRequest('siteselect', 'filter_siteselect', '');
		//$sites=JHtml::_('site.sites');
		//$siteselect=$siteselect?$siteselect:$sites[0]->value;

		if($siteselect!=0)
		{
			$html.='<option value="0">'.JText::_('tất cả').'</option>';
		}

        $html .=JHtml::_('select.options', $categorylist, 'id', 'treename',$category_id_select);
        $html .="</select>";
        return $html;


    }



}