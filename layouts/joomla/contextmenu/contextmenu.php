<?php
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/media/system/js/contextmenu.js');
$data = $displayData;
$command=$data['view']->command;
$controller_task=$data['view']->controller_task;

?>
<ul id="contextMenu" class="dropdown-menu" role="menu" style="display:none" >
    <li><a tabindex="-1"  data-command="edit_all_row" href="javascript:void(0)"><?php echo JText::_('Edit All Row') ?></a></li>
    <li><a tabindex="-1" data-controller-task="<?php echo $controller_task ?>" data-command-component="<?php echo $command ?>" data-command="save_all" href="javascript:void(0)" style="display: none" class="save-all"><?php echo JText::_('Save all') ?></a></li>
    <li><a tabindex="-1" href="javascript:void(0)">Something else here</a></li>
    <li class="divider"></li>
    <li><a tabindex="-1" href="javascript:void(0)">Separated link</a></li>
</ul>
<style type="text/css">
    .input-title
    {
        border: 1px #ccc dotted;
    }
</style>