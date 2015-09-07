<?php
/**
 * Created by PhpStorm.
 * User: THANHTIN
 * Date: 5/20/2015
 * Time: 10:57 AM
 */
defined('_JEXEC') or die('Restricted access');


$app = JFactory::getApplication();
$doc = JFactory::getDocument();
JHtml::_('behavior.framework');



?>
<form action='<?php echo JRoute::_($action) ?>' method="post" id="frm_tour_search" name="tour_search" class="form-inline">
    <div class="row col-md-12">
        <div class="form-group col-md-3">
            <span class="glyphicon glyphicon-map-marker"></span>
            <input type="text" class="form-control" id="exampleInputName2" placeholder="Enter destination, hotel name">
        </div>
        <div class="form-group col-md-3">
            <span class="glyphicon glyphicon-calendar"></span>
            <input type="text" class="form-control" id="exampleInputName2" placeholder="Depart date">
        </div>
        <div class="form-group col-md-3">
            <span class="glyphicon glyphicon-calendar"></span>
            <input type="text" class="form-control" id="exampleInputName2" placeholder="Return date">
        </div>
        <div class="form-group col-md-3">
            <a href="" style="float: right; margin-right: 60px; color: red;margin-top: 15px;font-size: 18px">GO&nbsp;<span class="glyphicon glyphicon-play-circle"></span> </a>
        </div>
    </div>

    <input type="hidden" name="option" value="com_bookpro">
    <input type="hidden" name="controller" value="hotel">
    <input type="hidden" name="task" value="searchadv">
    <?php echo JHtmlForm::token() ?>
</form>
