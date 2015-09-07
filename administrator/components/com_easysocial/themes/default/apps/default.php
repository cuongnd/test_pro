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
<form action="index.php" method="post" name="adminForm" class="esForm" id="adminForm" data-table-grid>

	<div class="row-fluid filter-bar">
		<div class="span12">

			<div class="form-inline full-width">
				<span class="mr-20">
					<strong><?php echo JText::_( 'COM_EASYSOCIAL_SEARCH' ); ?> :</strong>

					<?php echo $this->html( 'filter.search' , $search ); ?>

				</span>

				<strong><?php echo JText::_( 'COM_EASYSOCIAL_FILTER_BY' ); ?> :</strong>

				<?php echo $this->html( 'filter.published' , 'state' , $state ); ?>

				<select name="filter" class="select mr-5" data-table-grid-filter>
					<option value="all"<?php echo $filter == 'all' ? ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYSOCIAL_GRID_SELECT_TYPE' );?></option>
					<option value="fields"<?php echo $filter == 'fields' ? ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYSOCIAL_GRID_FILTER_TYPE_FIELDS' );?></option>
					<option value="apps"<?php echo $filter == 'apps' ? ' selected="selected"' : '';?>><?php echo JText::_( 'COM_EASYSOCIAL_GRID_FILTER_TYPE_APPS' );?></option>
				</select>

				<div class="pull-right">
					<?php echo $this->html( 'filter.limit' , $limit ); ?>
				</div>
			</div>
		</div>
	</div>

	<div id="appsTable">

		<table class="table table-striped table-es table-hover">
			<thead>
				<tr>
					<th width="1%" class="center">
						<input type="checkbox" name="toggle" data-table-grid-checkall />
					</th>

					<th style="text-align: left;">
						<?php echo $this->html( 'grid.sort' , 'title' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_TITLE' ) , $ordering , $direction ); ?>
					</th>

					<th class="center" width="5%">
						<?php echo $this->html( 'grid.sort' , 'state' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_STATUS' ) , $ordering , $direction ); ?>
					</th>

					<th class="center" width="10%">
						<?php echo $this->html( 'grid.sort' , 'type' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_TYPE' ) , $ordering , $direction ); ?>
					</th>

					<th class="center" width="10%">
						<?php echo $this->html( 'grid.sort' , 'group' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_GROUP' ) , $ordering , $direction ); ?>
					</th>

					<th width="10%" class="center">
						<?php echo $this->html( 'grid.sort' , 'created' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_CREATED' ) , $ordering , $direction ); ?>
					</th>

					<th width="5%" class="center">
						<?php echo $this->html( 'grid.sort' , 'id' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_ID' ) , $ordering , $direction ); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php if( $apps ){ ?>
					<?php $i = 0; ?>
					<?php foreach( $apps as $app ){ ?>
					<tr class="<?php echo $app->state ? '' : 'error';?>">
						<td class="center">
							<?php echo $this->html( 'grid.id' , $i++ , $app->id ); ?>
						</td>

						<td>
							<div class="row-fluid">
								<div class="pull-left mr-10">
									<img src="<?php echo $app->getIcon( 'large' ); ?>" width="24" />
								</div>

								<a href="<?php echo FRoute::_( 'index.php?option=com_easysocial&view=apps&layout=form&id=' . $app->id );?>">
									<?php echo $app->get( 'title' ); ?>
								</a>
								<div class="small">
									<?php echo JText::_( 'COM_EASYSOCIAL_APPS_AUTHOR' );?>: <strong><?php echo $app->getMeta()->author;?></strong>
									&bull;
									<?php echo JText::_( 'COM_EASYSOCIAL_APPS_VERSION' );?>: <strong><?php echo $app->version ? $app->version : JText::_( 'COM_EASYSOCIAL_NOT_AVAILABLE_SYMBOL' ) ; ?></strong>
								</div>
							</div>
						</td>

						<td class="center">
							<?php echo $this->html( 'grid.published' , $app , 'apps' ); ?>
						</td>

						<td class="center">
							<?php echo JText::_( 'COM_EASYSOCIAL_APPS_TYPE_' . strtoupper( $app->type ) ); ?>
						</td>

						<td class="center">
							<?php echo $app->group; ?>
						</td>

						<td class="center">
							<?php echo $app->created; ?>
						</td>

						<td class="center">
							<?php echo $app->id;?>
						</td>
					</tr>
					<?php } ?>
				<?php } else { ?>
					<tr>
						<td colspan="8" class="center empty">
							<?php echo JText::_( 'COM_EASYSOCIAL_APPS_NO_APPS_FOUND' );?>
						</td>
					</tr>
				<?php } ?>
			</tbody>

			<tfoot>
				<tr>
					<td colspan="8" class="center">
						<div class="footer-pagination"><?php echo $pagination->getListFooter(); ?></div>
					</td>
				</tr>
			</tfoot>

		</table>
	</div>

	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="ordering" value="<?php echo $ordering;?>" data-table-grid-ordering />
	<input type="hidden" name="direction" value="<?php echo $direction;?>" data-table-grid-direction />
	<input type="hidden" name="boxchecked" value="0" data-table-grid-box-checked />
	<input type="hidden" name="task" value="" data-table-grid-task />
	<input type="hidden" name="option" value="com_easysocial" />
	<input type="hidden" name="view" value="apps" />
	<input type="hidden" name="controller" value="apps" />
</form>
