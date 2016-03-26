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

class websiteHelperBackend
{
	public static $extension = 'com_website';
	public static function getSupperAdminWebsite()
	{
		$domainSupper=array(
			'supper.hoteclick.com'
		,'supper.websitetemplatepro.com'
		,'supper.shoponline123.net'
		,'supper.asianventure.com'
		,'admin.asianventure.com'
		);
		return $domainSupper;

	}
	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('JGLOBAL_websiteS'),
			'index.php?option=com_website&view=websites',
			$vName == 'websites'
		);
		JHtmlSidebar::addEntry(
			JText::_('com_website_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_website',
			$vName == 'categories');
		JHtmlSidebar::addEntry(
			JText::_('com_website_SUBMENU_FEATURED'),
			'index.php?option=com_website&view=featured',
			$vName == 'featured'
		);
	}
	public function getOneTemplateWebsite()
	{
		return 38;
	}
	/**
	 * Applies the website tag filters to arbitrary text as per settings for current user group
	 *
	 * @param   text  $text  The string to filter
	 *
	 * @return  string  The filtered string
	 *
	 * @deprecated  4.0  Use JComponentHelper::filterText() instead.
	*/
	public static function filterText($text)
	{
		JLog::add('websiteHelperFrontEnd::filterText() is deprecated. Use JComponentHelper::filterText() instead.', JLog::WARNING, 'deprecated');

		return JComponentHelper::filterText($text);
	}

	public static function getWebsites()
	{
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->from('#__website');
		$query->select('id,title');
		$db->setQuery($query);
		$listWebsite=$db->loadObjectList();
		$query=$db->getQuery(true);
		$query->from('#__domain_website');
		$query->select('id,domain,website_id');
		$db->setQuery($query);
		$listWebsiteDomain=$db->loadObjectList();
		foreach($listWebsiteDomain as $domainWebsite)
		{
			foreach($listWebsite as $key=> $website)
			{
				if($website->id==$domainWebsite->website_id)
				{
					$listWebsite[$key]->listSite[]=$domainWebsite->domain;
				}
			}
		}
		foreach($listWebsite as $key=>$website)
		{
			$listWebsite[$key]->title.='('.implode(',',$website->listSite).')';
		}
		return $listWebsite;
	}
	function getForAllWebsiteType()
	{
		$listWebsite=array();
		$other=array(
			'id'=>-1
			,'parent_id'=>0
			,'title'=>JText::_("Run for all")
		);
		$listWebsite[]=(object)$other;
		$other=array(
			'id'=>0
			,'parent_id'=>0
			,'title'=>JText::_("None")
		);
		$listWebsite[]=(object)$other;
		require_once JPATH_ROOT.'/components/com_website/helpers/website.php';
		$listWebsite1= websiteHelperFrontEnd::getWebsites();
		foreach($listWebsite1 as $website)
		{
			$website->parent_id=0;
			$listWebsite[]=$website;

		}
		return $listWebsite;
	}
	function getOptionListWebsite($task='quick_assign_website')
	{

	}
	function getGenericlistWebsite($name='website_id',$attribute='',$selected)
	{
		require_once JPATH_ROOT.'/components/com_website/helpers/website.php';
		$listWebsite= websiteHelperFrontEnd::getWebsites();
		$option=array();
		$option[] = JHTML::_('select.option', '-1',  JText::_("Run for all"));
		$option[] = JHTML::_('select.option', '0',  JText::_("None"));
		foreach($listWebsite as $website)
		{
			$option[] = JHTML::_('select.option',$website->id,  $website->title);

		}
		$select= JHTML::_('select.genericlist', $option,  $name,  'class = "btn btn-default inputbox" size = "1" '.$attribute,  'value',  'text',$selected );

		return $select;
	}
	function setKeyWebsite($items)
	{
		require_once JPATH_ROOT.'/components/com_website/helpers/website.php';
		$listWebsite= websiteHelperFrontEnd::getWebsites();

		$listWebsite=JArrayHelper::pivot($listWebsite,'id');

		foreach($items as $key=>$item)
		{

			if($items[$key]->website_id==-1)
			{
				$items[$key]->website='All';
			}elseif($items[$key]->website_id==0)
			{
				$items[$key]->website='None';
			}else
			{
				$items[$key]->website=$listWebsite[$item->website_id]->title;
			}
		}
		return $items;
	}

}
