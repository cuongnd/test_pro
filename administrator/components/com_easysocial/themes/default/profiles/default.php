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

	<div class="row-fluid filter-bar<?php echo $callback ? ' mt-20' : '';?>">
		<div class="span12">

			<div class="form-inline full-width">
				<span class="mr-20">
					<strong><?php echo JText::_( 'COM_EASYSOCIAL_SEARCH' ); ?> :</strong>
					<span class="input-append">
						<input type="text" class="input-large" name="search" value="<?php echo $this->html( 'string.escape' , $search );?>" data-table-grid-search-input />
						<button class="btn btn-es" data-table-grid-search><i class="ies-search ies-small"></i></button>
						<button class="btn btn-es" data-table-grid-search-reset><i class="ies-cancel ies-small"></i></button>
					</span>
				</span>

				<?php if( !$callback ){ ?>
				<strong><?php echo JText::_( 'COM_EASYSOCIAL_FILTER_BY' ); ?> :</strong>

				<?php echo $this->html( 'filter.published' , 'state' , $state ); ?>

				<div class="pull-right">
					<?php echo $this->html( 'filter.limit' , $limit ); ?>
				</div>
				<?php } ?>

			</div>
		</div>
	</div>

	<?php if( $orphanCount ) { ?>
		<div class="mt-20 small">
			<span class="label label-warning small"><?php echo JText::_( 'COM_EASYSOCIAL_FOOTPRINT_WARNING' );?>:</span>
			<?php echo JText::sprintf( 'COM_EASYSOCIAL_PROFILES_ORPHAN_ITEMS_NOTICE', $orphanCount );?>
		</div>
		<br />
	<?php } ?>

	<div id="profilesTable" data-profiles>

		<table class="table table-striped table-es table-hover">
			<thead>
				<tr>
					<?php if( !$callback ){ ?>
					<th width="1%" class="center">
						<input type="checkbox" name="toggle" data-table-grid-checkall />
					</th>
					<?php } ?>

					<th style="text-align: left;">
						<?php echo $this->html( 'grid.sort' , 'title' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_TITLE' ) , $ordering , $direction ); ?>
					</th>

					<?php if( !$callback ){ ?>
					<th class="center" width="5%">
						<?php echo $this->html( 'grid.sort' , 'default' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_DEFAULT' ) , $ordering , $direction ); ?>
					</th>

					<th class="center" width="5%">
						<?php echo $this->html( 'grid.sort' , 'state' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_STATUS' ) , $ordering , $direction ); ?>
					</th>
					<?php } ?>

					<th width="5%" class="center">
						<?php echo JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_USERS' ); ?>
					</th>

					<?php if( !$callback ){ ?>
					<th class="center" width="10%">
						<?php echo $this->html( 'grid.sort' , 'ordering' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_ORDERING' ) , $ordering , $direction ); ?>
					</th>
					<th width="10%" class="center">
						<?php echo $this->html( 'grid.sort' , 'created' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_CREATED' ) , $ordering , $direction ); ?>
					</th>
					<?php } ?>

					<th width="<?php echo $callback ? '10%' : '5%';?>" class="center">
						<?php echo $this->html( 'grid.sort' , 'id' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_ID' ) , $ordering , $direction ); ?>
					</th>
				</tr>
			</thead>
			<tbody>

				<?php if( $profiles ){ ?>
					<?php $i = 0; ?>
					<?php foreach( $profiles as $profile ){ ?>
					<tr class="row<?php echo $i; ?>"
						data-profiles-item
						data-grid-row
						data-title="<?php echo $this->html( 'string.escape' , $profile->get( 'title' ) );?>"
						data-id="<?php echo $profile->id;?>"
					>
						<?php if( !$callback ){ ?>
						<td align="center">
							<?php echo $this->html( 'grid.id' , $i , $profile->id ); ?>
						</td>
						<?php } ?>

						<td>
							<a href="<?php echo FRoute::_( 'index.php?option=com_easysocial&view=profiles&layout=form&id=' . $profile->id );?>" data-profile-insert data-id="<?php echo $profile->id;?>"><?php echo $profile->get( 'title' ); ?></a>
						</td>

						<?php if( !$callback ){ ?>
						<td class="center">
							<?php echo $this->html( 'grid.featured' , $profile , 'profiles' ); ?>
						</td>
						<td class="center">
							<?php echo $this->html( 'grid.published' , $profile , 'profiles' ); ?>
						</td>
						<?php } ?>

						<td class="center">
							<?php echo $profile->getMembersCount( false ); ?>
						</td>

						<?php if( !$callback ){ ?>
						<td class="order">
							<?php echo $this->html( 'grid.ordering' , count( $profiles ) , ( $i + 1 ) , $ordering == 'ordering' ,  $profile->ordering ); ?>
						</td>

						<td class="center">
							<?php echo $profile->created; ?>
						</td>
						<?php } ?>

						<td class="center">
							<?php echo $profile->id;?>
						</td>
					</tr>
						<?php $i++; ?>
					<?php } ?>
				<?php } else { ?>
					<tr>
						<td colspan="8" class="center empty">
							<?php echo JText::_( 'COM_EASYSOCIAL_NO_PROFILES_AVAILABLE_CURRENTLY' );?>
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
	<input type="hidden" name="view" value="profiles" />
	<input type="hidden" name="controller" value="profiles" />
</form>
