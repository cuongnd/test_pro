<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Import parent view
Foundry::import( 'site:/views/views' );

class EasySocialViewAlbums extends EasySocialSiteView
{
	private function checkFeature()
	{
		$config	= Foundry::config();

		// Do not allow user to access photos if it's not enabled
		if( !$config->get( 'photos.enabled' ) )
		{
			$this->setMessage( JText::_( 'COM_EASYSOCIAL_ALBUMS_PHOTOS_DISABLED' ) , SOCIAL_MSG_ERROR );

			Foundry::info()->set( $this->getMessage() );
			$this->redirect( FRoute::dashboard( array() , false ) );
			$this->close();
		}
	}

	/**
	 * Displays a list of recent albums that the user created.
	 *
	 * @since	1.0
	 * @access	public
	 * @return
	 */
	public function display( $tpl = null )
	{
		// Check if the current request is made for the current logged in user or another user.
		$uid	= JRequest::getInt( 'userid' , null );

		if( !$uid )
		{
			$uid 	= null;
		}

		$user 	= Foundry::user( $uid );

		// Check if photos is enabled
		$this->checkFeature();

		// We do not want to display a guest album. Does not make sense
		if( !$user->id )
		{
			$this->setMessage( JText::_( 'COM_EASYSOCIAL_ALBUMS_INVALID_USER_PROVIDED' ) , SOCIAL_MSG_ERROR );

			Foundry::info()->set( $this->getMessage() );
			$this->redirect( FRoute::dashboard( array() , false ) );
			$this->close();
		}

		// Set page title
		$title 	= JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ALBUMS' );

		if( !$user->isViewer() )
		{
			$title	= $user->getName() . ' - ' . $title;
		}

		Foundry::page()->title( $title );

		// Set the breadcrumbs
		Foundry::page()->breadcrumb( $title );

		// Get albums model
		$model 	= Foundry::model( 'Albums' );
		$model->initStates();

		$startlimit = JRequest::getVar( 'limitstart', '');
		if( ! $startlimit )
		{
			$model->setState( 'limitstart', 0);
		}

		// Get list of norm albums
		$albums = $model->getAlbums( $user->id , SOCIAL_TYPE_USER, array( 'pagination' => true ) );
		$pagination = $model->getPagination();

		// Format albums by date
		$data	= Foundry::albums()->groupAlbums( $albums );

		$theme	= Foundry::themes();

		$theme->set( 'user'			, $user );
		$theme->set( 'data' 		, $data );
		$theme->set( 'pagination' 	, $pagination );


		// Get the theme output
		$html = $theme->output( 'site/albums/list' );

		// Wrap it with the albums wrapper.
		return $this->output( $html );
	}

	/**
	 * Displays a restricted page
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user's id
	 */
	public function restricted( $uid=null )
	{
		$uid  = (!empty($uid)) ? $uid : JRequest::getInt('userid' , null);
		$user = Foundry::user( $uid );

		$this->set( 'showProfileHeader', true);
		$this->set( 'user'   , $user );

		echo parent::display( 'site/albums/restricted' );
	}

	/**
	 * Post process after an album is deleted
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		Optional user id
	 */
	public function deleted($uid=null)
	{
		// Require user to be logged in
		Foundry::requireLogin();

		$uid  = (!empty($uid)) ? $uid : JRequest::getInt('userid' , null);
		$user = Foundry::user( $uid );

		$this->set( 'showProfileHeader', true);
		$this->set( 'user'   , $user );

		echo parent::display( 'site/albums/deleted' );
	}

	/**
	 * Displays the album item
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function item()
	{
		// Check if photos is enabled
		$this->checkFeature();

		// Get logged in user
		$my		= Foundry::user();

		// Determine if user is trying to view another person's album
		$userId = JRequest::getInt( 'userid' , null );
		$user 	= Foundry::user( $userId );

		// Retrieve the album from request
		$id		= JRequest::getInt( 'id', null );

		// Load album
		$album = Foundry::albums( $id );

		// Empty id or invalid id is not allowed.
		if( !$id || !$album->data->id )
		{
			return $this->deleted();
		}

		// Check if the album is viewable
		if( !$album->data->viewable() )
		{
			return $this->restricted( $album->data->uid );
		}

		// Get a list of photos within this album
		$photos 	= $album->getPhotos( $album->data->id );
		$photos 	= $photos[ 'photos' ];

		if( $photos )
		{
			foreach( $photos as $photo )
			{
				// Set the opengraph data for photos within this album
				Foundry::opengraph()->addImage( $photo->getSource() );
			}
		}

		// Set page title
		$title 	= $album->data->get( 'title' );

		if( !$user->isViewer() )
		{
			$title	= $user->getName() . ' - ' . $title;
		}

		Foundry::page()->title( $title );

		// Set the breadcrumbs
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ALBUMS' ) , FRoute::albums() );
		Foundry::page()->breadcrumb( $album->data->get( 'title' ) );

		// Render options
		$options = array(
			'viewer' => $my->id
		);

		// Render item
		$html = $album->renderItem( $options );

		return $this->output( $html, $album->data );
	}

	/**
	 * Renders the album's form
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function form()
	{
		// Only allow registered users to upload photos
		Foundry::requireLogin();

		// Check if photos is enabled
		$this->checkFeature();

		$my = Foundry::user();

		// Get album id
		$id = JRequest::getInt( 'id', null );

		// Load album
		$album = Foundry::albums( $id );

		// If we are creating an album
		if ( !$album->data->id ) {

			// Set album ownership to the current logged in user.
			$album->data->uid = $my->id;

			// Check if we have exceeded album creation limit.
			if ( Foundry::access()->exceeded( 'albums.total' , $my->getTotalAlbums(true) ) ) {

				$theme = Foundry::themes();
				$theme->set( 'user', $my );
				$html = $theme->output( 'site/albums/exceeded' );

				return $this->output( $html, $album->data );
			}
		}

		// Set page title
		$title 	= JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ALBUMS' );
		Foundry::page()->title( $album->data->get( 'title' ) );

		// Set the breadcrumbs
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ALBUMS' ) , FRoute::albums() );
		Foundry::page()->breadcrumb( $album->data->get( 'title' ) );

		// If user can edit this album
		if ($album->data->editable()) {

			// Render options
			$options = array(
				'viewer'       => $my->id,
				'layout'       => 'form',
				'showStats'    => false,
				'showResponse' => false,
				'showTags'     => false
			);

			// Render item
			$html = $album->renderItem( $options );

		// If user cannot edit this album
		} else {

			$this->restricted( $album->data->uid );
		}

		return $this->output( $html, $album->data );
	}

	/**
	 * Displays the albums a user has
	 *
	 * @since	1.0
	 * @access	public
	 * @author	Mark Lee <mark@stackideas.com>
	 */
	public function output( $content = '' , $album = false )
	{
		// If album is passed in, we need to get the owner of the item.
		if( $album !== false )
		{
			$uid 	= $album->uid;
			$user 	= Foundry::user( $album->uid );
		}
		else
		{
			// See if are viewing another user's album
			$uid = JRequest::getInt( 'userid' , null );

			if( !$uid )
			{
				$uid 	= null;
			}

			// If we viewing another user's albums, load that user.
			// If not, load current logged in user.
			$user 	= Foundry::user( $uid );
		}

		// If no layout was given, load recent layout
		$layout		= JRequest::getCmd( 'layout' , 'recent' );

		// Browser menu
		$id     = JRequest::getInt('id');
		$model 	= Foundry::model( 'Albums' );

		// Get a list of core albums
		$coreAlbums 	= $model->getAlbums( $user->id , SOCIAL_TYPE_USER , array( 'coreAlbumsOnly' => true ) );

		// Get a list of normal albums
		$albums 		= $model->getAlbums( $user->id, SOCIAL_TYPE_USER , array( 'core' => false , 'ordering' => 'core' ) );

		// Browser frame
		// Get the user alias
		$userAlias 	= $user->getAlias();

		$this->set( 'userAlias', $userAlias );
		$this->set( 'id'     , $id );
		$this->set( 'user'   , $user );
		$this->set( 'coreAlbums'	, $coreAlbums );
		$this->set( 'albums' , $albums );
		$this->set( 'content', $content );
		$this->set( 'uuid'   , uniqid() );
		$this->set( 'layout' , $layout );

		echo parent::display( 'site/albums/default' );
	}

	/**
	 * Post processing when creating a new album
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function store( $album = null )
	{
		// Require user to be logged in
		Foundry::requireLogin();

		Foundry::info()->set( $this->getMessage() );

		if( $this->hasErrors() )
		{
			return $this->form();
		}

		return $this->redirect( FRoute::albums( array('id' => $album->getAlias() , 'layout' => 'item' )) );
	}

	/**
	 * Post processing when deleting an album
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function delete()
	{
		// Require user to be logged in
		Foundry::requireLogin();

		Foundry::info()->set( $this->getMessage() );

		$this->redirect( FRoute::albums( array() , false ) );
	}
}
