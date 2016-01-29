<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2014 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v6.2.4
 * @build-date      2014/12/15
 */

defined('_JEXEC') or die('Restricted access');

$subStatus = $this->subStatus;
?>
<div class="clrlft"></div>
<div class="sourcecoast">
    <div class="row-fluid">
        <div class="span12 autotune">
            <fieldset class="adminform">
                <legend><?php echo JText::_('COM_JFBCONNECT_AUTOTUNE_SUBSTATUS_LABEL');?></legend>
                <div>
                    <?php
                    foreach ($subStatus as $key => $msg)
                    {
                        echo '<div class="pull-left" style="margin:0 10px;"><strong>' . ucwords(str_replace("_", " ", $key)) . ":</strong> " . $msg . '</div>';
                    }
                    echo '<div class="pull-left" style="margin:0 10px;"><strong>Last Checked:</strong> ' . $this->subStatusUpdated . '</div>';
                    ?>
                </div>
            </fieldset>
        </div>
    </div>
</div>