<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Restricted access');

require( EBLOG_ADMIN_ROOT . DIRECTORY_SEPARATOR . 'views.php');

class EasyBlogViewUserGroups extends EasyBlogAdminView
{
	function display($tpl = null)
	{
		// @rule: Test for user access if on 1.6 and above
		if( EasyBlogHelper::getJoomlaVersion() >= '1.6' )
		{
			if(!JFactory::getUser()->authorise('easyblog.manage.user' , 'com_easyblog') )
			{
				JFactory::getApplication()->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
				JFactory::getApplication()->close();
			}
		}

		//initialise variables
		$document	= JFactory::getDocument();
		$user		= JFactory::getUser();
		$mainframe	= JFactory::getApplication();
		$groups		= EasyBlogHelper::getJoomlaUserGroups();

		$this->assign( 'groups'			, $groups );
		parent::display($tpl);
	}

	function getPostCount( $id )
	{
		$db	= EasyBlogHelper::db();

		$query	= 'SELECT COUNT(1) FROM #__easyblog_post '
				. 'WHERE `created_by`=' . $db->Quote( $id );
		$db->setQuery( $query );

		return $db->loadResult();
	}

	function registerToolbar()
	{
		JToolBarHelper::title( JText::_( 'COM_EASYBLOG_BLOGGERS_TITLE' ), 'users' );

		JToolBarHelper::back( JText::_( 'COM_EASYBLOG_HOME' ) , 'index.php?option=com_easyblog' );
		JToolBarHelper::divider();
		JToolbarHelper::addNew();
		JToolbarHelper::deleteList();
	}
}
