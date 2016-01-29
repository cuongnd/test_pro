<?php
/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 26/9/2015
 * Time: 8:32
 */
JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_plugins/models');
$pluginModel=JModelLegacy::getInstance('Plugins','PluginsModel');
$plugins=$pluginModel->getItems();
$listPlugin=array();
foreach($plugins as $plugin)
{
    $listPlugin[$plugin->folder][]=$plugin;
}
?>
<ul class="nav sub list-plugin">
    <?php foreach ($listPlugin as $key => $plugins) { ?>
        <li><a href="javascript:void(0)"><?php echo $key ?> <i class=im-paragraph-justify></i></a>
            <ul class="nav sub">
                <?php foreach ($plugins as $plugin) { ?>
                    <li data-plugin-id="<?php echo $plugin->id ?>" title="<?php echo $plugin->title ?>"
                        class="plugin_item"><a href="javascript:void(0)">
                            <div class="pull-left"><i
                                    class="en-shuffle"></i><?php echo JString::sub_string($plugin->name, 7) ?>
                            </div>
                            <i class="st-settings pull-right"></i>

                            <div style="width: 69px" class="pull-right"><input name="enable_plugin"
                                                                               id="enable_plugin"
                                                                               class="plugin_item"
                                                                               title="<?php echo $plugin->title ?>"
                                                                               type="checkbox"
                                                                               data-size="mini" <?php echo $plugin->issystem ? 'disabled' : '' ?>   <?php echo $plugin->enabled ? 'checked' : '' ?>>
                            </div>
                        </a>

                    </li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
</ul>
