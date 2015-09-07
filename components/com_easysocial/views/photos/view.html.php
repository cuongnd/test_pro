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

class EasySocialViewPhotos extends EasySocialSiteView
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

	public function display( $content = '' )
	{
		// Check if photos is enabled
		$this->checkFeature();

		// See if are viewing another user's album (userid param determines that).
		$uid	= JRequest::getInt('userid' , null);

		// If we viewing another user's albums, load that user.
		// If not, load current logged in user.
		$user	= Foundry::user( $uid );

		$url 	= FRoute::albums( array( 'userid' => $user->getAlias() ) , false );

		return $this->redirect( $url );
	}

	public function restricted($uid=null)
	{
		$uid  = (!empty($uid)) ? $uid : JRequest::getInt('userid' , null);
		$user = Foundry::user( $uid );

		$this->set( 'showProfileHeader', true);
		$this->set( 'user'   , $user );

		echo parent::display( 'site/photos/restricted' );
	}

	public function deleted($uid=null)
	{
		$uid  = (!empty($uid)) ? $uid : JRequest::getInt('userid' , null);
		$user = Foundry::user( $uid );

		$this->set( 'showProfileHeader', true);
		$this->set( 'user'   , $user );

		echo parent::display( 'site/photos/deleted' );
	}

	/**
	 * Displays the photo item
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function item()
	{
		// Check if photos is enabled
		$this->checkFeature();

		// Get current user
		$my		= Foundry::user();

		// Get photo
		$id		= JRequest::getInt( 'id' , null );
		$photo	= Foundry::photo( $id );

		// If id is not given or photo does not exist
		if( !$id || !$photo->data->id )
		{
			return $this->deleted();
		}

		// Set the opengraph data for this photo
		Foundry::opengraph()->addImage( $photo->data->getSource() );

		// Get album
		$album = $photo->album();

		// Set the page title.
		Foundry::page()->title( $photo->data->get( 'title' ) );

		// Not viewable
		if( !$photo->data->viewable() )
		{
			return $this->restricted($photo->data->uid);
		}

		// Set the breadcrumbs
		Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ALBUMS' ) , FRoute::albums() );
		Foundry::page()->breadcrumb( $album->data->get( 'title' ) , $album->data->getPermalink() );
		Foundry::page()->breadcrumb( $photo->data->get( 'title' ) );

		// Assign a badge for the user
		$photo->data->assignBadge( 'photos.browse' , $my->id );

		// Render options
		$options = array(
			'viewer'         => $my->id,
			'size'           => SOCIAL_PHOTOS_LARGE,
			'showNavigation' => true
		);

		if( Foundry::user()->id == 0 )
		{
			$options['showToolbar'] 	= false;
			$options['showResponse'] 	= false;
		}

		// Render item
		$html = $photo->renderItem( $options );

		return $this->output( $html );
	}

 	public function output( $content = '' )
 	{
		$uid	= JRequest::getInt('userid' , null);

 		// If we viewing another user's albums, load that user.
 		// If not, load current logged in user.
		$user = Foundry::user( $uid );

		// If the user does not exist, redirect to spotlight.
		if( !$user->id )
		{
			return $this->redirect( FRoute::dashboard( array() , false ) );
		}

		// Get current photo
		$id		= JRequest::getInt( 'id' , null );
		$photo	= Foundry::table( 'photo' );
		$photo->load( $id );

		// Get the photo creator
		$creator 	= Foundry::user( $photo->uid );

		// Build the user alias
		$userAlias 	= $creator->getAlias();

		// Get album photo
		$albumId = JRequest::getInt( 'album_id', $photo->album_id );
		$album = Foundry::table( 'album' );
		$album->load( $albumId );

		// If the user can view the album, display list of photos.
		if ( $album->viewable()	)
		{
			$photos = $album->getPhotos(array('limit'=>2048));
			$photos = $photos['photos'];
		// Else only display that single photo in the list
		} else {
			$photos = array($photo);
		}

		$this->set( 'userAlias' , $userAlias );
		$this->set( 'id'     , $id );
		$this->set( 'user'   , $user );
		$this->set( 'album'  , $album );
		$this->set( 'photos' , $photos );
		$this->set( 'photo'  , $photo );
		$this->set( 'content', $content );
		$this->set( 'uuid'   , uniqid() );

		echo parent::display( 'site/photos/default' );
 	}


	/**
	 * Displays the photo form
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function form()
	{
		// Only logged in users are allowed to modify photos.
		Foundry::requireLogin();

		// Check if photos is enabled
		$this->checkFeature();

		// Get current user
		$my = Foundry::user();

		// Get photo
		$id		= JRequest::getInt( 'id' , null );
		$photo	= Foundry::photo( $id );

		// If id is not given or photo does not exist
		if( !$id || !$photo->data->id )
		{
			return $this->deleted();
		}

		// Get album
		$album = $photo->album();

		// If the user can view this photo
		if( $photo->data->viewable() )
		{
			// Set the page title.
			Foundry::page()->title( $photo->data->get( 'title') );

			// Set the breadcrumbs
			Foundry::page()->breadcrumb( JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_ALBUMS' ) , FRoute::albums() );
			Foundry::page()->breadcrumb( $album->data->get( 'title' ) , $album->data->getPermalink() );
			Foundry::page()->breadcrumb( $photo->data->get( 'title' ) );

			// Assign a badge for the user
			$photo->data->assignBadge( 'photos.browse' , $my->id );

			// Render options
			$options = array( 'size' => 'large', 'showForm' => true , 'layout' => 'form');

			// Render item
			$html = $photo->renderItem( $options );
		}
		else
		{
			// @TODO: Put this in proper template & language string.
			$html = 'You are not allowed to edit this photo';
		}

		return $this->output( $html );
	}

	/**
	 * Allows use to download a photo from the site.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function download()
	{
		// Get the id of the photo
		$id 	= JRequest::getInt( 'id' );
		$info 	= Foundry::info();
		
		// Check if photos is enabled
		$this->checkFeature();

		// Id provided must be valid
		if( !$id )
		{
			$this->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_PHOTO_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
			$info->set( $this->getMessage() );

			return $this->redirect( FRoute::albums( array() , false ) );
		}

		$photo = Foundry::photo( $id );

		if( !$photo->data->viewable() )
		{
			return $this->restricted( $photo->data->uid );
		}
		$photo->data->getExtension();
		// Let's try to download the file now
		$photo->data->download();

	}
}
