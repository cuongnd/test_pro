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
<div class="app-tasks-wrapper" data-tasksApp>

	<div class="row-fluid small filter-tasks mt-10">

		<div class="pull-left">
			<a href="javascript:void(0);" class="btn btn-es-inverse btn-small small" data-tasksApp-create>
				<i class="icon-es-create"></i> <?php echo JText::_( 'APP_TASKS_NEW_TASK_BUTTON' ); ?>
			</a>
		</div>

		<ul class="unstyled inline pull-right">
			<li>
				<?php echo JText::_( 'APP_TASKS_FILTER' ); ?>:
			</li>
			<li data-tasksApp-filter data-filter="all" class="active">
				<a href="javascript:void(0);"><?php echo JText::_( 'APP_TASKS_FILTER_ALL' ); ?></a>
			</li>
			<li data-tasksApp-filter data-filter="resolved">
				<a href="javascript:void(0);"><?php echo JText::_( 'APP_TASKS_FILTER_RESOLVED' ); ?></a>
			</li>
			<li data-tasksApp-filter data-filter="unresolved">
				<a href="javascript:void(0);"><?php echo JText::_( 'APP_TASKS_FILTER_UNRESOLVED' ); ?></a>
			</li>
		</ul>
	</div>
	<hr />


	<ul class="unstyled tasks-list" data-tasksApp-lists>
		<?php if( $tasks ){ ?>
			<?php foreach( $tasks as $task ){ ?>
				<?php echo $this->loadTemplate( 'themes:/apps/user/tasks/default.dashboard.item' , array( 'task' => $task ) ); ?>
			<?php } ?>
		<?php } ?>
	</ul>


</div>
