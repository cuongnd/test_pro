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
            <input type="text" class="form-control" id="exampleInputName2" placeholder="From: City name, airport">
        </div>
        <div class="form-group col-md-3">
            <span class="glyphicon glyphicon-map-marker"></span>
            <input type="text" class="form-control" id="exampleInputName2" placeholder="To: City name, airport">
        </div>
        <div class="form-group col-md-3">
            <span class="glyphicon glyphicon-calendar"></span>
            <input type="text" class="form-control" id="exampleInputName2" placeholder="Depart date">
        </div>
        <div class="form-group col-md-3">
            <span class="glyphicon glyphicon-calendar"></span>
            <input type="text" class="form-control" id="exampleInputName2" placeholder="Return date">
        </div>
    </div>
    <div class="row col-md-12" style="margin: 10px 0">
        <div class="col-md-4">

        </div>
        <div class="col-md-4">
            <table>
                <tr>
                    <td style="padding: 5px 10px">Adults( 12+ )</td>
                    <td style="padding: 5px 10px">Child( 2-11 )</td>
                    <td style="padding: 5px 10px">Infant( <2)</td>
                </tr>
                <tr>
                    <td style="padding: 0px 10px">
                        <select class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </td>
                    <td style="padding: 0px 10px">
                        <select class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </td>
                    <td style="padding: 0px 10px">
                        <select class="form-control">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-4">
            <a href="" style="float: right; margin-right: 60px; color: red;margin-top: 15px;font-size: 18px">GO&nbsp;<span class="glyphicon glyphicon-play-circle"></span> </a>
        </div>
    </div>

    <input type="hidden" name="option" value="com_bookpro">
    <input type="hidden" name="controller" value="flight">
    <input type="hidden" name="task" value="searchadv">
    <?php echo JHtmlForm::token() ?>
</form>
