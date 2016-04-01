<?php
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root() . '/components/com_virtuemart/assets/css/view-category.css');
$doc->addStyleSheet(JUri::root() . '/components/com_virtuemart/assets/css/vmcustom.css');
$doc->addStyleSheet(JUri::root() . '/media/system/css/ionicons.min.css');
$input = JFactory::getApplication()->input;
$from_search = $input->get('from_search', 0, 'int');
$keyword = $input->get('keyword', '', 'string');
defined('_JEXEC') or die('Restricted access');
$app=JFactory::getApplication();
$menu=$app->getMenu();
$menu_active_item=$menu->getActive();
$configviewlayout=$menu_active_item->configviewlayout;
JHTML::_('behavior.modal');
$menu_item_admin_edit_product=$configviewlayout->get('link_edit_product',0);
?>
<div class="view-category-manager">
    <form action="index.php" method="post" name="adminForm" id="adminForm">
        <table>
            <tr>
                <td width="100%">
                    <div class="pull-right">
                        <button class="btn btn-primary" type="button"><i class="im-plus"></i><?php echo JText::_('Add new') ?></button>
                        <button class="btn btn-primary" type="button"><i class="im-delete"></i><?php echo JText::_('Delete') ?></button>
                        <button class="btn btn-primary" type="button"><i class="en-publish"></i><?php echo JText::_('Publish') ?></button>
                        <button class="btn btn-primary" type="button"><i class="en-unpublish"></i><?php echo JText::_('Unpublish') ?></button>
                        <button class="btn btn-primary" type="button"><i class="im-copy"></i><?php echo JText::_('Duplicate') ?></button>
                    </div>
                </td>
            </tr>
        </table>
        <div id="editcell">
            <div class="vm-page-nav">

            </div>
            <table class="adminlist table table-striped" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="admin-checkbox">
                        <label class="checkbox"><input type="checkbox" name="toggle" value=""
                                                       onclick="Joomla.checkAll(this)"/><?php echo $this->sort('virtuemart_transfer_addon_id', 'Id'); ?>
                        </label>

                    </th>
                    <th>
                        <?php echo $this->sort('title', 'product name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('category_name', 'category name'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('location', 'Location'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('price', 'Price'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('start_date', 'Start date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('end_date', 'End date'); ?>
                    </th>
                    <th>
                        <?php echo $this->sort('description', 'Description'); ?>
                    </th>
                    <th>
                        <?php echo JText::_('Application') ?>
                    </th>
                    <th width="70">
                        <?php echo vmText::_('Action'); ?>
                    </th>
                    <?php /*	<th width="10">
				<?php echo vmText::_('COM_VIRTUEMART_SHARED'); ?>
			</th> */ ?>
                </tr>
                </thead>
                <?php
                $k = 0;
                for ($i = 0, $n = count($this->items); $i < $n; $i++) {
                    $row = $this->items[$i];

                    $checked = JHtml::_('grid.id', $i, $row->virtuemart_product_id);
                    $published = $this->gridPublished($row, $i);
                    $editlink = JUri::root().'index.php?option=com_virtuemart&view=productdetails&layout=adminedit&cid[]=' . $row->virtuemart_product_id.'&Itemid='.$menu_item_admin_edit_product;
                    ?>
                    <tr class="row<?php echo $k; ?>">
                        <td class="admin-checkbox">
                            <?php echo $checked; ?>
                        </td>
                        <td align="left">
                            <a href="<?php echo $editlink; ?>"><?php echo $row->product_name; ?></a>
                        </td>
                        <td align="left">
                            <?php echo $row->category_name; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->location; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->price; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->start_date; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->end_date; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->description; ?>
                        </td>
                        <td align="left">
                            <?php echo $row->list_tour; ?>
                        </td>
                        <td align="center">
                            <?php echo $published; ?>
                            <?php echo $edit; ?>
                            <?php echo $delete; ?>
                        </td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
                ?>
                <tfoot>
                <tr>
                    <td colspan="10">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <input type="hidden" value="" name="task">
        <input type="hidden" value="com_virtuemart" name="option">
        <input type="hidden" value="category" name="controller">
        <input type="hidden" value="category" name="view">
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>
