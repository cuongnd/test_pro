<?php
/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 26/9/2015
 * Time: 8:37
 */
JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/models');
$dataSourceModal=JModelLegacy::getInstance('DataSources','phpMyAdminModel');
$currentDataSource=$dataSourceModal->getCurrentDataSources();
?>
<ul class="nav sub">
    <?php foreach ($currentDataSource as $item) { ?>
        <li class="item-element item-data-source-ui" data-add-on-id="<?php echo $item->datasource->id; ?>"
            data-add-on-type="binding-source">
            <a href="javascript:void(0)"><i class=st-files></i><?php echo $item->datasource->title; ?></a>
            <ul class="nav sub">
                <?php foreach ($item->listField as $column => $columnValue) { ?>
                    <li class="item-element item-data-column" data-column-name="<?php echo $column ?>"><a
                            href="javascript:void(0)"><i class=st-files></i> <?php echo $column ?></a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
</ul>
