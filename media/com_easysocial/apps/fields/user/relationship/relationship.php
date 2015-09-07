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

Foundry::import( 'admin:/includes/fields/fields' );

class SocialFieldsUserRelationship extends SocialFieldItem
{
	/*
	 * Provide boolean status for the result
	 */
	public function onValidate( $post )
	{
	    return true;
	}

	/*
	 * Responsible to output the html codes that is displayed to
	 * a user when they edit their profile.
	 *
	 * @param
	 */
	public function onEdit( &$post, &$user, $errors )
	{
		if( !$user->id ) {
			return false;
		}

		$this->set( 'user', $user );

		$model = $this->model( 'relations' );

		$relation = $model->getActorRelationship( $user->id );

		$targetted = $model->getTargetRelationship( $user->id, array( 'state' => 0 ) );

		$this->set( 'relation', $relation );

		$this->set( 'pending', $targetted );

		$types = $this->getRelationshipTypes();

		$firstType = '';

		foreach( $types as $type )
		{
			$firstType = $type;
			break;
		}

		$this->set( 'firstType', $firstType );

		$this->set( 'types', $types );

		return $this->display();
	}

	/*
	 * Save trigger which is called after really saving the object.
	 */
	public function onEditAfterSave( &$post, &$user )
	{
		$json = Foundry::json();

		$params = $json->decode( $post[$this->inputName] );

		if( !isset( $params->type ) )
		{
			return false;
		}

		if( !isset( $params->target ) )
		{
			$params->target = array(0);
		}

		$model = $this->model( 'relations' );

		$relation = $model->getActorRelationship( $user->id );

		$origType = $relation ? $relation->type : '';
		$origTarget = $relation ? $relation->getTargetUser()->id : 0;

		if( $relation === false )
		{
			$relation = $this->table( 'relations' );
		}

		$relation->actor = $user->id;
		$relation->type = $params->type;
		$relation->target = $relation->isConnect() ? $params->target[0] : 0;

		// Check if there is a change in type or target
		if( $origType !== $relation->type || $origTarget !== $relation->target )
		{
			// If original target is not empty, we need to find the target's relationship and change it
			if( !empty( $origTarget ) )
			{
				$targetRel = $model->getActorRelationship( $origTarget, array( 'target' => $user->id ) );

				if( $targetRel )
				{
					$targetRel->target = 0;
					$targetRel->state = 1;
					$targetRel->store();
				}
			}

			$state = $relation->request();
		}
		else
		{
			$state = $relation->store();
		}

		if( !$state )
		{
			return false;
		}

		return true;
	}

	/*
	 * Responsible to output the html codes that is displayed to
	 * a user when they edit their profile.
	 *
	 * @param
	 */
	public function onDisplay( $user )
	{
		if( !$this->allowedPrivacy( $user ) )
		{
			return;
		}

		$model = $this->model( 'relations' );
		$relation = $model->getActorRelationship( $user->id );

		if( !$relation )
		{
			return;
		}

		$this->set( 'relation', $relation );

		return $this->display( 'display' );
	}

	public function onSample()
	{
		$types = $this->getRelationshipTypes();

		$this->set( 'types', $types );

		return $this->display();
	}

	private function getRelationshipTypes()
	{
		// get all the relationship types and key it with the name
		$allowedTypes = $this->params->get( 'relationshiptype', array() );
		$types = $this->field->getApp()->getManifest( 'config' )->relationshiptype->option;

		$result = array();

		foreach( $types as $type )
		{
			if( empty( $allowedTypes ) || ( !empty( $allowedTypes ) && in_array( $type->value, $allowedTypes ) ) )
			{
				$type->label = JText::_( $type->label );
				$type->connectword = JText::_( 'PLG_FIELDS_RELATIONSHIP_CONNECT_WORD_' . strtoupper( $type->value ) );

				$result[$type->value] = $type;
			}
		}

		return $result;
	}

	public function onOAuthGetUserPermission( &$permissions )
	{
		$permissions[] = 'user_relationships';
	}

	public function onOAuthGetMetaFields( &$fields )
	{
		$fields[] = 'relationship_status';
	}
}
