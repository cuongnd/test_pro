<?php
/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 10/9/2015
 * Time: 19:02
 */
JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_phpmyadmin/models');
$dataSourceModal = JModelLegacy::getInstance('DataSources', 'phpMyAdminModel');
$currentDataSource = $dataSourceModal->getCurrentDataSources();
$doc=JFactory::getDocument();
ob_start();
require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';

?>
<script type="text/javascript">
    jQuery(document).ready(function($){
        Joomla.design_website.seting.list_data_source=<?php echo json_encode($currentDataSource) ?>;
    });

</script>
<?php

$jscontent = ob_get_clean();
$jscontent = JUtility::remove_string_javascript($jscontent);
$doc->addScriptDeclaration($jscontent);
?>
<div>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#database" aria-controls="database" role="tab" data-toggle="tab">database</a></li>
        <li role="presentation"><a href="#detail" aria-controls="detail" role="tab" data-toggle="tab">detail</a></li>
        <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Messages</a></li>
        <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Settings</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="database">            <div class="list-add-one">
                <?php

                foreach ($currentDataSource as $item) {
                    ?>
                    <div data-add-on-id="<?php echo $item->datasource->id ?>" class="add-on-item-content pull-left">
                        <a class="remove label label-danger remove-add-on" data-add-on-id="<?php echo $item->datasource->id ?>" href="javascript:void(0)">
                            <i class="glyphicon-remove glyphicon"></i>
                        </a>
                        <a data-add-on-id="<?php echo $item->datasource->id ?>" href="javascript:void(0)">
                            <i class="br-database"></i><?php echo $item->datasource->title ?>
                        </a>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="detail"><div id="database_detail"></div></div>
        <div role="tabpanel" class="tab-pane" id="messages">...</div>
        <div role="tabpanel" class="tab-pane" id="settings">...</div>
    </div>

</div>

