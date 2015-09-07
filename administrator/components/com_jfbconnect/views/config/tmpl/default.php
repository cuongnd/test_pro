<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');

$providers = JFBCFactory::getAllProviders();

?>
<div class="sourcecoast">
    <form method="post" id="adminForm" name="adminForm" class="form-horizontal">
        <?php
        $this->tabsStart('myTab', 'config_general');

        $this->tabStart('myTab', 'config_general', JText::_('COM_JFBCONNECT_CONFIG_MENU_GENERAL'));
        $this->formDisplay('config');
        $this->tabEnd();

        foreach ($providers as $p)
        {
            $this->tabStart('myTab', 'config_' . $p->systemName, JText::_('COM_JFBCONNECT_CONFIG_MENU_' . strtoupper($p->name) . '_API'));
            $this->formDisplay($p->name);
            $this->tabEnd();
        }
        $this->tabsEnd();
        ?>

        <input type="hidden" name="option" value="com_jfbconnect" />
        <input type="hidden" name="controller" value="config" />
        <input type="hidden" name="cid[]" value="0" />
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </form>
</div>

<script type="text/javascript">
    function setupFields()
    {
        if (jfbcJQuery("input[name='create_new_users']:checked").val() == '1')
        {
            jfbcJQuery(".fullJoomla").each(function (i)
            {
                this.style.display = "block";
            });

            if (jfbcJQuery("input[name='registration_generate_username']:checked").val() == '1')
                jfbcJQuery(".autoUsername").css("display", "block");
            else
                jfbcJQuery(".autoUsername").css("display", "none");
        }
        else
        {
            jfbcJQuery(".autoUsername").css("display", "block");

            jfbcJQuery(".fullJoomla").each(function (i)
            {
                this.style.display = "none";
            });

        }
    }

    jfbcJQuery(document).ready(function ()
    {
        setupFields();
        jfbcJQuery("input[name='create_new_users']").click(function ()
        {
            setupFields();
        });
        jfbcJQuery("input[name='registration_generate_username']").click(function ()
        {
            setupFields();
        });
    });

</script>