
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
AImporter::helper('currency', 'form');
$document = JFactory::getDocument();
JHtmlBehavior::framework();
JHtml::_('jquery.ui');
JHtml::_('jquery.framework');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
JHtmlBehavior::formvalidation();
$config = AFactory::getConfig();

$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);
$document = JFactory::getDocument();
$document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js");
if ($local != 'en') {
    $document->addScript(JURI::root() . 'components/com_bookpro/assets/js/validatei18n/messages_' . $local . '.js');
}
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/jquery-ui.css');
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui.js');
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui-timepicker-addon.js');

$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/view-tourbook.css');
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/view-order.css');
?>

<style type="text/css">
    label.error{
        color: red;
        font-style: italic;
    }
    .form-horizontal .control-label
    {
        width: auto;
        padding-right: 5px;


    }
    .form-horizontal .controls 
    {
        margin-left: auto;
    }
    .form-horizontal .controls input
    {
        width: auto;
        padding: 4px 2px;
    }
    .form-horizontal .controls input.bridthday
    {
        width: 105px;
    }
    .form-horizontal.airpost_transfer input
    {
        width: 70px !important;
    }

</style>




<div class="form-horizontal">
    <h3><?php echo JText::_('COM_BOOKPRO_PASSENGER_INFOMATION') ?></h3>
    <div><?php echo JText::_('COM_BOOKPRO_PASSENGER_INFOMATION_DESCRIPTION') ?></div>
    <?php echo $this->loadTemplate("passengeritem") ?>
</div>













<style type="text/css">
    .form-horizontal .control-label
    {
        width: auto;
        text-align: left;


    }
    .form-horizontal .controls 
    {
        margin-left: auto;
    }
    .form-horizontal .controls input
    {
        width: auto;
        padding: 4px 2px;
    }
    .form-horizontal .controls input.bridthday
    {
        width: 105px;
    }
    .block_right ul
    {
        list-style: none;
    }
</style>

<script type="text/javascript">
    jQuery(document).ready(function($) {


        $(document).on('click', 'ul.passengers a.passenger_edit', function() {
            $li_passenger = $(this).closest('li.passenger');
            $indexoflipassenger = $li_passenger.index();
            $('div.passenger_form').each(function($index) {

                if ($indexoflipassenger == $index)
                {
                    $(this).css({
                        display: "block"
                    });
                }
                else
                {
                    $(this).css({
                        display: "none"
                    });
                }
            });

        });
        function stylewidthcontrol($object)
        {
            $maxwidth = 0;
            $object.find('.control-group .control-label').each(function($index) {
                if ($maxwidth < $(this).width())
                    $maxwidth = $(this).width();
            });
            $object.find('.control-group .control-label').css({
                width: $maxwidth + 10
            });
        }
        stylewidthcontrol($('.passenger_form'));

        $('#frontTourForm').validate({// initialize the plugin
           

        });
    });

</script>



