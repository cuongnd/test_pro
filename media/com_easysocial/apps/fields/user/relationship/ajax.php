<?php
/*
* @package		EasySocial
* @sub			Text Field
* @copyright	Copyright (C) 2009 - 2011 StackIdeas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'admin:/includes/fields/fields' );

class SocialFieldsUserRelationship extends SocialFieldItem
{
	public function approve()
	{
		$id = JRequest::getInt( 'id' );

		$ajax = Foundry::ajax();

		$user = Foundry::user();

		$relation = $this->table( 'relations' );
		$state = $relation->load( $id );

		if( !$state )
		{
			return $ajax->reject( JText::_( 'PLG_FIELDS_RELATIONSHIP_NOT_FOUND' ) );
		}

		if( !$relation->isTarget() )
		{
			return $ajax->reject( JText::_( 'PLG_FIELDS_RELATIONSHIP_NOT_TARGET_TO_PERFORM_ACTION' ) );
		}

		$state = $relation->approve();

		if( !$state )
		{
			return $ajax->reject( JText::_( 'PLG_FIELDS_RELATIONSHIP_APPROVE_ERROR' ) );
		}

		// User is approving as "target", hence actor is now the opposite for this user
		$target = $relation->getActorUser();

		$data = new stdClass();
		$data->id = $target->id;
		$data->name = $target->getName();
		$data->avatar = $target->getAvatar();
		$data->type = $relation->type;

		return $ajax->resolve( $data );
	}

	public function reject()
	{
		$id = JRequest::getInt( 'id' );

		$ajax = Foundry::ajax();

		$user = Foundry::user();

		$relation = $this->table( 'relations' );
		$state = $relation->load( $id );

		if( !$state )
		{
			return $ajax->reject( JText::_( 'PLG_FIELDS_RELATIONSHIP_NOT_FOUND' ) );
		}

		if( !$relation->isTarget() )
		{
			return $ajax->reject( JText::_( 'PLG_FIELDS_RELATIONSHIP_NOT_TARGET_TO_PERFORM_ACTION' ) );
		}

		$state = $relation->reject();

		if( !$state )
		{
			return $ajax->reject( JText::_( 'PLG_FIELDS_RELATIONSHIP_REJECT_ERROR' ) );
		}

		return $ajax->resolve();
	}

	public function delete()
	{
		$ajax = Foundry::ajax();

		$user = Foundry::user();

		$model = $this->model( 'relations' );

		$relation = $model->getRelationship( $user->id );

		if( !$relation )
		{
			return $ajax->reject( JText::_( 'PLG_FIELDS_RELATIONSHIP_NOT_FOUND' ) );
		}

		if( !$relation->isActor() )
		{
			return $this->reject();
		}

		$state = $relation->remove();

		if( !$state )
		{
			return $ajax->reject( JText::_( 'PLG_FIELDS_RELATIONSHIP_DELETE_ERROR' ) );
		}

		return $ajax->resolve();
	}
}
