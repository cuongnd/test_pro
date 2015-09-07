<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_plugins
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$supperAdmin=JFactory::isSupperAdmin();
// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state',	'com_plugins');
$saveOrder	= $listOrder == 'ordering';
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_plugins&task=plugins.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'itemList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_plugins&view=plugins'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="main-container" class="span10">

<?php else : ?>
	<div id="main-container">
<?php endif;?>
        <?php
        // Search tools bar
        echo JLayoutHelper::render('joomla.contextmenu.contextmenu', array('view' => $this), null, array('debug' => false));
        ?>

        <div id="quick-tool" class="btn-toolbar row-fluid">
            <?php if($supperAdmin){ ?>
            <div class="assign-website btn-group pull-left">
                <?php echo $this->listWebsite; ?>
            </div>
            <?php } ?>
        </div>
        <?php 		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
        ?>

		<div class="clearfix"> </div>
		<table class="table table-striped" id="itemList">
			<thead>
				<tr>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', '<i class="icon-menu-2"></i>', 'ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING'); ?>
					</th>
					<th width="1%" class="hidden-phone">
						<?php echo JHtml::_('grid.checkall'); ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo JHtml::_('grid.sort', 'JSTATUS', 'enabled', $listDirn, $listOrder); ?>
					</th>
					<th class="title">
						<?php echo JHtml::_('grid.sort', 'COM_PLUGINS_NAME_HEADING', 'name', $listDirn, $listOrder); ?>
					</th>
                    <?php if($supperAdmin){ ?>
                        <th class="title">
                            <?php echo JHtml::_('grid.sort', 'JGLOBAL_RUN_FOR', 'a.website_id', $listDirn, $listOrder); ?>
                        </th>
                    <?php } ?>
                    <?php if($supperAdmin){ ?>
                        <th class="title">
                            <?php echo JHtml::_('grid.sort', 'Is System', 'a.issystem', $listDirn, $listOrder); ?>
                        </th>
                    <?php } ?>

                    <th width="10%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_PLUGINS_FOLDER_HEADING', 'folder', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" class="nowrap hidden-phone">
						<?php echo JHtml::_('grid.sort', 'COM_PLUGINS_ELEMENT_HEADING', 'element', $listDirn, $listOrder); ?>
					</th>
					<th width="5%" class="hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'access', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="12">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering   = ($listOrder == 'ordering');
				$canEdit    = $user->authorise('core.edit',       'com_plugins');
				$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $user->get('id') || $item->checked_out == 0;
				$canChange  = $user->authorise('core.edit.state', 'com_plugins') && $canCheckin;
				?>
				<tr class="row<?php echo $i % 2; ?>" item-id="<?php echo $item->id?>" sortable-group-id="<?php echo $item->folder?>">
					<td class="order nowrap center hidden-phone">
						<?php
						$iconClass = '';
						if (!$canChange)
						{
							$iconClass = ' inactive';
						}
						elseif (!$saveOrder)
						{
							$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
						}
						?>
						<span class="sortable-handler<?php echo $iconClass ?>">
							<i class="icon-menu"></i>
						</span>
						<?php if ($canChange && $saveOrder) : ?>
							<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering;?>" class="width-20 text-area-order " />
						<?php endif; ?>
					</td>
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="center">
						<?php echo JHtml::_('jgrid.published', $item->enabled, $i, 'plugins.', $canChange); ?>
					</td>
					<td>
						<?php if ($item->checked_out) : ?>
							<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'plugins.', $canCheckin); ?>
						<?php endif; ?>
						<?php if ($canEdit) : ?>
							<a class="quick-edit-title" href="<?php echo JRoute::_('index.php?option=com_plugins&task=plugin.edit&id='.(int) $item->id); ?>">
								<?php echo $item->name; ?></a>
						<?php else : ?>
								<?php echo $item->name; ?>
						<?php endif; ?>
					</td>
                    <?php if($supperAdmin){ ?>
                        <td class="center hidden-phone">
                            <?php echo $item->website ?>
                        </td>
                    <?php } ?>
                    <?php if($supperAdmin){ ?>
                    <td class="center">
                        <?php echo JHtml::_('jgrid.is_system', $item->issystem, $i, 'plugins.', $canChange); ?>
                    </td>
                    <?php } ?>
					<td class="nowrap small hidden-phone">
						<?php echo $this->escape($item->folder);?>
					</td>
					<td class="nowrap small hidden-phone">
						<?php echo $this->escape($item->element);?>
					</td>
					<td class="small hidden-phone">
						<?php echo $this->escape($item->access_level); ?>
					</td>
					<td class="center hidden-phone">
						<?php echo (int) $item->id;?>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
