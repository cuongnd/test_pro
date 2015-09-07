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
<form name="adminForm" id="adminForm" method="post" data-table-grid>
<div class="row-fluid filter-bar">
	<div class="span12">
		<div class="form-inline">

			<span class="mr-20">
				<strong><?php echo JText::_( 'COM_EASYSOCIAL_SEARCH' ); ?> :</strong>

				<?php echo $this->html( 'filter.search' , $search ); ?>
			</span>

			<?php if( $this->tmpl != 'component' ){ ?>
			<span>
				<strong><?php echo JText::_( 'COM_EASYSOCIAL_FILTER_BY' ); ?> :</strong>

				<?php echo $this->html( 'filter.lists' , $extensions , 'extension' , $extension , JText::_( 'COM_EASYSOCIAL_FILTER_SELECT_EXTENSION' ) , 'all' ); ?>

				<?php echo $this->html( 'filter.published' , 'state' , $state ); ?>
			</span>
			<?php } ?>

			<div class="pull-right">
				<?php echo $this->html( 'filter.limit' , $limit ); ?>
			</div>
		</div>
	</div>
</div>

<div id="pointsTable">
	<table class="table table-striped table-es table-hover">
		<thead>
			<?php if( $this->tmpl != 'component' ){ ?>
			<th width="1%" class="center">
				<input type="checkbox" name="toggle" data-table-grid-checkall />
			</th>
			<?php } ?>

			<th>
				<?php echo $this->html( 'grid.sort' , 'title' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_TITLE' ) , $ordering , $direction ); ?>
			</th>

			<?php if( $this->tmpl != 'component' ){ ?>
			<th width="1%" class="center">
				<?php echo $this->html( 'grid.sort' , 'state' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_STATE' ) , $ordering , $direction ); ?>
			</th>

			<th width="5%" class="center">
				<?php echo $this->html( 'grid.sort' , 'state' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_EXTENSION' ) , $ordering , $direction ); ?>
			</th>
			<th width="5%" class="center">
				<?php echo $this->html( 'grid.sort' , 'command' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_COMMAND' ) , $ordering , $direction ); ?>
			</th>
			<th width="15%" class="center">
				<?php echo $this->html( 'grid.sort' , 'created' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_CREATED' ) , $ordering , $direction ); ?>
			</th>
			<?php } ?>

			<th width="<?php echo $this->tmpl == 'component' ? '8%' : '5%';?>" class="center">
				<?php echo $this->html( 'grid.sort' , 'id' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_ID' ) , $ordering , $direction ); ?>
			</th>
		</thead>

		<tbody>
		<?php if( $badges ){ ?>
			<?php $i = 0; ?>
			<?php foreach( $badges as $badge ){ ?>
			<tr>

				<?php if( $this->tmpl != 'component' ){ ?>
				<td class="center">
					<?php echo $this->html( 'grid.id' , $i , $badge->id ); ?>
				</td>
				<?php } ?>

				<td>
					<img src="<?php echo $badge->getAvatar();?>" width="24" height="24" align="left" class="mr-10 mt-5" />

					<a href="<?php echo FRoute::_( 'index.php?option=com_easysocial&view=badges&layout=form&id=' . $badge->id );?>"
						data-id="<?php echo $badge->id;?>"
						data-title="<?php echo $badge->get( 'title' );?>"
						data-avatar="<?php echo $badge->getAvatar();?>"
						data-alias="<?php echo $badge->getAlias();?>"
						data-badge-insert
						data-es-provide="tooltip"
						data-content-original="<?php echo $badge->description;?>">
						<?php echo $badge->get( 'title' ); ?>
					</a>
					<div class="small">
						<?php echo $badge->get( 'description' ); ?>
					</div>
				</td>

				<?php if( $this->tmpl != 'component' ){ ?>
				<td class="center">
					<?php echo $this->html( 'grid.published' , $badge , 'badges' ); ?>
				</td>
				<td class="center">
					<?php echo $badge->getExtensionTitle();?>
				</td>

				<td class="center">
					<?php echo $badge->command;?>
				</td>

				<td class="center">
					<?php echo $badge->created;?>
				</td>
				<?php } ?>

				<td class="center">
					<?php echo $badge->id;?>
				</td>
			</tr>
			<?php } ?>
		<?php } else { ?>
			<tr>
				<td colspan="8">
					<div class="emtpy"><?php echo JText::_( 'COM_EASYSOCIAL_BADGES_LIST_EMPTY' ); ?></div>
				</td>
			</tr>
		<?php } ?>
		</tbody>

		<tfoot>
			<tr>
				<td colspan="8">
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
<input type="hidden" name="view" value="badges" />
<input type="hidden" name="controller" value="badges" />

</form>
