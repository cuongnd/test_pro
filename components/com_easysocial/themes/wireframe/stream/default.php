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

$isguest = ( isset( $guest ) ) ? $guest : false;

?>
<div class="es-streams <?php echo ( count( $streams) == 0 ) ? ' no-stream' : ''; ?>"
	 data-streams
     data-currentdate="<?php echo Foundry::date()->toMySQL(); ?>"
     data-excludeids=""
>
	<?php if( $view == 'profile' ){ ?>
		<?php echo $this->render( 'module' , 'es-profile-before-story' ); ?>
	<?php } ?>

	<?php if( $view == 'dashboard' ){ ?>
		<?php echo $this->render( 'module' , 'es-dashboard-before-story' ); ?>
	<?php } ?>

	<?php if (!empty($story)) { echo $story->html(); } ?>

	<?php if( $view == 'dashboard' ){ ?>
		<?php echo $this->render( 'module' , 'es-dashboard-after-story' ); ?>
	<?php } ?>

	<?php if( $view == 'profile' ){ ?>
		<?php echo $this->render( 'module' , 'es-profile-after-story' ); ?>
	<?php } ?>

	<!-- Notifications bar -->
	<div data-stream-notification-bar>
	</div>

	<ul class="es-stream-list"
	    data-stream-list>

	<?php if( $streams ){ ?>
		<?php foreach( $streams as $stream ){ ?>
			<?php echo $this->loadTemplate( 'site/stream/default.item' , array( 'stream' => $stream ) ); ?>

			<?php if( $view == 'profile' ){ ?>
				<?php echo $this->render( 'module' , 'es-profile-between-streams' ); ?>
			<?php } ?>

			<?php if( $view == 'dashboard' ){ ?>
				<?php echo $this->render( 'module' , 'es-dashboard-between-streams' ); ?>
			<?php } ?>
		<?php } ?>

		<?php if( $isguest && isset( $nextlimit ) ) { ?>
			<?php if( Foundry::user()->id != 0 ) { ?>
				<li class="pagination" style="border-top: 0px;" data-stream-pagination-guest data-nextlimit="<?php echo $nextlimit; ?>" >
					<div>
						<?php if( $nextlimit ){ ?>
							<a class="btn btn-es-primary btn-stream-updates" href="javascript:void(0);"><i class="ies-refresh"></i>	<?php echo JText::_( 'COM_EASYSOCIAL_STREAM_LOAD_PREVIOUS_STREAM_ITEMS' ); ?></a>
						<?php } ?>
					</div>
				</li>
			<?php } ?>
		<?php } else { ?>
			<li class="pagination" style="border-top: 0px;" data-stream-pagination data-startdate="<?php echo $nextdate; ?>" data-enddate="<?php echo $enddate; ?>" >
				<div>
					<?php if( $nextdate ){ ?>
						<a class="btn btn-es-primary btn-stream-updates" href="javascript:void(0);"><i class="ies-refresh"></i>	<?php echo JText::_( 'COM_EASYSOCIAL_STREAM_LOAD_PREVIOUS_STREAM_ITEMS' ); ?></a>
					<?php } ?>
				</div>
			</li>
		<?php } ?>


	<?php } else { ?>
		<li class="empty center">
			<i class="icon-es-empty-feed mb-10"></i>
			<div><?php echo $empty;?></div>
		</li>
	<?php } ?>

</div>
