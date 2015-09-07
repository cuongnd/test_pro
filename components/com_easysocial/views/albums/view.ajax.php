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
	public function exceeded($uid=null)
	{
		$ajax = Foundry::ajax();

		$uid  = (!empty($uid)) ? $uid : JRequest::getInt('userid' , null);
		$user = Foundry::user( $uid );

		$theme = Foundry::themes();
		$theme->set( 'showProfileHeader', false );
		$theme->set( 'user'   , $user );

		$html = $theme->output( 'site/albums/exceeded' );
		$ajax->resolve( $html );
	}

	public function restricted($uid=null)
	{
		$ajax = Foundry::ajax();

		$uid  = (!empty($uid)) ? $uid : JRequest::getInt('userid' , null);
		$user = Foundry::user( $uid );

		$theme = Foundry::themes();
		$theme->set( 'showProfileHeader', false );
		$theme->set( 'user'   , $user );

		$html = $theme->output( 'site/albums/restricted' );
		$ajax->resolve( $html );
	}

	public function deleted($uid=null)
	{
		$ajax = Foundry::ajax();

		$uid  = (!empty($uid)) ? $uid : JRequest::getInt('userid' , null);
		$user = Foundry::user( $uid );

		$theme = Foundry::themes();
		$theme->set( 'user'   , $user );
		$html = $theme->output( 'site/albums/deleted' );

		$ajax->resolve( $html );
	}	

	/**
	 * Renders the single album view
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function item()
	{
		$ajax = Foundry::ajax();

		$my = Foundry::user();

		// Get the album id from the request
		$id = JRequest::getInt( 'id' , null );

		$album	= Foundry::albums( $id );

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

		$renderOptions = JRequest::getVar('renderOptions', array());

		// Render the album item
		$html = $album->renderItem($renderOptions);

		return $ajax->resolve( $html );
	}

	/**
	 * Renders the album form.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function form()
	{
		// Only allow registered users to upload photos
		Foundry::requireLogin();

		$ajax = Foundry::ajax();

		$my = Foundry::user();

		// Get album id
		$id = JRequest::getInt( 'id', null );

		// Get album
		$album	= Foundry::albums( 'Album', $id );

		// If we are creating an album
		if ( !$album->data->id ) {

			// Check if we have exceeded album creation limit first
			if ( Foundry::access()->exceeded( 'albums.total' , $my->getTotalAlbums(true) ) ) {
				return $this->exceeded( $my->id );
			}			

			// Set album ownership to the current logged in user.
			$album->data->uid = $my->id;
		}

		// If user can edit this album
		if ($album->data->editable()) {

			// Render options
			$options = array(
				'layout' => 'form',
				'showStats'    => false,
				'showResponse' => false,
				'showTags'     => false
			);	

			// Render the album item
			$html = $album->renderItem( $options );

		// If user cannot edit this album
		} else {

			return $this->restricted( $album->data->uid );
		}

		return $ajax->resolve( $html );
	}

	public function dialog()
	{
		Foundry::requireLogin();

		$ajax = Foundry::ajax();

		
		$user = Foundry::user();
		$content = '<div class="es-content-hint">' . JText::_('COM_EASYSOCIAL_ALBUMS_SELECT_ALBUM_HINT') . '</div>';
		$layout = "item";


		// Browser menu
		$id     = JRequest::getInt('id');
		$model 	= Foundry::model( 'Albums' );
		$albums = $model->getAlbums( $user->id, SOCIAL_TYPE_USER );

		// Browser frame
		// Get the user alias
		$userAlias 	= $user->getAlias();

		$theme = Foundry::themes();

		$theme->set( 'userAlias', $userAlias );
		$theme->set( 'id'     , $id );
		$theme->set( 'user'   , $user );
		$theme->set( 'content' , $content );
		$theme->set( 'albums' , $albums );
		$theme->set( 'uuid'   , uniqid() );
		$theme->set( 'layout' , $layout );

		$html = $theme->output( 'site/albums/dialog' );

		return $ajax->resolve( $html );
	}

	/**
	 * Returns a list of likes for this album
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function response()
	{
		$ajax = Foundry::ajax();

		// Get id from request.
		$id 	= JRequest::getInt( 'id' );

		$album 	= Foundry::table( 'Album' );
		$album->load( $id );

		if( !$id || !$album->id )
		{
			return $ajax->reject();
		}

		$theme = Foundry::themes();
		$theme->set( 'album' , $album );
		$html = $theme->output( 'site/albums/album.response' );

		return $ajax->resolve( $html );
	}

	/**
	 * Retrieves a list of albums
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function listItems( $albums )
	{
		$ajax 	= Foundry::ajax();

		return $ajax->resolve( $albums );
	}

	/**
	 * Returns album object to the caller.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAlbum( $album = null )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve( $album->export(array('cover', 'photos')) );
	}

	/**
	 * Post processing when creating a new album
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function store( $album = null )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		// Success message
		$theme = Foundry::themes();
		$theme->set('album', $album);
		$message = $theme->output( 'site/albums/message.album.save' );
		$ajax->notify($message, SOCIAL_MSG_SUCCESS);
		
		// Item html
		$html = Foundry::albums( $album->id )->renderItem();

		return $ajax->resolve($album->export(), $html);
	}

	public function delete( $state )
	{
		$ajax = Foundry::ajax();

		if (!$state) {

			return $ajax->reject( $this->getMessage() );
		}

		$redirect = JRequest::getBool('redirect', 1);

		if ($redirect)
		{
			$url = FRoute::albums();
			return $ajax->redirect( $url );
		}
		else
		{
			return $ajax->resolve();
		}
	}

	/**
	 * Displays a confirmation dialog to delete an album.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function confirmDelete()
	{
		$ajax = Foundry::ajax();

		$id 	= JRequest::getInt( 'id' );

		// Get dialog
		$theme	= Foundry::themes();
		$theme->set( 'id' , $id );
		$output	= $theme->output( 'site/albums/dialog.delete' );

		return $ajax->resolve( $output );
	}

	public function setCover( $photo = null )
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve( $photo->export() );
	}

	public function loadMore( $photos = array(), $nextStart = 0 )
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		// Get the current logged in user.
		$my = Foundry::user();		

		// Get layout
		$layout = JRequest::getCmd('layout', 'item');

		$options = array(
			'viewer' => $my->id,
			'layout' => $layout,
			'showResponse' => false,
			'showTags'     => false
		);

		if ($layout=="dialog") {
			$options['showForm'] = false;
			$options['showInfo'] = false;
			$options['showStats'] = false;
			$options['showToolbar'] = false;
		}

		$htmls = array();

		foreach( $photos as $photo )
		{
			$htmls[] = Foundry::photo( $photo->id )->renderItem( $options );
		}		

		return $ajax->resolve( $htmls, $nextStart );
	}

	public function reorder()
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve();
	}

}
