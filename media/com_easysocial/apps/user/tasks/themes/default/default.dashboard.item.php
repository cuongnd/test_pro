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
<li class="taskItem all<?php echo $task->state == 0 ? ' unresolved' : ' resolved';?>" data-tasksApp-item data-id="<?php echo $task->id;?>">
	<div class="row-fluid">
		<div class="pull-left">
			<input type="checkbox" class="mr-10" id="task-<?php echo $task->id;?>" data-taskItem-checkbox <?php echo $task->state == 1 ? 'checked="checked" ' : '';?>/>

			<label for="task-<?php echo $task->id;?>" style="display:inline;"><?php echo $task->get( 'title' ); ?></label>
			
			<span class="btn-group">
				<a href="javascript:void(0);" data-foundry-toggle="dropdown" class="dropdown-toggle_ btn btn-dropdown">
					<i class="icon-es-dropdown"></i>
				</a>
				<ul class="dropdown-menu dropdown-menu-user messageDropDown">
					<li>
						<a href="javascript:void(0);" data-taskItem-remove><?php echo JText::_( 'APP_TASKS_REMOVE_ITEM' );?></a>
					</li>
				</ul>
			</span>
		</div>
	
		<div class="pull-right small hello">
			<i class="ies-clock ies-small"></i> <?php echo Foundry::date( $task->created )->toLapsed(); ?>
		</div>
	</div>
</li>
