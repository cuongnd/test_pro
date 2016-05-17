<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of listmywebsite.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 * @since       1.5
 */
class websitetemplateproViewlisttemplatecategory extends JViewLegacy
{

	/**
	 * Display the view
	 */
    public $category_id=0;
    public $page_selected=1;
	public function display($tpl = null)
	{
        require_once JPATH_ROOT.'/components/website/website_websitetemplatepro/com_websitetemplatepro/helpers/listwebsitetemplate.php';
        $this->list_template_website=listwebsitetemplateHelper::get_list_template_website_by_category_id_include_children($this->category_id);
		parent::display($tpl);
	}
}
