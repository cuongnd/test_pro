<?php
/**
* @package		Social
* @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

Foundry::import( 'site:/views/views' );

class EasySocialViewShare extends EasySocialSiteView
{
	/**
	 * type : 'profile' == profile status,
	 * content : shared content.
	 */

	public function add()
	{
		$type     = JRequest::getString( 'type' );
		$content  = JRequest::getVar( 'text' );
		
		$my         = Foundry::get( 'People' );
		$streamId   = '';
		
		switch($type)
		{
		    case 'profile':
		        $data   = array();
				$data['actor_node_id'] 	= $my->get('node_id');
				$data['node_id'] 		= '1';
				$data['content'] 		= $content;
				
				$storyTbl   = Foundry::table('Story');
				$storyTbl->bind($data);
				$storyTbl->store();
		    
		        $streamId   = $storyTbl->streamId;
		        
		        if( !empty($streamId) )
		        {
		            $story	= Foundry::get( 'Stream' )->get('people', '', '', false, $streamId);
		            Foundry::get( 'AJAX' )->success( $story[0] );
		            return;
		        }
		    
				break;
			default:
			    break;
		}

		Foundry::get( 'AJAX' )->success();
	}
}
