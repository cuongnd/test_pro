<?php
defined('_JEXEC') or die('Restricted access');
$config = AFactory::getConfig();
?>

<div class="row-fluid">

    <div class="span12">

        <div class="span8">
                <?php $rankstar = JURI::base() . "/components/com_bookpro/assets/images/" . $displayData->rank . 'star.png'; ?>
            <h2 class="hoteltitle">
            <?php echo $displayData->title ?>
                <span><img src="<?php echo $rankstar; ?>"> </span>
            </h2>
            <div class="hoteladd">
                <span class ="address"><?php echo $displayData->address1 . ', ' . $displayData->city_title ?></span>
                <a
                    href="index.php?option=com_bookpro&task=displaymap&tmpl=component&hotel_id=<?php echo $displayData->id ?>"
                    class='modal_hotel'
                    rel="{handler: 'iframe', size: {x: 501, y: 460}}"><?php echo JText::_("COM_BOOKPRO_VIEW_MAP") ?>
                </a>
            </div>
        </div>
        <div class="span4">
                <?php if ($this->event->afterDisplayTitle) { ?>
                <div class="pull-right">
                <?php echo $this->event->afterDisplayTitle ?>
                </div>
<?php } ?>
        </div>

    </div>

</div>
