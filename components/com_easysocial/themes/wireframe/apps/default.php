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
<div class="es-container" data-apps>
	<a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
		<i class="ies-grid-view ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_SIDEBAR_TOGGLE' );?>
	</a>
	<div class="es-sidebar" data-sidebar>
		<?php echo $this->render( 'module' , 'es-apps-sidebar-top' ); ?>

		<div class="es-widget es-widget-borderless">
			<div class="es-widget-head"><?php echo JText::_( 'COM_EASYSOCIAL_APPS' );?></div>

			<div class="es-widget-body">
				<ul class="es-nav es-nav-stacked">
					<li class="apps-filter-item<?php echo $filter == 'browse' ? ' active' :'';?>"
						data-apps-title="<?php echo JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_BROWSE_APPS' , true );?>"
						data-apps-filter
						data-apps-filter-type="browse"
						data-apps-filter-url="<?php echo FRoute::apps();?>">
						<a href="javascript:void(0);">
							<?php echo JText::_( 'COM_EASYSOCIAL_APPS_BROWSE_APPS' );?>
						</a>
					</li>
					<li class="apps-filter-item<?php echo $filter == 'mine' ? ' active' :'';?>"
						data-apps-title="<?php echo JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_YOUR_APPS' , true );?>"
						data-apps-filter
						data-apps-filter-type="mine"
						data-apps-filter-url="<?php echo FRoute::apps( array( 'filter' => 'mine' ) );?>">
						<a href="javascript:void(0);">
							<?php echo JText::_( 'COM_EASYSOCIAL_APPS_YOUR_APPS' );?>
						</a>
					</li>

				</ul>

			</div>
		</div>

		<?php echo $this->render( 'module' , 'es-apps-sidebar-bottom' ); ?>
	</div>

	<div class="es-content">

		<?php echo $this->render( 'module' , 'es-apps-before-contents' ); ?>

		<div class="pl-10 pt-10">
			<div class="row-fluid">
				<div class="pull-left">

					<?php if( $filter == 'mine' ){ ?>
					<h5 data-page-apps-title><?php echo JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_YOUR_APPS' ); ?></h5>
					<?php } else { ?>
					<h5 data-page-apps-title><?php echo JText::_( 'COM_EASYSOCIAL_PAGE_TITLE_BROWSE_APPS' ); ?></h5>
					<?php }?>
				</div>

				<?php if( $this->template->get( 'apps_sorting' ) ){ ?>
				<div class="btn-group btn-group-view-apps pull-right" data-apps-sorting style="<?php echo $filter == 'mine' ? 'display: none;' : '';?>">
					<a class="btn btn-small alphabetical<?php echo $sort == 'alphabetical' ? ' active' : '';?>"
						data-apps-sort
						data-apps-sort-type="alphabetical"
						data-apps-sort-url="<?php echo FRoute::apps( array( 'sort' => 'alphabetical' ) );?>"
						data-es-provide="tooltip"
						data-placement="bottom"
						data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_APPS_SORT_ALPHABETICALLY' , true );?>"
					>
						<i class="ies-bars ies-small"></i>
					</a>
					<a class="btn btn-small recent<?php echo $sort == 'recent' ? ' active' : '';?>"
						data-apps-sort
						data-apps-sort-type="recent"
						data-apps-sort-url="<?php echo FRoute::apps( array( 'sort' => 'recent' ) );?>"
						data-es-provide="tooltip"
						data-placement="bottom"
						data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_APPS_SORT_RECENT_ADDED' , true );?>"
					>
						<i class="ies-upload-2 ies-small"></i>
					</a>
					<a class="btn btn-small trending<?php echo $sort == 'trending' ? ' active' : '';?>"
						data-apps-sort
						data-apps-sort-type="trending"
						data-apps-sort-url="<?php echo FRoute::apps( array( 'sort' => 'trending' ) );?>"
						data-es-provide="tooltip"
						data-placement="bottom"
						data-original-title="<?php echo JText::_( 'COM_EASYSOCIAL_APPS_SORT_TRENDING_APPS' , true );?>"
					>
						<i class="ies-fire ies-small"></i>
					</a>
				</div>
				<?php } ?>
			</div>

			<hr />

			<ul class="unstyled es-apps-grid mt-10" data-apps-listing>
				<?php if( $apps ){ ?>
					<?php foreach( $apps as $app ){ ?>
						<?php echo $this->loadTemplate( 'site/apps/default.item' , array( 'app' => $app ) ); ?>
					<?php } ?>
				<?php } else { ?>
					<?php echo $this->loadTemplate( 'site/apps/default.empty' ); ?>
				<?php } ?>
			</ul>
		</div>

		<?php echo $this->render( 'module' , 'es-apps-after-contents' ); ?>

	</div>
</div>
