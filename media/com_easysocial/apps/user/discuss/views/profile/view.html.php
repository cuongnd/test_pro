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

/**
 *
 * @since	1.0
 * @access	public
 */
class DiscussViewProfile extends SocialAppsView
{
	/**
	 * Determines if EasyDiscuss is installed on the site
	 *
	 * @since	1.0
	 * @access	public
	 * @return	bool	True or false
	 */
	public function exists()
	{
		$path 	= JPATH_ROOT . '/components/com_easydiscuss/helpers/helper.php';

		if( !JFile::exists( $path ) )
		{
			return false;
		}

		require_once( $path );

		return true;
	}

	/**
	 * Displays the application output in the profile.
	 *
	 * @since	1.0
	 * @access	public
	 * @param	int		The user id that is currently being viewed.
	 */
	public function display( $userId = null , $docType = null )
	{
		$user	=  Foundry::user($userId);
		$my		=  Foundry::user();

		// Get the app's model
		$model 	= $this->getModel( 'Discuss' );

		// Get statistics
		$stats 			= $model->getUserStats( $userId );

		// Get the params
		$params 		= $this->getUserParams( $userId );

		$limitPosts   	= (int) $params->get( 'discuss-recent-limit' , 5 );
		$limitReplies 	= (int) $params->get( 'discuss-participating-limit' , 5 );

		// Total vote casted
		$voteModel 		= DiscussHelper::getModel( 'Votes' );
		$totalVotes 	= $model->getTotalUserVotes( $user->id );

		// Get recent new post created
		$postsModel		= DiscussHelper::getModel( 'Posts' );
		$posts 			= $postsModel->getPostsBy( 'user', $user->id, 'latest', null, 'published', '', $limitPosts );

		// Format discussions
		$posts 			= DiscussHelper::formatPost( $posts , false , true );

		// Total count of new post created
		$totalNewPosts = count( $posts );

		// Get the list of post of user recently participated
		$recentParticipated = $postsModel->getRepliesFromUser( $user->id );
		$recentParticipated = array_slice( $recentParticipated, 0 , $limitReplies );

		$totalRepliesCount = $model->getTotalUserReplies( $user->id );

		// Get favourite items
		$favourites	 	= $postsModel->getData( 'true', 'latest', 'null', 'favourites' );
		$favourites 	= DiscussHelper::formatPost( $favourites );

		$this->set( 'favourites'		, $favourites );
		$this->set( 'stats'				, $stats );
		$this->set( 'params'			, $params );
		$this->set( 'user'				 , $user );
		$this->set( 'totalVotes' 		 , $totalVotes );
		$this->set( 'totalPosts' 		 , $totalNewPosts );
		$this->set( 'totalReplies' 		 , $totalRepliesCount );
		$this->set( 'posts'				 , $posts );
		$this->set( 'recentParticipated' , $recentParticipated );

		echo parent::display( 'profile/default' );
	}
}
