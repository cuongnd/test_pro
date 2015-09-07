<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>
<div class="sourcecoast">
    <div class="row-fluid">
        <?php include('step_sidebar.php'); ?>
        <div class="span9 autotune">
            <form method="post" id="adminForm" name="adminForm">
                <h3><?php echo JText::_('COM_JFBCONNECT_AUTOTUNE_FINISH_LABEL'); ?></h3>

                <p><?php echo JText::_('COM_JFBCONNECT_AUTOTUNE_FINISH_DESC') ?></p>

                <h4><?php echo JText::_('COM_JFBCONNECT_AUTOTUNE_FINISH_NEXT_LABEL'); ?></h4>

                <p><?php echo JText::_('COM_JFBCONNECT_AUTOTUNE_FINISH_NEXT_DESC'); ?>
                <ul>
                    <li><strong>Further customization:</strong> Look through the <a href="index.php?option=com_jfbconnect&view=config">configuration options</a>
                        available in JFBConnect. Everything should be set to good default values, but you may want to tweak them further.
                    </li>
                    <li><strong>Add a Login With Facebook button:</strong> Let users register or login to your site with Facebook & JFBConnect:
                        <ul>
                            <li>Configure and enable the SCLogin module in the <a href="index.php?option=com_modules">Module Manager</a>. This is a new login
                                module that has the "Login With Facebook" button built in.
                            </li>
                            <li>Alternatively, add {JFBCLogin} to your articles, template, or anywhere else on your site to add the Login With Facebook button
                                in custom locations.
                            </li>
                        </ul>
                    </li>
                    <li><strong>Social Integration:</strong> Configure the <a href="index.php?option=com_jfbconnect&view=social">Social buttons</a> of
                        JFBConnect for automatically adding social sharing buttons or comment boxes to your content.
                    </li>
                </ul>
                </p>

                <input type="hidden" name="option" value="com_jfbconnect" />
                <input type="hidden" name="view" value="autotune" />
                <input type="hidden" name="task" value="" />
            </form>
            <div style="clear:both"></div>
        </div>
    </div>
</div>
