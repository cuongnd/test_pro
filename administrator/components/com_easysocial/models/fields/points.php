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

// Include foundry
require_once( JPATH_ROOT . '/administrator/components/com_easysocial/includes/foundry.php' );

class JFormFieldEasySocial_Points extends JFormField
{
	protected $type 	= 'EasySocial_Points';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 * @since   1.6
	 */
	protected function getInput()
	{
		// Load the language file.
		Foundry::language()->load( 'com_easysocial' , JPATH_ADMINISTRATOR );

		// Render the headers
		Foundry::page()->start();

		// Attach dialog's css file.
		JFactory::getDocument()->addStylesheet( rtrim( JURI::root() , '/' ) . '/administrator/components/com_easysocial/themes/default/styles/dialog.css' );
		
		$theme 	= Foundry::themes();

		$label 	= (string) $this->element[ 'label' ];
		$name 	= (string) $this->name;
		$title 	= JText::_( 'COM_EASYSOCIAL_JFIELD_SELECT_POINTS' );

		if( $this->value )
		{
			$points 	= Foundry::table( 'Points' );
			$points->load( $this->value );
			$title 	= $points->get( 'title' );
		}

		$theme->set( 'name'		, $name );
		$theme->set( 'id'		, $this->id );
		$theme->set( 'value'	, $this->value );
		$theme->set( 'label'	, $label );
		$theme->set( 'title'	, $title );
		$output	= $theme->output( 'admin/jfields/points' );

		// We do not want to process stylesheets on Joomla 2.5 and below.
		$options	= array();

		if( Foundry::version()->getVersion() < 3 )
		{
			$options[ 'processStylesheets' ]	= false;
		}

		Foundry::page()->end( $options );

		return $output;
	}
}
