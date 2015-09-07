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
?>
<div class="es-container" data-users>
	<a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
		<i class="ies-grid-view ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_SIDEBAR_TOGGLE' );?>
	</a>
	<div class="es-sidebar" data-sidebar>
		<?php echo $this->render( 'module' , 'es-users-sidebar-top' ); ?>

		<div class="es-widget es-widget-borderless">
			<div class="es-widget-body">

				<h5><?php echo JText::_( 'COM_EASYSOCIAL_USERS' );?></h5>

				<hr />

				<ul class="es-nav es-nav-stacked">
					<li class="<?php echo !$filter || $filter == 'all' ? 'active' : '';?>">
						<a href="<?php echo FRoute::users();?>"
						data-users-filter
						data-filter="all"
						data-url=""
						>
							<?php echo JText::_( 'COM_EASYSOCIAL_USERS_FILTER_USERS_ALL_USERS' );?>
						</a>
					</li>
					<li class="<?php echo $filter == 'photos' ? 'active' : '';?>">
						<a href="<?php echo FRoute::users( array( 'filter' => 'photos' ) );?>"
						data-users-filter
						data-filter="photos"
						>
							<?php echo JText::_( 'COM_EASYSOCIAL_USERS_FILTER_USERS_WITH_PHOTOS' );?>
						</a>
					</li>
					<li class="<?php echo $filter == 'online' ? 'active' : '';?>">
						<a href="<?php echo FRoute::users( array( 'filter' => 'online' ) );?>"
						data-users-filter
						data-filter="online"
						>
							<?php echo JText::_( 'COM_EASYSOCIAL_USERS_FILTER_ONLINE_USERS' );?>
						</a>
					</li>
				</ul>

			</div>
		</div>

		<?php echo $this->render( 'module' , 'es-users-sidebar-bottom' ); ?>

	</div>

	<div class="es-content">

		<?php echo $this->render( 'module' , 'es-users-before-contents' ); ?>

		<div data-users-content>
			<?php echo $this->loadTemplate( 'site/users/default.list' , array( 'users' => $users , 'filter' => $filter , 'sort' => $sort , 'isSort' => false ) ); ?>

			<div class="es-pagination-footer" data-users-pagination>
				<?php echo $pagination->getListFooter( 'site' );?>
			</div>
		</div>

		<?php echo $this->render( 'module' , 'es-users-after-contents' ); ?>
	</div>


</div>
