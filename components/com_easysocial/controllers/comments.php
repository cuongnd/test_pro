<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @author		Jason Rey <jasonrey@stackideas.com>
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'site:/controllers/controller' );

class EasySocialControllerComments extends EasySocialController
{
	/**
	 * Allows caller to save a comment.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function save()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Only registered users are allowed here.
		Foundry::requireLogin();

		// Get the view
		$view 	= $this->getCurrentView();

		// Check for permission first
		$access = Foundry::access();

		if( !$access->allowed( 'comments.add' ) )
		{
			$view->setMessage( 'ACL: Not allowed to add comments', SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$element	= JRequest::getString( 'element', '' );
		$group		= JRequest::getString( 'group', SOCIAL_APPS_GROUP_USER );
		$uid		= JRequest::getInt( 'uid', 0 );
		$input		= JRequest::getVar( 'input', '' , 'post' , 'none' , JREQUEST_ALLOWRAW );
		$data		= JRequest::getVar( 'data', array() );

		$compositeElement = $element . '.' . $group;

		$table		= Foundry::table( 'comments' );

		$table->element = $compositeElement;
		$table->uid = $uid;
		$table->comment = $input;
		$table->created_by = Foundry::user()->id;
		$table->created = Foundry::date()->toSQL();
		$table->params = $data;

		$state		= $table->store();

		if( !$state )
		{
			$view->setMessage( $table->getError(), SOCIAL_MSG_ERROR );
		}

		$dispatcher = Foundry::dispatcher();

		$comments 	= array( &$table );
		$args 		= array( &$comments );

		// @trigger: onPrepareComments
		$dispatcher->trigger( $group , 'onPrepareComments' , $args );

		return $view->call( __FUNCTION__, $table );
	}

	public function update()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Only registered users are allowed here.
		Foundry::requireLogin();

		// Get the view
		$view		= Foundry::view( 'comments', false );

		// Check for permission first
		$access = Foundry::access();

		$id		= JRequest::getInt( 'id', 0 );

		$table = Foundry::table( 'comments' );
		$state = $table->load( $id );

		if( !$state )
		{
			$view->setMessage( $table->getError(), SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		if( ! ( $access->allowed( 'comments.edit' ) || ( $access->allowed( 'comments.editown' ) && $table->isAuthor() ) ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_COMMENTS_NOT_ALLOWED_TO_EDIT' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$input	= JRequest::getVar( 'input', '' );

		$newData = array(
			'comment' => $input
		);

		$state = $table->update( $newData );

		if( !$state )
		{
			$view->setMessage( $table->getError(), SOCIAL_MSG_ERROR );
		}

		$view->call( __FUNCTION__, $table );
	}

	public function load()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Only registered users are allowed here.
		Foundry::requireLogin();

		// Get the view
		$view = Foundry::view( 'comments', false );

		// Check for permission first
		$access = Foundry::access();

		if( !$access->allowed( 'comments.read' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_COMMENTS_NOT_ALLOWED_TO_READ' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$element	= JRequest::getString( 'element', '' );
		$group		= JRequest::getString( 'group', SOCIAL_APPS_GROUP_USER );
		$uid		= JRequest::getInt( 'uid', 0 );
		$start		= JRequest::getInt( 'start', '' );
		$limit		= JRequest::getInt( 'length', '' );

		$compositeElement = $element . '.' . $group;

		$options	= array( 'element' => $compositeElement, 'uid' => $uid, 'start' => $start, 'limit' => $limit );

		$model		= Foundry::model( 'comments' );

		$comments	= $model->getComments( $options );

		if( !$comments )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_COMMENTS_ERROR_RETRIEVING_COMMENTS' ) , SOCIAL_MSG_ERROR );
		}

		$view->call( __FUNCTION__, $comments );
	}

	public function delete()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Only registered users are allowed here.
		Foundry::requireLogin();

		// Get the view
		$view = Foundry::view( 'comments', false );

		// Check for permission first
		$access = Foundry::access();

		$id	= JRequest::getInt( 'id', 0 );

		$table	= Foundry::table( 'comments' );

		$state = $table->load( $id );

		if( !$state )
		{
			$view->setMessage( $table->getError(), SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		if( ! ( $access->allowed( 'comments.delete' ) || ( $access->allowed( 'comments.deleteown' ) && $table->isAuthor() ) ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_COMMENTS_NOT_ALLOWED_TO_DELETE' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$state	= $table->delete();

		if( !$state )
		{
			$view->setMessage( $table->getError(), SOCIAL_MSG_ERROR );
		}

		$view->call( __FUNCTION__ );
	}

	public function like()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Only registered users are allowed here.
		Foundry::requireLogin();

		// Check permission
		$access = Foundry::access();

		$id = JRequest::getInt( 'id', 0 );

		$table = Foundry::table( 'comments' );
		$table->load( $id );

		$likes = $table->like();

		$view = Foundry::view( 'comments', false );

		if( $likes === false )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_COMMENTS_NOT_ABLE_TO_LIKE' ) , SOCIAL_MSG_ERROR );
		}

		$view->call( __FUNCTION__, $likes );
	}

	public function likedUsers()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Only registered users are allowed here.
		Foundry::requireLogin();

		// Check permission
		$access = Foundry::access();

		$id = JRequest::getInt( 'id', 0 );

		$likes = Foundry::likes( $id, 'comments' );

		$html = $likes->getLikedUsersDialog();

		$view = Foundry::view( 'comments', false );

		$view->call( __FUNCTION__, $html );
	}

	public function likesText()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Only registered users are allowed here.
		Foundry::requireLogin();

		// Check permission
		$access = Foundry::access();

		$id = JRequest::getInt( 'id', 0 );

		$likes = Foundry::likes( $id, 'comments' );

		$string = $likes->toHTML();

		$view = Foundry::view( 'comments', false );

		if( !$state )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_COMMENTS_NOT_ABLE_TO_LIKE' ) , SOCIAL_MSG_ERROR );
		}

		$view->call( __FUNCTION__, $string );
	}

	public function getUpdates()
	{
		$data = JRequest::getVar( 'data', '' );

		// Data comes in with the format of:
		// {
		// 	"stream.user": {
		// 		"1": {
		// 			"total": 10,
		// 			"count": 3,
		// 			"ids": ["7", "8", "9"]
		// 		}
		// 	}
		// }

		$newData	= array();
		$model		= Foundry::model( 'comments' );

		$updateLimit = Foundry::config()->get( 'comments_update_limit', 10 );

		foreach( $data as $key => $blocks )
		{
			$newData[$key] = array();

			foreach( $blocks as $uid => $block )
			{
				// Construct mandatory options
				$options = array( 'element' => $key, 'uid' => $uid, 'limit' => 0 );

				$newData[$key][$uid] = array(
					'total'	=> 0,
					'count'	=> 0,
					'ids'	=> array()
				);

				$total	= $block['total'];
				$count	= $block['count'];

				// Get the new total value
				$newData[$key][$uid]['total'] = $model->getCommentCount( $options );

				// ids could be non-existent if the passed in array is empty
				$ids	= array();

				if( array_key_exists( 'ids', $block ) && is_array( $block['ids'] ) )
				{
					$ids = $block['ids'];
				}

				// Limit the count value. Count value that is too large should not proceed because there might be too many comments to check
				if( $count > $updateLimit )
				{
					$options['start'] = $newData[$key][$uid]['total'] - $updateLimit;
					$options['limit'] = $updateLimit;

					$ids = array_slice( $ids, -$updateLimit );
				}

				// incoming count != incoming total and ids is not empty, means there are existing comments, then only pull existing comments to check
				// incoming count == incoming total, then get all the comments to check
				if( $count != $total && !empty( $ids ) )
				{
					$options['commentid'] = $ids[0];
				}

				// Get the comments
				$comments = $model->getComments( $options );

				// Assign the new count value
				$newData[$key][$uid]['count'] = count( $comments );

				// Create an array to keep a copy of the ids
				$newIds = array();

				// Check for newly inserted comments
				foreach( $comments as $comment )
				{
					// Keep a copy of the ids for integrity check later
					$newIds[] = $comment->id;

					// If newId is not in the list of ids, means it is a new comment
					if( !in_array( $comment->id , $ids ) )
					{
						$newData[$key][$uid]['ids'][$comment->id] = $comment->renderHTML();
					}
				}

				// If there are existing comments, check for integrity
				if( !empty( $ids ) )
				{
					foreach( $ids as $id )
					{
						$newData[$key][$uid]['ids'][$id] = true;

						// If the id no longer exist, mark for deletion
						if( !in_array( $id, $newIds ) )
						{
							$newData[$key][$uid]['ids'][$id] = false;
						}
					}
				}
			}
		}

		Foundry::view( 'comments', false )->call( __FUNCTION__, $newData );
	}

	public function getRawComment()
	{
		// Check for request forgeries.
		Foundry::checkToken();

		// Only registered users are allowed here.
		Foundry::requireLogin();

		// Get the view
		$view = Foundry::view( 'comments', false );

		// Check for permission first
		$access = Foundry::access();

		if( !$access->allowed( 'comments.read' ) )
		{
			$view->setMessage( JText::_( 'COM_EASYSOCIAL_COMMENTS_NOT_ALLOWED_TO_READ' ) , SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$id = JRequest::getInt( 'id', 0 );

		$table = Foundry::table( 'comments' );

		$state = $table->load( $id );

		if( !$state )
		{
			$view->setMessage( $table->getError(), SOCIAL_MSG_ERROR );
			return $view->call( __FUNCTION__ );
		}

		$comment = $table->comment;

		$stringLib = Foundry::get( 'string' );

		$comment = $stringLib->escape( $comment );


		$view->call( __FUNCTION__, $comment );
	}
}
