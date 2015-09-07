
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
	/**
	 * Renders the html wrapper for photos
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	private function renderBrowser( $content='' )
	{
		$ajax = Foundry::ajax();

		// See if are viewing another user's album (userid param determines that).
		$uid = JRequest::getInt('userid' , null);

		// If we viewing another user's albums, load that user.
		// If not, load current logged in user.
		$user = Foundry::user( $uid );

		// If the user does not exist, redirect to spotlight.
		if( !$user->id )
		{
			return $ajax->reject();
		}

		// Get current photo
		$id = JRequest::getInt( 'id' , null );
		$photo = Foundry::table( 'photo' );
		$photo->load( $id );

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

		// Build the user alias
		$userAlias = $album->getCreator()->getAlias();

		// Generate photo browser template
		$theme = Foundry::themes();
		$theme->set( 'userAlias' , $userAlias );
		$theme->set( 'id'     , $id );
		$theme->set( 'user'   , $user );
		$theme->set( 'album'  , $album );
		$theme->set( 'photos' , $photos );
		$theme->set( 'photo'  , $photo );
		$theme->set( 'content', $content );
		$theme->set( 'uuid'   , uniqid() );

		return $theme->output( 'site/photos/default' );
	}

	public function restricted($uid=null)
	{
		$ajax = Foundry::ajax();

		$uid  = (!empty($uid)) ? $uid : JRequest::getInt('userid' , null);
		$user = Foundry::user( $uid );

		$theme = Foundry::themes();
		$theme->set( 'showProfileHeader', false );
		$theme->set( 'user'   , $user );

		$html = $theme->output( 'site/photos/restricted' );
		$ajax->resolve( $html );
	}

	public function deleted($uid=null)
	{
		$ajax = Foundry::ajax();

		$uid  = (!empty($uid)) ? $uid : JRequest::getInt('userid' , null);
		$user = Foundry::user( $uid );

		$theme = Foundry::themes();
		$theme->set( 'showProfileHeader', false );
		$theme->set( 'user'   , $user );
		$html = $theme->output( 'site/photos/deleted' );

		$ajax->resolve( $html );
	}

	/**
	 * Renders a photo item
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function item()
	{
		$ajax = Foundry::ajax();

		// Get current user
		$my = Foundry::user();

		// Get photo
		$id = JRequest::getInt( 'id' , null );
		$photo = Foundry::photo( $id );

		// If id is not given or photo does not exist
		if( !$id || !$photo->data->id )
		{
			return $this->deleted();
		}

		// Get album
		$album = $photo->album();

		// Wrap the content in a photo browser if required
		$browser = JRequest::getInt( 'browser' , null );

		// Check if the album is viewable
		if( !$photo->data->viewable() )
		{
			return $this->restricted($photo->data->uid);
		}

		// Assign a badge for the user
		$photo->data->assignBadge( 'photos.browse' , $my->id );

		$options = array(
			'viewer'  => $my->id,
			'size'    => SOCIAL_PHOTOS_LARGE,
			'showNavigation' => true
		);

		if( Foundry::user()->id == 0 )
		{
			$options['showToolbar'] 	= false;
			$options['showResponse'] 	= false;
		}

		$html = $photo->renderItem( $options );

		// Assign a badge for the user
		$photo->data->assignBadge( 'photos.browse' , $my->id );

		if ($browser)
		{
			$html = $this->renderBrowser($html);
		}

		return $ajax->resolve( $html );
	}

	public function getPhoto($data=null)
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve( $data );
	}

	/**
	 * Post processing after a photo is unliked
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function unlike( $photoId )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$like 	= Foundry::likes( $photoId , SOCIAL_TYPE_PHOTO );

		$obj 		= new stdClass();
		$obj->state	= false;
		$obj->count	= $like->getCount();
		$obj->html 	= $like->toString();

		return $ajax->resolve( $obj );
	}

	/**
	 * Post processing after a photo is liked
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function like( $photoId )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$like 	= Foundry::likes( $photoId , SOCIAL_TYPE_PHOTO );

		$obj 		= new stdClass();
		$obj->state	= true;
		$obj->count	= $like->getCount();
		$obj->html 	= $like->toString();

		return $ajax->resolve( $obj );
	}

	/**
	 * Returns a list of likes for this photo
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

		$photo 	= Foundry::table( 'Photo' );
		$photo->load( $id );

		if( !$id || !$photo->id )
		{
			return $ajax->reject();
		}

		$theme = Foundry::themes();
		$theme->set( 'photo' , $photo );
		$html = $theme->output( 'site/albums/photo.response' );

		return $ajax->resolve( $html );
	}

	/**
	 * Post process after a photo has been featured
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function feature( $isFeatured = false )
	{
		$ajax 	= Foundry::ajax();

		if( $isFeatured )
		{
			$this->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_PHOTO_FEATURED_SUCCESS' ) , SOCIAL_MSG_SUCCESS );
		}
		else
		{
			$this->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_PHOTO_UNFEATURED_SUCCESS' ) , SOCIAL_MSG_SUCCESS );
		}


		return $ajax->resolve( $this->getMessage() , $isFeatured );
	}

	/**
	 * Processes after an item is marked as featured
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function toggleFeatured()
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve();
	}

	public function confirmDelete()
	{
		$ajax = Foundry::ajax();

		// Get dialog
		$theme = Foundry::themes();
		$html = $theme->output( 'site/photos/dialog.delete' );

		return $ajax->resolve( $html );
	}

	/**
	 * Post processing after a tag is deleted
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function deleteTag( )
	{
		$ajax 	= Foundry::ajax();

		return $ajax->resolve( );
	}

	/**
	 * Post processing after a photo is deleted
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function delete( $newCover = false )
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		if( $newCover )
		{
			$ajax->setCover( $newCover->export() );
		}

		return $ajax->resolve();
	}

	public function move()
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		return $ajax->resolve();
	}

	public function moveToAnotherAlbum()
	{
		$ajax = Foundry::ajax();

		// Get the current user.
		$my		= Foundry::user();

		// Get photo
		$id		= JRequest::getInt( 'id' );
		$photo	= Foundry::table( 'photo' );
		$photo->load( $id );

		// Check if the user is really allowed to move the photo

		// Get albums
		$model	= Foundry::model( 'Albums' );
		$albums	= $model->getAlbums( $my->id , SOCIAL_TYPE_USER , array( 'exclusion' => $photo->album_id ) );


		// Get dialog
		$theme = Foundry::themes();
		$theme->set( 'albums', $albums );
		$theme->set( 'photo' , $photo );
		$html = $theme->output( 'site/photos/dialog.move' );

		return $ajax->resolve( $html );
	}

	/**
	 * Returns a list of tags for a particular photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTags( $tags )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getErrors() );
		}

		return $ajax->resolve( $tags );
	}

	/**
	 * Processes after storing a tag object
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createTag( $tag, $photo )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getError() );
		}

		$theme = Foundry::themes();
		$theme->set('tag'  , $tag);
		$theme->set('photo', $photo);
		$tagItem = $theme->output('site/photos/tags.item');
		$tagListItem = $theme->output('site/photos/taglist.item');

		$ajax->resolve( $tag, $tagItem, $tagListItem );
	}

	/**
	 * Post processing after creating a profile cover from a photo.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createCover( $cover = null )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		// Format the output
		$result 		= new stdClass();

		$result->url 		= $cover->getSource();
		$result->position 	= $cover->getPosition();
		$result->x 			= $cover->x;
		$result->y 			= $cover->y;

		return $ajax->resolve( $result );
	}

	/**
	 * Post processing after creating an avatar from a photo
	 *
	 * @since	1.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function createAvatar( $photo = null )
	{
		$ajax 	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$my 		= Foundry::user();

		$userObj 	= new stdClass();
		$userObj->avatars 	= new stdClass();

		// Initialize values
		$userObj->avatars->small	= $my->getAvatar( SOCIAL_AVATAR_SMALL );
		$userObj->avatars->medium 	= $my->getAvatar( SOCIAL_AVATAR_MEDIUM );
		$userObj->avatars->large 	= $my->getAvatar( SOCIAL_AVATAR_LARGE );
		$userObj->avatars->square 	= $my->getAvatar( SOCIAL_AVATAR_SQUARE );

		$photoObj 	= (object) $photo;

		return $ajax->resolve($photoObj, $userObj, $my->getPermalink( false ) );
	}

	/**
	 * Post processing after a photo is rotated.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTablePhoto	The photo table.
	 * @param	Array				The paths.
	 * @return
	 */
	public function rotate($photo, $paths)
	{
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$result = $photo->getTags();
		$tags 	= array();

		if( $result )
		{
			foreach( $result as $row )
			{
				$obj 		= new stdClass();

				$obj->id		= $row->id;
				$obj->width		= $row->width;
				$obj->height	= $row->height;
				$obj->left 		= $row->left;
				$obj->top 		= $row->top;

				$tags[]	= $obj;
			}
		}

		return $ajax->resolve( $photo->export() , $tags );
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

	public function thumbnails()
	{
		$ajax = Foundry::ajax();

		$albumId = JRequest::getInt('albumId');
		$photoId = JRequest::getInt('photoId');

		if( !$albumId )
		{
			$ajax->reject('Invalid album id provided', SOCIAL_MSG_ERROR);
		}

		$album = Foundry::table('album');
		$album->load($albumId);

		$photos = $album->getPhotos();
		$photos = $photos["photos"];

		$theme = Foundry::themes();
		$theme->set('album', $album);
		$theme->set('photos', $photos);
		$theme->set('photoId', $photoId);

		$html = $theme->output('site/photos/thumbnails');

		return $ajax->resolve($html);
	}

	/**
	 * Displays the side bar of the photo in the popup
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function content()
	{
		$ajax = Foundry::ajax();

		$id = JRequest::getInt('id');

		if( !$id )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_PHOTOS_INVALID_PHOTO_ID_PROVIDED' ) , SOCIAL_MSG_ERROR );
		}

		$photo = Foundry::table( 'photo' );
		$photo->load( $id );

		$album = Foundry::table( 'album' );
		$album->load( $photo->album_id );

		$theme = Foundry::themes();
		$theme->set( 'photo', $photo );
		$theme->set( 'album', $album );
		$theme->set( 'photos', $album->getPhotos() );

		$type = JRequest::getCmd('type', 'single');

		$html = $theme->output('site/photos/content');

		return $ajax->resolve($html);
	}

	/**
	 * Post process after a photo is saved
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function update( $photo )
	{
		$ajax	= Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		$data	= $photo->export();

		$user 	= Foundry::user( $photo->uid );

		$theme	= Foundry::themes();
		$theme->set( 'userAlias' , $user->getAlias() );
		$theme->set( 'photo', $photo );
		$info	= $theme->output( 'site/photos/info' );

		return $ajax->resolve( $data, $info );
	}

	/**
	 * Post processing after a photo cover is uploaded on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @param	SocialTablePhoto
	 */
	public function uploadCover($photo=null)
	{
		// Load up the ajax library
		$ajax = Foundry::ajax();

		if( $this->hasErrors() )
		{
			return $ajax->reject( $this->getMessage() );
		}

		// Get the photo in json format
		$data = $photo->export();

		return $ajax->resolve($data);
	}


	/**
	 * Post process after a cover is deleted
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function removeCover()
	{
		$ajax 	= Foundry::ajax();

		$my 	= Foundry::user();
		$cover 	= $my->getCover();

		return $ajax->resolve( $cover->getSource() );
	}

	public function avatar()
	{
		$ajax 	= Foundry::ajax();

		// Get user
		$uid = JRequest::getInt('userid' , null);
		$user = Foundry::user( $uid );

		// Get photo id
		$id = JRequest::getInt('id');
		$photo = Foundry::photo($id);

		$redirect = JRequest::getInt('redirect', 1);

		// If photo does not exist, return;
		if (!$photo->data->id) {
			return $this->deleted();
		}

		$theme = Foundry::themes();
		$theme->set('user',  $user);
		$theme->set('photo', $photo->data);
		$theme->set('redirect', $redirect);
		$html = $theme->output('site/photos/avatar');

		return $ajax->resolve($html);
	}
}
