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

$privLib = foundry::privacy();

?>
<form name="adminForm" id="adminForm" method="post" data-table-grid>
<div class="row-fluid filter-bar">
	<div class="span12">
		<div class="form-inline">
			<span class="mr-20">
				<strong><?php echo JText::_( 'COM_EASYSOCIAL_SEARCH' ); ?> :</strong>

				<?php echo $this->html( 'filter.search' , $search ); ?>
			</span>

			<div class="pull-right">
				<?php echo $this->html( 'filter.limit' , $limit ); ?>
			</div>

		</div>
	</div>
</div>


<div id="pointsTable">
	<table class="table table-striped table-es table-hover">
		<thead>
			<th>
				<?php echo $this->html( 'grid.sort' , 'description' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_DESCRIPTION' ) , $ordering , $direction ); ?>
			</th>
			<th width="10%" class="center">
				<?php echo $this->html( 'grid.sort' , 'type' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_TYPE' ) , $ordering , $direction ); ?>
			</th>
			<th width="10%" class="center">
				<?php echo $this->html( 'grid.sort' , 'rule' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_RULE' ) , $ordering , $direction ); ?>
			</th>
			<th width="15%" class="center">
				<?php echo $this->html( 'grid.sort' , 'value' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_DEFAULT' ) , $ordering , $direction ); ?>
			</th>
			<th width="5%" class="center">
				<?php echo $this->html( 'grid.sort' , 'id' , JText::_( 'COM_EASYSOCIAL_TABLE_COLUMN_ID' ) , $ordering , $direction ); ?>
			</th>
		</thead>

		<tbody>
		<?php if( $privacy ){ ?>
			<?php foreach( $privacy as $item ){ ?>
			<tr>
				<td>
					<a href="<?php echo FRoute::_( 'index.php?option=com_easysocial&view=privacy&layout=form&id=' . $item->id );?>">
						<i class="ies-eye ies-small mr-5"></i> <?php echo JText::_( $item->description ); ?>
					</a>
				</td>
				<td class="center">
					<?php echo $item->type ?>
				</td>
				<td class="center">
					<?php echo $item->rule ?>
				</td>
				<td class="center">
					<?php
						$text = $privLib->toKey( $item->value );
						echo JText::_( 'COM_EASYSOCIAL_PRIVACY_OPTION_'  . strtoupper( $text ) );
					?>
				</td>

				<td class="center">
					<?php echo $item->id;?>
				</td>
			</tr>
			<?php } ?>
		<?php } else { ?>
			<tr>
				<td colspan="6">
					<div class="empty center"><?php echo JText::_( 'COM_EASYSOCIAL_PRIVACY_LIST_EMPTY' ); ?></div>
				</td>
			</tr>
		<?php } ?>
		</tbody>

		<tfoot>
			<tr>
				<td colspan="6">
					<?php if( $privacy ){ ?>
					<div class="footer-pagination"><?php echo $pagination->getListFooter();?></div>
					<?php } ?>
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
<input type="hidden" name="view" value="privacy" />
<input type="hidden" name="controller" value="privacy" />
</form>
