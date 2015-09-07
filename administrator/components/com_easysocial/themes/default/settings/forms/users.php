<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

	$displayOptions 	= array(
								$settings->makeOption( 'Username' , 'username' ),
								$settings->makeOption( 'Real Name' , 'realname' ),
								'help' => true
							);

	$deleteOptions 		= array(
								$settings->makeOption( 'Delete Immediately And Notify Admin' , 'delete' ),
								$settings->makeOption( 'Unpublish Account And Notify Admin' , 'unpublish' ),
								'help' => true,
								'class' => 'input-xlarge'
							);

	$startItem 			= array(
								$settings->makeOption( 'Me And Friends' , 'me' ),
								$settings->makeOption( 'Everyone' , 'everyone' ),
								$settings->makeOption( 'Following' , 'following' ),
								'help' => true,
								'class' => 'input-xlarge'
							);

	$logoutMenus 	= $this->html( 'form.menus' , 'general.site.logout' , $this->config->get( 'general.site.logout' ) );
	$loginMenus 	= $this->html( 'form.menus' , 'general.site.login' , $this->config->get( 'general.site.login' ) );


	echo $settings->renderPage(
			$settings->renderColumn(
				$settings->renderSection(
					$settings->renderHeader( 'Display Options' ),
					$settings->renderSetting( 'Display name format' , 'users.displayName' , 'list' , $displayOptions ),
					$settings->renderSetting( 'Permalink format' , 'users.aliasName' , 'list' , $displayOptions )
				),
				$settings->renderSection(
					$settings->renderHeader( 'Authentication' ),
					$settings->renderSetting( 'Allow Login With Email', 'general.site.loginemail' , 'boolean' , array( 'help' => true , 'info' => true ) ),
					$settings->renderSetting( 'Login Redirection', 'general.site.login' , 'custom' , array( 'help' => true , 'field' => $loginMenus ) ),
					$settings->renderSetting( 'Logout Redirection', 'general.site.logout' , 'custom' , array( 'help' => true , 'field' => $logoutMenus ) )
				),
				$settings->renderSection(
					$settings->renderHeader( 'User Deletion' ),
					$settings->renderSetting( 'Account Deletion Workflow' , 'users.deleteLogic' , 'list' , $deleteOptions )
				),
				$settings->renderSection(
					$settings->renderHeader( 'User Indexing' ),
					$settings->renderSetting( 'Name indexing format' , 'users.indexer.name' , 'list' , $displayOptions ),
					$settings->renderSetting( 'Index Email' , 'users.indexer.email' , 'boolean' , array( 'help' => true ) )
				)
			),

			$settings->renderColumn(
				$settings->renderSection(
					$settings->renderHeader( 'Dashboard Behavior' ),
					$settings->renderSetting( 'Default Start Item' , 'users.dashboard.start' , 'list' , $startItem )
				),
				$settings->renderSection(
					$settings->renderHeader( 'Activity Stream' ),
					$settings->renderSetting( 'Add login' , 'users.stream.login' , 'boolean' , array( 'help' => true ) )
				),
				$settings->renderSection(
					$settings->renderHeader( 'User Listings' ),
					$settings->renderSetting( 'Include Site Administrators' , 'users.listings.admin' , 'boolean' , array( 'help' => true ) )
				),
				$settings->renderSection(
					$settings->renderHeader( 'Leaderboard Listings' ),
					$settings->renderSetting( 'Include Site Administrators in Leaderboard' , 'leaderboard.listings.admin' , 'boolean' , array( 'help' => true ) )
				)
			)
		);
