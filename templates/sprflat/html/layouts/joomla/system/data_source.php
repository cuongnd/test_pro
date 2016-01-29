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

$scriptId = "tab_footer";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function($){


        $('#footer_tab a[data-toggle="tab"]').on('shown.bs.tab', function (event) {

            var  aria_controls= $(event.target).attr('aria-controls');
            console.log(aria_controls);
            switch(aria_controls) {
                case 'detail':
                    var ajax_add_detail= $(event.target).data('ajax_add_detail');
                    if(typeof ajax_add_detail==="undefined")
                    {
                        $(event.target).data('ajax_add_detail',true)
                        ajax_load_detail_database();
                    }
                    break;
            }

        });
        function ajax_load_detail_database()
        {
            Joomla.design_website.seting.list_data_source=<?php echo json_encode($currentDataSource) ?>;
            Joomla.design_website.build_grid_database_manager(Joomla.design_website.seting.list_data_source);

        }

    });
</script>
<?php
$script=ob_get_clean();
$script=JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);






require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';

?>
<div>

    <!-- Nav tabs -->
    <ul id="footer_tab" class="nav nav-tabs" role="tablist">
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

