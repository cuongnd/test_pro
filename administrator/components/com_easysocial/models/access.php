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

jimport('joomla.application.component.model');

Foundry::import( 'admin:/includes/model' );

/**
 * Access Control model.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class EasySocialModelAccess extends EasySocialModel
{
	private $data			= null;

	/**
	 * Class constructor
	 *
	 * @since	1.0
	 * @access	public
	 */
	function __construct()
	{
		parent::__construct( 'access' );
	}
	
	public function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if ( empty( $this->pagination ) )
		{
			jimport('joomla.html.pagination');
			$this->pagination = new JPagination( $this->total , $this->getState('limitstart') , $this->getState('limit') );
		}

		return $this->pagination;
	}

	/**
	 * Given the access uid and type, load the params
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The access uid
	 * @param	string	The access type
	 * @return	string	The raw params
	 */
	public function getParams( $uid , $type )
	{
		$db 	= Foundry::db();
		$sql	= $db->sql();

		$sql->select( '#__social_access' );
		$sql->column( 'params' );
		$sql->where( 'uid' , $uid );
		$sql->where( 'type' , $type );

		$db->setQuery( $sql );
		$params	= $db->loadResult();

		return $params;
	}

	/**
	 * Renders the access form
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getForm( $uid , $type = SOCIAL_TYPE_USERGROUP , $prefix = '' , $activeTab = '' )
	{
		// The default path for the raw configuration.
		$folder 	= SOCIAL_ADMIN_DEFAULTS . '/access';
		$defaults 	= SOCIAL_ADMIN_DEFAULTS . '/access.json';

		// Get a list of files from the folder.
		jimport( 'joomla.filesystem.folder' );
		$files 		= JFolder::files( $folder , '.' , true , true );

		if( !$files )
		{
			$this->setError( JText::_( 'COM_EASYSOCIAL_ACCESS_NO_ACCESS_DEFINITIONS' ) );
			return false;
		}

		$forms 	= array();

		foreach( $files as $file )
		{
			$forms[]	= Foundry::makeObject( $file );
		}

		// Get the access data.
		$access	= Foundry::table( 'Access' );
		$access->load( array( 'uid' => $uid , 'type' => $type ) );

		// Load the stored config
		$defaultValues 		= Foundry::makeObject( $defaults );	
		$registry			= Foundry::registry( $defaults );

		$storedRegistry		= Foundry::registry( $access->params );

		$registry->mergeObjects( $storedRegistry , false , true );

		$form 	= Foundry::form();
		$form->load( $forms );
		$form->bind( $registry );


		$output	= $form->render( true , true , $activeTab , $prefix );

		return $output;
	}

}
