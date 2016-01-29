<?php
$doc=JFactory::getDocument();
JHtml::_('jquery.framework');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/core.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/widget.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/menu.js');
$doc->addScript(JUri::root().'/media/system/js/jquery.ui-contextmenu.js');
?>
<ul id="contextMenu" class="dropdown-menu" role="menu" style="display:none" >
    <li><a tabindex="-1" aria-disabled="true"  data-command="cut-element" href="javascript:void(0)"><?php echo JText::_('Cut') ?></a></li>
    <li ><a tabindex="-1"  data-command="copy-element" href="javascript:void(0)"><?php echo JText::_('Copy') ?></a></li>
    <li ><a tabindex="-1"  data-command="duplicate-element" href="javascript:void(0)"><?php echo JText::_('Duplicate') ?></a></li>
    <li class="disabled"><a tabindex="-1"  data-command="past-element" href="javascript:void(0)"><?php echo JText::_('Past') ?></a></li>
</ul>
