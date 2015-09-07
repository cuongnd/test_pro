<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2009 - 2011 StackIdeas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'admin:/includes/fields/dependencies' );

class SocialFieldTableUserRelations extends JTable
{
	public $id			= null;
	public $actor		= null;
	public $target		= null;
	public $type		= null;
	public $state		= null;
	public $created		= null;

	// Extended data
	public $typeInfo	= null;

	public function __construct(& $db )
	{
		parent::__construct( '#__social_relationship_status' , 'id' , $db );
	}

	public function store( $updateNulls = false )
	{
		$db	= Foundry::db();

		if( is_null( $this->created ) )
		{
			$this->created	= Foundry::date()->toSql();
		}

		return parent::store( $updateNulls );
	}

	public function isActor( $userid = null )
	{
		if( is_null( $userid ) )
		{
			$userid = Foundry::user()->id;
		}

		return $this->actor == $userid;
	}

	public function isTarget( $userid = null )
	{
		if( is_null( $userid ) )
		{
			$userid = Foundry::user()->id;
		}

		return $this->target == $userid;
	}

	public function getActorUser()
	{
		if( empty( $this->actor ) )
		{
			return Foundry::user();
		}

		return Foundry::user( $this->actor );
	}

	public function getTargetUser()
	{
		return Foundry::user( $this->target );
	}

	public function getOppositeUser( $userid = null )
	{
		$oppositeId = $this->isActor() ? $this->target : $this->actor;

		if( empty( $oppositeId ) )
		{
			return false;
		}

		$oppositeUser = Foundry::user( $oppositeId );

		return $oppositeUser;
	}

	public function isPending()
	{
		return $this->state == 0;
	}

	public function isApproved()
	{
		return !$this->isPending();
	}

	public function isConnect()
	{
		$type = $this->getType();

		if( !$type )
		{
			return false;
		}

		return $type->connect;
	}

	public function getLabel()
	{
		$type = $this->getType();

		if( !$type )
		{
			return false;
		}

		return $type->label;
	}

	public function getConnectWord()
	{
		$type = $this->getType();

		if( !$type )
		{
			return false;
		}

		return $type->connectword;
	}

	public function getOppositeTable()
	{
		if( !$this->isConnect() || empty( $this->actor ) || empty( $this->target ) )
		{
			return false;
		}

		$table = JTable::getInstance( 'relations', 'SocialFieldTableUser' );
		$table->load( array( 'actor' => $this->target, 'target' => $this->actor ) );

		return $table;
	}

	public function request()
	{
		$this->state = $this->isConnect() && !empty( $this->target ) ? 0 : 1;

		$state = $this->store();

		if( !$state )
		{
			return false;
		}

		if( !$this->state )
		{
			$actor = Foundry::user( $this->actor );
			$target = Foundry::user( $this->target );

			$emailOptions = array(
				'title'		=> JText::sprintf( 'PLG_FIELDS_RELATIONSHIP_EMAIL_REQUEST_TITLE_' . strtoupper( $this->type ) . '_REQUEST', $actor->getName() ),
				'template'	=> 'fields/user/relationship/request',
				'params'	=> array(
					'posterName' => $actor->getName(),
					'posterAvatar' => $actor->getAvatar(),
					'posterLink' => $actor->getPermalink(),
					'recipientName' => $target->getName(),
					'type' => JText::_( 'PLG_FIELDS_RELATIONSHIP_' . strtoupper( $this->type ) ),
					'connect' => JText::_( 'PLG_FIELDS_RELATIONSHIP_CONNECT_WORD_' . strtoupper( $this->type ) ),
					'link' => FRoute::profile( array( 'layout' => 'edit' ) )
				)
			);

			$systemOptions = array(
				'uid'		=> $this->id,
				'actor_id'	=> $this->actor,
				'type'		=> 'relationship',
				'title'		=> JText::_( 'PLG_FIELDS_RELATIONSHIP_SYSTEM_TITLE_' . strtoupper( $this->type ) . '_REQUEST' ),
				'url'		=> FRoute::profile( array( 'layout' => 'edit' ) ),
				'image'		=> $actor->getAvatar( SOCIAL_AVATAR_LARGE )
			);

			Foundry::notify( 'relationship.request', array( $this->target ), $emailOptions, $systemOptions );
		}

		return true;

	}

	public function approve()
	{
		$this->state = 1;

		$state = $this->store();

		if( !$state )
		{
			return false;
		}

		// After approval, we need to clear relationship status for target user as an actor
		$db = Foundry::db();
		$sql = $db->sql();

		$sql->delete( $this->getTableName() )
			->where( 'actor', $this->target );

		$db->setQuery( $sql );
		$db->query();

		// Then we need clear all other requests by other people
		$sql->clear();
		$sql->update( $this->getTableName() )
			->set( 'target', 0 )
			->set( 'state', 1 )
			->where( 'actor', $this->actor, '<>' )
			->where( 'target', $this->target );

		$db->setQuery( $sql );
		$db->query();

		// After clearing all other relationship status, we need to create the same relationship status for target user as an actor
		$table = JTable::getInstance( 'relations', 'SocialFieldTableUser' );
		$table->actor = $this->target;
		$table->target = $this->actor;
		$table->state = 1;
		$table->type = $this->type;

		$table->store();

		// Send notification here

		return true;
	}

	public function reject()
	{
		$this->target = 0;
		$this->state = 1;

		$this->store();

		// Send notification here

		return true;
	}

	public function remove()
	{
		$state = $this->delete();

		if( !$state )
		{
			return false;
		}

		// Send notification here

		return true;
	}

	private function getApp()
	{
		$table = Foundry::table( 'app' );
		$state = $table->loadByElement( 'relationship', SOCIAL_APPS_GROUP_USER, SOCIAL_APPS_TYPE_FIELDS );

		if( !$state )
		{
			return false;
		}

		return $table;
	}

	private function getType()
	{
		static $types = null;

		if( is_null( $types ) )
		{
			$app = $this->getApp();

			if( !$app )
			{
				return false;
			}

			$types = $app->getManifest( 'config' )->relationshiptype->option;

			foreach( $types as &$type )
			{
				$type->label = JText::_( $type->label );
				$type->connectword = JText::_( 'PLG_FIELDS_RELATIONSHIP_CONNECT_WORD_' . strtoupper( $type->value ) );
			}
		}

		if( !isset( $this->typeInfo ) )
		{
			$this->typeInfo = false;

			foreach( $types as $type )
			{
				if( $this->type == $type->value )
				{
					$this->typeInfo = $type;
					break;
				}
			}
		}

		return $this->typeInfo;
	}
}
