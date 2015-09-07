<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Include main views file.
Foundry::import( 'admin:/views/views' );

class EasySocialViewThemes extends EasySocialAdminView
{
	/**
	 * Displays a list of themes on the site.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function display( $tpl = null )
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.themes' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		// Set page heading
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_THEMES' ) );

		// Set page icon.
		$this->setIcon( 'icon-jar jar-image_woodenframe' );

		// Set page description
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_THEMES' ) );

		JToolbarHelper::custom( 'makeDefault' , 'default' , '' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_MAKE_DEFAULT' ) , false );

		// Load themes model
		$model 	= Foundry::model( 'Themes' );

		$themes	= $model->getThemes();

		$this->set( 'themes'	, $themes );

		parent::display( 'admin/themes/default' );
	}

	/**
	 * Displays the theme's form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function form()
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.themes' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		$element 	= JRequest::getVar( 'element' );

		if( !$element )
		{
			$this->redirect( 'index.php?option=com_easysocial&view=themes' );
			$this->close();
		}

		JToolbarHelper::apply( 'apply' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SAVE' ) , false , false );
		JToolbarHelper::save( 'save' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_SAVE_AND_CLOSE' ) );

		$model 		= Foundry::model( 'Themes' );
		$theme		= $model->getTheme( $element );

		// Set the page heading
		$this->setHeading( $theme->name );
		$this->setIcon( 'icon-jar jar-image_woodenframe' );
		$this->setDescription( $theme->desc );

		$this->set( 'theme' , $theme );

		parent::display( 'admin/themes/form' );
	}

	/**
	 * Displays the theme's installation page
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function install()
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.themes' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		JToolbarHelper::cancel( 'cancel' , JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_BUTTON_CANCEL' ) );

		// Set the page heading
		$this->setHeading( JText::_( 'COM_EASYSOCIAL_TOOLBAR_TITLE_THEMES_INSTALL' ) );
		$this->setIcon( 'icon-jar jar-image_woodenframe' );
		$this->setDescription( JText::_( 'COM_EASYSOCIAL_DESCRIPTION_THEMES_INSTALL' ) );

		parent::display( 'admin/themes/install' );
	}

	/**
	 * Post process after an installation is completed
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function installCompleted()
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.themes' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		Foundry::info()->set( $this->getMessage() );
		$this->redirect( 'index.php?option=com_easysocial&view=themes' );
		$this->close();
	}

	/**
	 * Make a theme as a default theme
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function makeDefault()
	{
		Foundry::info()->set( $this->getMessage() );

		$this->redirect( 'index.php?option=com_easysocial&view=themes' );
	}

	/**
	 * Post processing after a theme is stored
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function store( $task , $element = null )
	{
		// Disallow access
		if( !$this->authorise( 'easysocial.access.themes' ) )
		{
			$this->redirect( 'index.php' , JText::_( 'JERROR_ALERTNOAUTHOR' ) , 'error' );
		}

		$url 	= 'index.php?option=com_easysocial&view=themes';
		$active = JRequest::getVar( 'activeTab' );

		if( $active )
		{
			$active	= '&activeTab=' . $active;
		}

		if( $element && ($task == 'apply' && $task != 'save' ) )
		{
			$url 	= 'index.php?option=com_easysocial&view=themes&layout=form&element=' . $element . $active;
		}

		Foundry::info()->set( $this->getMessage() );

		$this->redirect( $url );
		$this->close();
	}
}
