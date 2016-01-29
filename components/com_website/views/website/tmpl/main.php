<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_website
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$doc=JFactory::getDocument();
JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');
$doc->addStyleSheet(JUri::root().'components/com_website/assets/css/view-website-main.css');
$doc->addScript(JUri::root().'components/com_website/assets/js/view-website-main.js');
?>

<div class="create-website">
    <div class="row">
        <div class="col-lg-4">
            <img class="img-responsive" src="<?php JUri::root() ?>images/stories/others/_day_01_prv.jpg">
        </div>
        <div class="col-lg-8">
            <div class="row-fluid">
                <h3>
                    <?php echo JText::_('Welcome to the tool create your website setup wizard') ?>
                </h3>
                <h4><?php echo JText::_('Please don\'t close your browser.') ?></h4>
                <h5><?php echo JText::_('Success') ?></h5>
                <div class="progress">

                    <div id="setup_website_progress_bar" class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="<?php echo $this->progress_bar_success ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $this->progress_bar_success ?>%">
                    </div>
                </div>

                <div class="main-display">
                    <?php $this->setLayout($this->layoutOfCurrentStep); ?>
                    <?php echo $this->loadtemplate(); ?>
                </div>
                <input type="hidden" name="progress_success" value="0">
            </div>

        </div>
    </div>
    <div class="row">
        <div class="pull-right btn-group setup">
            <button type="button" class="btn btn-warning back"><span class="glyphicon glyphicon-chevron-left"></span>Back</button>
            <button type="button"  data-loading-text="Loading..." autocomplete="off" class="btn btn-primary next">Next<span class="glyphicon glyphicon-chevron-right"></button>
            <button type="button" class="btn btn-danger cancel">Cancel</button>
        </div>
    </div>
    <div class="row">
        <div class="pull-right">
            <div class="checkbox autosetup">
                <label>
                    <input name="autoSetup" type="checkbox"> <?php echo JText::_('Auto setup') ?>
                </label>
            </div>
        </div>
    </div>
</div>


