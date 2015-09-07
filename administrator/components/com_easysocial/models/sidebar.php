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

// Include the main model parent.
Foundry::import( 'admin:/includes/model' );

/**
 * Model for admin's sidebar.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class EasySocialModelSidebar extends EasySocialModel
{
	function __construct()
	{
		parent::__construct( 'sidebar' );
	}

	public function sortSidebar( $a , $b )
	{
		return $a->order < $b->order ? -1 : 1;
	}

	/**
	 * Returns a list of menus for the admin sidebar.
	 *
	 * Example:
	 * <code>
	 * <?php
	 * $model 	= Foundry::model( 'Sidebar' );
	 *
	 * // Returns an array of menu items.
	 * $model->getItems();
	 * ?>
	 * </code>
	 *
	 * @since	1.0
	 * @access	public
	 * @param	null
	 * @return	Array
	 *
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function getItems()
	{
		// @TODO: Configurable theme path for the back end.
		$theme	= Foundry::themes();
		$path 	= Foundry::resolve( 'themes:/admin/defaults/sidebar' );

		jimport( 'joomla.filesystem.folder' );
		$files 	= JFolder::files( $path , '.json' , false , true  );

		// Lets process the json contents.
		$items 		= array();

		foreach( $files as $file )
		{
			$item 		= Foundry::makeObject( $file );

			$items[]	= $item;
		}

		// usort( $items , array( $this , 'sortSidebar' ) );

		// If there are no items there, it should throw an error.
		if( !$items )
		{
			Foundry::logError( __FILE__ , __LINE__ , 'SIDEBAR: Unable to parse menu.json file.' );
			return false;
		}

		// Initialize default result.
		$result 	= array();

		foreach( $items as $item )
		{
			// Generate a unique id.
			$uid 	= uniqid();

			// Generate a new group object for the sidebar.
			$obj 	= clone( $item );

			// Assign the unique id.
			$obj->uid 	= $uid;

			// Initialize the counter
			$obj->count	= 0;


			// Test if there's a counter key.
			if( isset( $obj->counter ) )
			{
				$namespace 	= explode( '/' , $obj->counter );
				$method 	= $namespace[ 1 ];
				$namespace 	= $namespace[ 0 ];

				$model 		= Foundry::model( $namespace );

				$count 		= $model->$method();
				$obj->count = $count;
			}

			if( !empty( $obj->childs ) )
			{
				$childItems 	= array();

				usort( $obj->childs , array( 'EasySocialModelSidebar' , 'sortItems' ) );

				foreach( $obj->childs as $child )
				{
					// Clone the child object.
					$childObj 	= clone( $child );

					// Let's get the URL.
					$url 					= array( 'index.php?option=com_easysocial' );
					$query 					= Foundry::makeArray( $child->url );

					// Set the url into the child item so that we can determine the active submenu.
					$childObj->url			= $child->url;
							
					if( $query )
					{
						foreach( $query as $queryKey => $queryValue )
						{
							$url[]	= $queryKey . '=' . $queryValue;

							// If this is a call to the controller, it must have a valid token id.
							if( $queryKey == 'controller' )
							{
								$url[]	= Foundry::token() . '=1'; 
							}
						}
					}

					// Set the item link.
					$childObj->link 	= implode( '&amp;' , $url );
					
					// Initialize the counter
					$childObj->count	= 0;

					// Check if there's any sql queries to execute.
					if( isset( $childObj->counter ) )
					{
						$namespace 	= explode( '/' , $childObj->counter );
						$method 	= $namespace[ 1 ];
						$namespace 	= $namespace[ 0 ];

						$model 		= Foundry::model( $namespace );

						$count 		= $model->$method();
						$childObj->count 	= $count;
					}
					
					// Add a unique id for the side bar for accordion purposes.
					$childObj->uid 		= $uid;

					// Add the menu item to the child items.
					$childItems[]		= $childObj;
				}

				$obj->childs 	= $childItems;
			}
			
			$result[]	= $obj;
		}

		// @TODO: Render applications to see if they want to add any menu's here.

		return $result;
	}

	public static function sortItems( $a , $b )
	{
		$al 	= JString::strtolower( JText::_( $a->title ) );
		$bl 	= JString::strtolower( JText::_( $b->title ) );

		if( $al == $bl )
		{
			return 0;
		}

		return ( $al > $bl ) ? +1 : -1;
	}
}