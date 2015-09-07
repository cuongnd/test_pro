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
$doc=JFactory::getDocument();
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listFilterTableType	= $this->escape($this->state->get('list.tabletype'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$saveOrder	= $listOrder == 'a.ordering';
$doc->addScript(JUri::root().'/administrator/components/com_phpmyadmin/assets/js/view-tables.js');
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

<form action="<?php echo JRoute::_('index.php?option=com_phpmyadmin&view=tables'); ?>" method="post" name="adminForm" id="adminForm">
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
           // echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
            ?>
            <?php if (empty($this->items)) : ?>
                <div class="alert alert-no-items">
                    <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                </div>
            <?php else : ?>
                <?php
                $firstTable=reset($this->items);
                $firstTable=$firstTable['local_table_property'];
                $countColumn=0;
                $listFilterTableType=array(
                    array(
                        'value'=>'structure',
                        'text'=>'Structure'
                    ),
                    array(
                        'value'=>'datalength',
                        'text'=>'Data Length'
                    ),
                );
                ?>
                <div id="filter-bar" class="btn-toolbar">
                    <label for="tabletype"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
                    <select name="tabletype" id="tabletype" class="input-medium" onchange="Joomla.submitform();">
                        <option value=""><?php echo JText::_('filter Table type');?></option>
                        <?php echo JHtml::_('select.options', $listFilterTableType, 'value', 'text', $listTableType); ?>
                    </select>
                </div>
                <table class="table table-striped" id="productList">
                    <thead>
                    <tr>
                        <th width="1%" class="nowrap center hidden-phone">
                            <?php echo JHtml::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                        </th>
                        <th width="1%" class="hidden-phone">
                            <?php echo JHtml::_('grid.checkall'); ?>
                        </th>
                        <?php foreach($firstTable as $key=>$value){ ?>
                        <?php $countColumn++; ?>
                        <th width="1%" class="hidden-phone">
                            <?php echo JHtml::_('searchtools.sort',  $key, 'a.created_by', $listDirn, $listOrder); ?>
                        </th>
                        <?php } ?>
                        <th width="1%" class="hidden-phone">
                            <div class="btn-group">
                                <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Synchronize Structure this Table</a></li>
                                    <li><a href="#">Synchronize Data this Table</a></li>
                                    <li><a href="#">Synchronize Structure Data this Table</a></li>
                                    <li><a href="#">post Structure this local Table to server</a></li>
                                    <li><a href="#">Edit</a></li>
                                </ul>
                            </div>
                        </th>
                        <?php foreach($firstTable as $key=>$value){ ?>

                        <th width="1%" class="hidden-phone">
                            <?php echo JHtml::_('searchtools.sort',  $key, 'a.created_by', $listDirn, $listOrder); ?>
                        </th>
                        <?php } ?>

                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=0; ?>
                    <?php foreach ($this->items as $key_table => $item) :

                        $local_table_property=$item['local_table_property'];
                        $server_table_property=$item['server_table_property'];

                        $list_column=$item['list_column'];
                        $item->max_ordering = 0; //??
                        $ordering   = ($listOrder == 'a.ordering');
                        $canCreate  = $user->authorise('core.create',     'com_product.category.'.$item->catid);
                        $canEdit    = $user->authorise('core.edit',       'com_product.product.'.$item->id);
                        $canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
                        $canEditOwn = $user->authorise('core.edit.own',   'com_product.product.'.$item->id) && $item->created_by == $userId;
                        $canChange  = $user->authorise('core.edit.state', 'com_product.product.'.$item->id) && $canCheckin;
                        ?>
                        <tr class="row<?php echo $i % 2; ?> <?php echo (!$local_table_property||!$server_table_property)?' error ':'' ?>" sortable-group-id="<?php echo $item->catid; ?>">
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
                                <?php echo JHtml::_('grid.id', $i, $key_table); ?>
                            </td>
                            <?php if($local_table_property)foreach($local_table_property as $key=> $value){ ?>
                                <td class="center hidden-phone">
                                    <span title="<?php echo $value!=$server_table_property->$key?('server-'.$key.':'.$server_table_property->$key):'' ?>" class="<?php echo $value!=$server_table_property->$key?' text-error table-error table-error-'.$key:'' ?>"><?php echo $value ?></span>
                                </td>
                            <?php }else{
                                ?>
                                <td  colspan="<?php echo $countColumn ?>" class="center hidden-phone table-local-not-exists" data-table="<?php echo $key_table ?>">
                                    <?php echo JText::_('Local not exists this table') ?>
                                </td>
                            <?php
                            } ?>
                            <td class="center hidden-phone">
                                <div class="btn-group">
                                    <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Synchronize Structure this Table</a></li>
                                        <li><a href="#">Synchronize Data this Table</a></li>
                                        <li><a href="#">Synchronize Structure Data this Table</a></li>
                                        <li><a href="#">post Structure this local Table to server</a></li>
                                        <li><a href="#">Edit</a></li>
                                    </ul>
                                </div>
                            </td>
                            <?php if($server_table_property)foreach($server_table_property as $key=> $value){ ?>
                                <td class="center hidden-phone">
                                    <span title="<?php echo $value!=$local_table_property->$key?('Local-'.$key.':'.$local_table_property->$key):'' ?>" class="<?php echo $value!=$local_table_property->$key?' text-error table-error table-error-'.$key:'' ?>"><?php echo $value ?></span>
                                </td>
                            <?php }else{ ?>
                                <td  colspan="<?php echo $countColumn ?>" class="center hidden-phone table-server-not-exists" data-table="<?php echo $key_table ?>">
                                    <?php echo JText::_('Server not exists this table') ?>
                                </td>
                            <?php } ?>
                        </tr>
                        <?php
                        $firstColumn=reset($list_column);

                        $firstColumn=is_object($firstColumn->local)?$firstColumn->local:$firstColumn->server;
                        $colSpan=0;
                        ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td colspan="<?php echo $countColumn ?>">
                                <table width="100%">
                                    <thead>
                                        <tr>
                                            <?php foreach($firstColumn as $key=>$value){ ?>
                                            <th>
                                                <?php echo $key ?>
                                                <?php $colSpan++; ?>
                                            </th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($list_column as $key=>$properties){ ?>
                                        <tr>
                                            <?php if(is_object($properties->local))foreach($properties->local as $property=>$valueProperty){ ?>
                                            <td>
                                                <?php $error=$valueProperty!=$properties->server->$property ?>
                                                <span title="<?php echo $error?'Server:'.$properties->server->$property:''; ?>" class="<?php echo $error?' text-error column-error column-error-'.$property:'' ?>"><?php echo $valueProperty ?></span>
                                            </td>
                                            <?php }else{ ?>
                                            <td colspan="<?php echo $colSpan ?>" class="column-local-not-exists" data-column="<?php echo $key ?>" data-table="<?php echo $key_table ?>"><?php echo JText::_('This field not exists') ?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Add payment</a></li>
                                        <li><a href="#">View detail</a></li>
                                        <li><a href="#">Edit</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td  colspan="<?php echo $countColumn ?>">
                                <table width="100%">
                                    <thead>
                                    <tr>
                                        <?php foreach($firstColumn as $key=>$value){ ?>
                                            <th>
                                                <?php echo $key ?>
                                            </th>
                                        <?php } ?>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach($list_column as $key=>$properties){ ?>
                                        <tr>
                                            <?php if(is_object($properties->server))foreach($properties->server as $property=>$valueProperty){ ?>
                                                <td>
                                                    <?php $error=$valueProperty!=$properties->local->$property ?>
                                                    <span title="<?php echo $error?'Local:'.$properties->local->$property:''; ?>" class="<?php echo $error?' text-error column-error column-error-'.$property:'' ?>"><?php echo $valueProperty ?></span>
                                                </td>
                                            <?php }else{ ?>
                                                <td colspan="<?php echo $colSpan ?>" class="column-server-not-exists" data-column="<?php echo $key ?>"><?php echo JText::_('This field not exists') ?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>

                            </td>

                        </tr>
                    <?php $i++; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
</form>
