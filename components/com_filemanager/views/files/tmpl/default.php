<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_product
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$saveOrder	= $listOrder == 'a.ordering';
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/administrator/components/com_filemanager/assets/js/view-files.js');
$doc->addStyleSheet(JUri::root().'/administrator/components/com_filemanager/assets/css/view-files.css');
require_once JPATH_ROOT.'/administrator/components/com_filemanager/helpers/files.php';
$this->items=FilesHelper::getFoldersAndFilesLocalAndServer(JPATH_ROOT);
if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_product&task=products.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'productList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
$assoc		= JLanguageAssociations::isEnabled();
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

<form action="<?php echo JRoute::_('index.php?option=com_product&view=products'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<?php if (empty($this->items)) : ?>
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<div class="row">
				<div class="span6">
					<table class="table table-striped" id="foldersfiles">
						<thead>
						<tr>
							<th width="1%" class="nowrap center hidden-phone">
								<?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
							</th>
							<th width="1%" class="hidden-phone">
								<?php echo JHtml::_('grid.checkall'); ?>
							</th>
							<th width="1%" style="min-width:55px" class="nowrap center">
								<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
							</th>
							<th>
								<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
							</th>
							<th width="10%" class="nowrap hidden-phone">
								<?php echo JHtml::_('searchtools.sort',  'File Size', 'a.access', $listDirn, $listOrder); ?>
							</th>

							<th width="10%" class="nowrap hidden-phone">
								<?php echo JHtml::_('searchtools.sort',  'Modify time', 'a.created_by', $listDirn, $listOrder); ?>
							</th>
							<th width="5%" class="nowrap hidden-phone">
								<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
							</th>
							<th width="10%" class="nowrap hidden-phone">
								<?php echo JHtml::_('searchtools.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
							</th>
							<th width="10%">
								<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
							</th>
							<th width="1%" class="nowrap hidden-phone">
								<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
							</th>
						</tr>
						</thead>
						<tbody>
						<?php foreach ($this->items as $i => $item) :
							$item->max_ordering = 0; //??
							$ordering   = ($listOrder == 'a.ordering');
							$canCreate  = $user->authorise('core.create',     'com_product.category.'.$item->catid);
							$canEdit    = $user->authorise('core.edit',       'com_product.product.'.$item->id);
							$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
							$canEditOwn = $user->authorise('core.edit.own',   'com_product.product.'.$item->id) && $item->created_by == $userId;
							$canChange  = $user->authorise('core.edit.state', 'com_product.product.'.$item->id) && $canCheckin;
							?>
							<tr class="row<?php echo $i % 2; ?> item <?php echo 'item-'.$item->type ?> show_sub_child" data-node="<?php echo $i+1 ?>" sortable-group-id="<?php echo $item->catid; ?>">
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
										<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
									<?php endif; ?>
								</td>
								<td class="center hidden-phone">
									<?php echo JHtml::_('grid.id', $i, $item->id); ?>
								</td>
								<td class="center">
									<div class="btn-group">
										<?php echo JHtml::_('jgrid.published', $item->state, $i, 'products.', $canChange, 'cb', $item->publish_up, $item->publish_down); ?>
										<?php echo JHtml::_('productadministrator.featured', $item->featured, $i, $canChange); ?>
										<?php
										// Create dropdown items
										$action = $archived ? 'unarchive' : 'archive';
										JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'products');

										$action = $trashed ? 'untrash' : 'trash';
										JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'products');

										// Render dropdown list
										echo JHtml::_('actionsdropdown.render', $this->escape($item->title));
										?>
									</div>
								</td>
								<td class="has-context">
									<span class="pull-left space"></span>
									<i class="icon-plus-minus pull-left icon-plus-sign"></i>
										<a href="javascript:void(0)" data-type="<?php echo $item->type ?>" class="pull-left treenode" data-path="<?php echo $item->name ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
										<?php echo $this->escape($item->name); ?>
										</a>
								</td>
								<td class="small hidden-phone item-size">
									<?php echo $this->escape($item->size); ?>
								</td>
								<td class="small hidden-phone item-mtime">
									<?php echo $this->escape($item->mtime); ?>
								</td>
								<td class="small hidden-phone">
									<?php if ($item->language == '*'):?>
										<?php echo JText::alt('JALL', 'language'); ?>
									<?php else:?>
										<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
									<?php endif;?>
								</td>
								<td class="nowrap small hidden-phone">
									<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
								</td>
								<td class="center">
									<?php echo (int) $item->hits; ?>
								</td>
								<td class="center hidden-phone">
									<?php echo (int) $item->id; ?>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="span6"></div>
			</div>

		<?php endif; ?>
		<?php echo $this->pagination->getListFooter(); ?>
		<?php //Load the batch processing form. ?>
		<?php echo $this->loadTemplate('batch'); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
