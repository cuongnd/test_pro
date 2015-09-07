<?php
/**
* @package		%PACKAGE%
* @subpackge	%SUBPACKAGE%
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
*
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

Foundry::import('admin:/includes/apps/apps');

/**
 * Friends application for EasySocial.
 *
 * @since	1.0
 * @author	Jason Rey <jasonrey@stackideas.com>
 */
class SocialUserAppComments extends SocialAppItem
{
	/**
	 * Class constructor.
	 *
	 * @since	1.0
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function exists()
	{
		$file = JPATH_ROOT . '/components/com_komento/helpers/helper.php';

		if (!JFile::exists($file))
		{
			return false;
		}

		require_once($file);

		return true;
	}

	public function onPrepareStream(SocialStreamItem &$item, $includePrivacy = true)
	{
		if ($item->context !== 'komento' || !in_array($item->verb, array('comment', 'reply', 'like')) || $this->exists() === false)
		{
			return;
		}

		$element = $item->context;
		$uid = $item->contextId;

		$my = Foundry::user();

		$privacy = Foundry::privacy($my->id);

		if ($includePrivacy && !$privacy->validate('core.view', $uid, $element, $item->actor->id))
		{
			return;
		}

		$item->display = SOCIAL_STREAM_DISPLAY_FULL;
		$item->color = '#F0786E';

		$comment = Komento::getComment($item->contextId, true);

		if ($item->verb === 'like')
		{
			$item->likes = false;
			$item->comments = false;
		}
		else
		{
			$streamLikes = Foundry::likes();
			$streamLikes->get($item->uid, 'komento');
			$item->likes = $streamLikes;

			$streamComments = Foundry::comments($item->uid, 'komento', SOCIAL_APPS_GROUP_USER, array('url' => FRoute::stream(array('layout' => 'item', 'id' => $item->uid))));
			$item->comments = $streamComments;
		}

		if ($includePrivacy)
		{
			$item->privacy = $privacy->form($uid, $element, $item->actor->id, 'core.view');
		}

		$this->set('actor', $item->actor);
		$this->set('comment', $comment);

		$item->title = parent::display('streams/' . $item->verb . '.title');
		$item->content = parent::display('streams/content');
	}

	public function onAfterCommentSave(&$comment)
	{
		$identifier = explode('.', $comment->element);

		if (empty($identifier[0]) || $identifier[0] !== 'komento' || !$this->exists())
		{
			return;
		}

		// If this comment is injected by Komento, then we don't proceed
		$source = $comment->getParams()->get( 'komento' );
		if( !empty( $source->source ) )
		{
			return;
		}

		// Get the current logged in user
		$my = Foundry::user();

		// Get the actor of the comment
		$actor = Foundry::user($comment->created_by);

		$pid = $comment->uid;

		// Get the parent comment from Komento first
		$parent = Komento::getComment($pid);

		// Set the component for Komento to work properly
		Komento::setCurrentComponent($parent->component);

		$config = Komento::getConfig();

		if( !$config->get( 'enable_easysocial_sync_comment' ) )
		{
			return;
		}

		$obj = Komento::getComment();

		$obj->created_by = $comment->created_by;
		$obj->created = $comment->created;
		$obj->publish_up = $comment->created;
		$obj->comment = $comment->comment;
		$obj->parent_id = $pid;
		$obj->depth = $parent->depth + 1;
		$obj->component = $parent->component;
		$obj->cid = $parent->cid;
		$obj->name = $actor->getName();
		$obj->email = $actor->email;
		$obj->published = SOCIAL_STATE_PUBLISHED;

		// We do this checking because there is a possibility that the comment is added by admin, which is not the comment actor itself
		if( $my->id == $actor->id )
		{
			$obj->ip = JRequest::getVar('REMOTE_ADDR', '', 'SERVER');
		}

		// Set the lft rgt boundary of the comment obj
		$model = Komento::getModel('comments');
		$model->updateCommentLftRgt($obj);

		// Set the extended parameters of the comment in Komento
		$obj->params = new stdClass();
		$obj->params->social = (object) array('source' => $comment->id);
		$url = $comment->getParams()->get('url');
		if (!empty($url))
		{
			$obj->params->social->url = $url;
		}

		$state = $obj->save();

		// We need to inject this data back into Social Comments
		$comment->setParam('komento', (object) array('target' => $obj->id));
		$comment->store();

		// No notification needed because Komento handles that already
	}

	public function onAfterDeleteComment(&$comment)
	{
		$identifier = explode('.', $comment->element);

		if (empty($identifier[0]) || $identifier[0] !== 'komento' || !$this->exists())
		{
			return;
		}

		$params = $comment->getParams()->get('komento');

		if( empty( $params ) )
		{
			return;
		}

		if (!empty($params->target))
		{
			$obj = Komento::getComment($params->target);
			$obj->delete();
		}

		if (!empty($params->source))
		{
			$obj = Komento::getComment($params->source);
			$obj->delete();
		}
	}

	public function onAfterLikeSave(&$likes)
	{
		$identifier = explode('.', $likes->type);

		// TODO: need to check element = comments too to see if the parent stream item is from Komento. If parent stream item is from Komento, then find if this comment exist in Komento, and sync the like over to the child comment

		if (empty($identifier[0]) || $identifier[0] !== 'komento' || !$this->exists())
		{
			return;
		}

		$streamId = $likes->uid;

		$streamTable = Foundry::table('streamitem');
		$state = $streamTable->load(array('uid' => $streamId));

		if($state)
		{
			$pid = $streamTable->context_id;

			$actionTable = Komento::getTable('actions');
			$state = $actionTable->load(array('type' => 'likes', 'comment_id' => $pid, 'action_by' => $likes->created_by));

			if( !$state )
			{
				// Get comment from Komento first
				$comment = Komento::getComment($pid);

				// Set the component for Komento to work properly
				Komento::setCurrentComponent($comment->component);

				$comment->action('add', 'likes', $likes->created_by);
			}
		}
	}

	public function onAfterLikeDelete(&$likes)
	{
		$identifier = explode('.', $likes->type);

		if (empty($identifier[0]) || $identifier[0] !== 'komento' || !$this->exists())
		{
			return;
		}

		$streamId = $likes->uid;

		$streamTable = Foundry::table('streamitem');
		$state = $streamTable->load(array('uid' => $streamId));

		if($state)
		{
			$pid = $streamTable->context_id;

			$actionTable = Komento::getTable('actions');
			$state = $actionTable->load(array('type' => 'likes', 'comment_id' => $pid, 'action_by' => $likes->created_by));

			if( $state )
			{
				// Get comment from Komento first
				$comment = Komento::getComment($pid);

				// Set the component for Komento to work properly
				Komento::setCurrentComponent($comment->component);

				$comment->action('remove', 'likes', $likes->created_by);
			}
		}
	}
}
