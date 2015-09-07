<?php

/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

?>

<form action="index.php" method="post" id="adminForm" name="adminForm">
    <div id="editcell">
        <h2><?php echo JText::_('COM_JFBCONNECT_USERMAP_SELECT_REQUEST');?></h2>
        <?php echo $this->requestList; ?>
        <p><?php echo JText::sprintf("COM_JFBCONNECT_USERMAP_SELECT_REQUEST_PREVIEW", count($this->fbIds));?></p>
    </div>
    <br/><br/>

    <input type="hidden" name="option" value="com_jfbconnect"/>
    <input type="hidden" name="task" value="previewSend"/>
    <input type="hidden" name="controller" value="request"/>
    <?php foreach ($this->fbIds as $id)
        echo '<input type="hidden" name="fbIds[]" value="'.$id.'" />';
    ?>
</form>
