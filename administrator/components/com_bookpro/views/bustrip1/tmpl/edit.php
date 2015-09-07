<?php
/**
 * @package    Bookpro
 * @author        Nguyen Dinh Cuong
 * @link        http://ibookingonline.com
 * @copyright    Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version    $Id: default.php 81 2012-08-11 01:16:36Z quannv $
 **/

JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
defined('_JEXEC') or die('Restricted access');
?>


<form action="index.php" method="post" name="adminForm" id="adminForm"       class="form-validate">

    <div class="form-horizontal">
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('id'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('from'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('from'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('to'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('to'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('roundtrip'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('roundtrip'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('km'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('km'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('duration'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('duration'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('duration2'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('duration2'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('summary'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('summary'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('trip_information'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('trip_information'); ?></div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
            <div class="controls"><?php echo $this->form->getInput('state'); ?></div>
        </div>


    </div>

    <input type="hidden" name="option" value="com_bookpro"/>
    <input type="hidden" name="controller" value="bustrip"/>
    <input type="hidden" name="task" value="save"/>
    <?php echo JHtml::_('form.token'); ?>
</form>

