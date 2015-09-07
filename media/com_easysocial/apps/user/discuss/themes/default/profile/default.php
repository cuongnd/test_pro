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
<div class="row-fluid profile-easydiscuss">


	<div class="row-fluid participation-graph">
		<div class="span6">
			<h4><?php echo JText::_( 'APP_DISCUSS_CHART_NEW_DISCUSSIONS' ); ?></h4>

			<span data-discuss-discussions-gravity-chart><?php echo implode( ',' , $stats->creations ); ?></span>
		</div>

		<div class="span6">
			<h4><?php echo JText::_( 'APP_DISCUSS_CHART_REPLIES' ); ?></h4>

			<span data-discuss-replies-gravity-chart><?php echo implode( ',' , $stats->replies ); ?></span>
		</div>
	</div>
	
	<div class="row-fluid stat-meta">
		<div class="span4 stat-item">
			<div class="total-posts">
				<div class="center"><?php echo JText::_( 'APP_DISCUSS_TOTAL_POSTS' ); ?></div>

				<div class="stat-points"><?php echo $totalPosts;?></div>
			</div>
		</div>

		<div class="span4 stat-item">
			<div class="total-replies">
				<div class="center"><?php echo JText::_( 'APP_DISCUSS_TOTAL_REPLIES' ); ?></div>

				<div class="stat-points"><?php echo $totalReplies;?></div>
			</div>
		</div>

		<div class="span4 stat-item">
			<div class="total-votes">
				<div class="center"><?php echo JText::_( 'APP_DISCUSS_VOTES' ); ?></div>

				<div class="stat-points"><?php echo $totalVotes;?></div>
			</div>
		</div>

	</div>


	<?php if( $params->get( 'discuss-recent' , true ) ){ ?>
	<div class="discussions-list">
		<h4><?php echo JText::_( 'APP_DISCUSS_RECENT_DISCUSSIONS' ); ?></h4>

		<?php if( $posts ){ ?>
		<ul class="post-items unstyled">
			<?php foreach( $posts as $post ){ ?>
			<li class="post-item">
				<div class="row-fluid">
					<div class="pull-left">
						<span class="vote-count" 
							data-original-title="<?php echo JText::_( 'APP_DISCUSS_TOTAL_VOTES' );?>" 
							data-es-provide="tooltip"
							data-placement="bottom"
						><?php echo $post->sum_totalvote;?></span>
					</div>

					<div class="post-info">
						<div class="post-title">
							<a href="<?php echo DiscussRouter::getPostRoute( $post->id );?>" class=""><?php echo $post->title ?></a>
						</div>

						<div class="post-meta">
							<?php echo JText::_( 'APP_DISCUSS_IN' );?> <a href="<?php echo DiscussRouter::getCategoryRoute( $post->category_id );?>"><?php echo $post->category;?></a>
						</div>
					</div>
				</div>
			</li>
			<?php } ?>
		</ul>
		<?php } else { ?>
		<div class="empty">
			<?php echo JText::sprintf( 'APP_DISCUSS_EMPTY_POSTS' , $user->getName() ); ?>
		</div>
		<?php } ?>
	</div>
	<?php } ?>

	<?php if( $params->get( 'discuss-participating' , true ) ){ ?>
	<div class="discussions-list">
		<h4><?php echo JText::_( 'APP_DISCUSS_RECENT_PARTICIPATIONS' ); ?></h4>

		<?php if( $recentParticipated ){ ?>
		<ul class="post-items unstyled">
			<?php foreach( $recentParticipated as $post ){ ?>
			<li class="post-item">
				<div class="row-fluid">
					<div class="pull-left">
						<span class="vote-count" 
							data-original-title="<?php echo JText::_( 'APP_DISCUSS_TOTAL_VOTES' );?>" 
							data-es-provide="tooltip"
							data-placement="bottom"
						><?php echo $post->sum_totalvote;?></span>
					</div>

					<div class="post-info">
						<div class="post-title">
							<a href="<?php echo DiscussRouter::getPostRoute( $post->id );?>" class=""><?php echo $post->title ?></a>
						</div>

						<div class="post-meta">
							<?php echo JText::_( 'APP_DISCUSS_IN' );?> <a href="<?php echo DiscussRouter::getCategoryRoute( $post->category_id );?>"><?php echo $post->category;?></a>
						</div>
					</div>
				</div>
			</li>
			<?php } ?>
		</ul>
		<?php } else { ?>
		<div class="empty center">
			<?php echo JText::sprintf( 'APP_DISCUSS_EMPTY_PARTICIPATING' , $user->getName() ); ?>
		</div>
		<?php } ?>
	</div>
	<?php } ?>

	<?php if( $params->get( 'discuss-favourites' , true ) ){ ?>
	<div class="discussions-list">
		<h4><?php echo JText::_( 'APP_DISCUSS_FAVOURITES' ); ?></h4>

		<?php if( $favourites ){ ?>
		<ul class="post-items unstyled">
			<?php foreach( $favourites as $post ){ ?>
			<li class="post-item">
				<div class="row-fluid">
					<div class="pull-left">
						<span class="vote-count" 
							data-original-title="<?php echo JText::_( 'APP_DISCUSS_TOTAL_VOTES' );?>" 
							data-es-provide="tooltip"
							data-placement="bottom"
						><?php echo $post->sum_totalvote;?></span>
					</div>

					<div class="post-info">
						<div class="post-title">
							<a href="<?php echo DiscussRouter::getPostRoute( $post->id );?>" class=""><?php echo $post->title ?></a>
						</div>

						<div class="post-meta">
							<?php echo JText::_( 'APP_DISCUSS_IN' );?> <a href="<?php echo DiscussRouter::getCategoryRoute( $post->category_id );?>"><?php echo $post->category;?></a>
						</div>
					</div>
				</div>
			</li>
			<?php } ?>
		</ul>
		<?php } else { ?>
		<div class="empty center">
			<?php echo JText::sprintf( 'APP_DISCUSS_EMPTY_PARTICIPATING' , $user->getName() ); ?>
		</div>
		<?php } ?>
	</div>
	<?php } ?>

</div>