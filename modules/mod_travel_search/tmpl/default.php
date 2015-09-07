<?php
JHtml::_('jquery.framework');
JHtml::_('bootstrap.framework');

defined('_JEXEC') or die;
?>
<div class="row tab-seach">
    <h3 class="pull-left">Search:</h3>
    <ul class="nav pull-left" role="tablist" id="tab_search_<?php echo $module->id ?>">
        <?php if ($tour) { ?>
            <li role="presentation" class="active"><a href="#tour" aria-controls="tour" role="tab" data-toggle="tab">Tours</a></li>
        <?php }?>
        <?php if ($hotel) { ?>
            <li role="presentation"><a href="#hotel" aria-controls="hotel" role="tab" data-toggle="tab">Hotels</a></li>
        <?php }?>
        <?php if ($flight) { ?>
            <li role="presentation"><a href="#flight" aria-controls="flight" role="tab" data-toggle="tab">Flights</a></li>
        <?php }?>
        <?php if ($car) { ?>
            <li role="presentation"><a href="#car" aria-controls="car" role="tab" data-toggle="tab">Cars</a></li>
        <?php } ?>
    </ul>

    <div class="pull-right radio_check">
        <label style="border-right: 1px #ccc solid;padding: 0 10px">
            <input type="radio" name="" id="" value="" class="noStyle">
            One way
        </label>
        <label>
            Round trip
            <input type="radio" name="" id="" value="" class="noStyle">
        </label>
    </div>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tour">
            <?php
            require_once  dirname(__FILE__).'/default_tour.php';
            ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="hotel">
            <?php
            require_once  dirname(__FILE__).'/default_hotel.php';
            ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="flight">
            <?php
            require_once  dirname(__FILE__).'/default_flight.php';
            ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="car">
            <?php
            require_once  dirname(__FILE__).'/default_car.php';
            ?>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#tab_search_<?php echo $module->id ?> a:last').tab('show')
    })
</script>

