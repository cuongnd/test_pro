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

// Import the required file and folder classes.
jimport( 'joomla.filesystem.file' );
jimport( 'joomla.filesystem.folder' );

/**
 * Photos library.
 *
 * @since	1.0
 * @author	Mark Lee <mark@stackideas.com>
 */
class SocialPhoto
{
	public $data = null;

	public function __construct( $id = null )
	{
		$data = Foundry::table( 'Photo' );
		$data->load( $id );

		$this->data = $data;
	}	

	public static function factory( $id = null )
	{
		return new self( $id );
	}	

	private $renderItemOptions = array(
		'viewer'         => null,
		'layout'         => 'item',
		'size'           => 'thumbnail',
		'showNavigation' => false,
		'showToolbar'    => true,
		'showInfo'       => true,
		'showStats'      => true,
		'showResponse'   => true,
		'showTags'       => true,
		'showForm'       => true
	);

	/**
	 * Wraps the provided album
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function renderItem($options=array())
	{
		// Normalize render options
		$options = array_merge($this->renderItemOptions, $options);
		
		// Build user alias
		$creator 	= $this->creator();
		$viewer  	= Foundry::user($options['viewer']);

		$userAlias	= $creator->getAlias();

		// Generate photo item template
		$theme = Foundry::themes();		
		$theme->set( 'userAlias', $userAlias             );
		$theme->set( 'tags'		, $this->data->getTags() );
		$theme->set( 'comments'	, $this->comments()      );
		$theme->set( 'likes'	, $this->likes()         );
		$theme->set( 'shares'	, $this->reposts()       );
		$theme->set( 'album'    , $this->album()->data   );
		$theme->set( 'photo'    , $this->data            );
		$theme->set( 'creator'  , $creator               );
		$theme->set( 'privacy'  , $this->privacy()       );
		$theme->set( 'options'  , $options               );

		return $theme->output( 'site/photos/item' );
	}

	public function album()
	{
		return Foundry::albums( $this->data->album_id );
	}

	public function creator()
	{
		return Foundry::user( $this->data->uid );
	}

	public function privacy()
	{
		// @TODO: Get proper photo privacy
		return Foundry::privacy();
	}

	public function likes()
	{
		return Foundry::likes( $this->data->id, SOCIAL_TYPE_PHOTO , SOCIAL_APPS_GROUP_USER );
	}

	public function comments()
	{
		return Foundry::comments( $this->data->id , SOCIAL_TYPE_PHOTO , SOCIAL_APPS_GROUP_USER , array( 'url' => FRoute::photos( array( 'id' => $this->data->id ) ) ) );
	}

	public function reposts()
	{
		return Foundry::get( 'Repost', $this->data->id, SOCIAL_TYPE_PHOTO , SOCIAL_APPS_GROUP_USER );
	}
}
