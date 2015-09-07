<?php
defined('_JEXEC') or die('Restricted access');
AImporter::model('orderinfos', 'hotel', 'rooms', 'room');
AImporter::helper('hotel', 'date', 'currency');
$infos = $displayData;
$infos = $infos[0] ? $infos : array($infos);
$checkin_date = $infos['arrivalDate'];
$checkout_date = $infos['departureDate'];
?>

<div class="row-fluid">
    <?php $rankstar = JURI::root() . "/components/com_bookpro/assets/images/" . $hotel->rank . 'star.png'; ?>
    <h2 class="hoteltitle-large"><?php echo $infos['Hotel']['name'] ?>
        <span><img src="<?php echo $infos['Hotel']['hotelRating']; ?>"> </span>
    </h2>

    <p><?php echo $infos['Hotel']['address1'] ?></p>

    <p><?php echo $infos['Hotel']['address2'] ?></p>

    <div class="form-inline">
        <label><i class="icon-large icon-calendar"></i><?php echo JText::sprintf('COM_BOOKPRO_HOTEL_CHECKIN_TXT', $checkin_date) ?></label>
        <label><i class="icon-large icon-calendar"></i><?php echo JText::sprintf('COM_BOOKPRO_HOTEL_CHECKOUT_TXT', $checkout_date) ?></label>
    </div>
    <?php foreach ($infos as $info) { ?>
        <label>
            <b><?php echo JText::sprintf('COM_BOOKPRO_NIGHT_NUMBER_TXT', $info['nights']) ?></b>
        </label>
        <label>
            <b><?php echo JText::sprintf('COM_BOOKPRO_TOTAL_ROOMS_TXT', $info['nights']) ?></b>
        </label>

        <label>
            <b><?php echo JText::sprintf('COM_BOOKPRO_TOTAL_ADULT_TXT', $info['numberOfAdults']) ?></b>
        </label>

        <label>
            <b><?php echo JText::sprintf('COM_BOOKPRO_TOTAL_CHILD_TXT', $info['numberOfChildren']) ?></b>
        </label>
    <?php } ?>
</div>
