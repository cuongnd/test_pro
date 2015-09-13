<?php die("Access Denied"); ?>#x#a:2:{s:6:"output";a:2:{s:4:"body";s:0:"";s:4:"head";a:2:{s:11:"styleSheets";a:1:{s:83:"http://etravelservice.com:81/modules/mod_travel_search/assets/css/travel_search.css";a:3:{s:4:"mime";s:8:"text/css";s:5:"media";N;s:7:"attribs";a:0:{}}}s:7:"scripts";a:3:{s:74:"http://etravelservice.com:81/modules/mod_travel_search/assets/js/helper.js";a:4:{s:11:"callingFile";s:90:"Calling file: H:\project\test_pro\modules\mod_travel_search\mod_travel_search.php line  14";s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}s:33:"/media/system/js/mootools-core.js";a:4:{s:11:"callingFile";s:71:"Calling file: H:\project\test_pro\libraries\cms\html\html.php line  707";s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}s:37:"/media/system/js/core-uncompressed.js";a:4:{s:11:"callingFile";s:71:"Calling file: H:\project\test_pro\libraries\cms\html\html.php line  707";s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}}}}s:6:"result";s:15213:"	<div class="row tab-seach">
    <h3 class="pull-left">Search:</h3>
    <ul class="nav pull-left" role="tablist" id="tab_search_1070">
                    <li role="presentation" class="active"><a href="#tour" aria-controls="tour" role="tab" data-toggle="tab">Tours</a></li>
                            <li role="presentation"><a href="#hotel" aria-controls="hotel" role="tab" data-toggle="tab">Hotels</a></li>
                            <li role="presentation"><a href="#flight" aria-controls="flight" role="tab" data-toggle="tab">Flights</a></li>
                            <li role="presentation"><a href="#car" aria-controls="car" role="tab" data-toggle="tab">Cars</a></li>
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
            <form class="form-inline" action='/component/bookpro/?view=tour&amp;Itemid=' method="post" id="frm_tour_search"
      name="tour_search">
    <div class="row col-md-12">
                    <div class="form-group col-md-3">
                <!--<span class="glyphicon glyphicon-pencil"></span>-->
                <input type="text" class="form-control" value=""
                       placeholder="keyword">
            </div>
        
                    <div class="form-group col-md-3">
                <span class="glyphicon glyphicon-th"></span>
                <select id="cat_id" name="cat_id" class="form-control">
	<option value="" selected="selected">MOD_TRAVEL_SEARCH_SELECT_CATEGORY</option>
	<option value="25">Cruising</option>
	<option value="40">Cuisines</option>
	<option value="41">Adventures</option>
	<option value="42">Beaches &amp; Sun</option>
</select>
            </div>
        
                    <div class="form-group col-md-3">
                <span class="glyphicon glyphicon-th"></span>
                <select id="duration" name="duration" class="form-control">
	<option value="" selected="selected">MOD_TRAVEL_SEARCH_DURATION_SELECT</option>
	<option value="0">MOD_TRAVEL_SEARCH_EXCURSION</option>
	<option value="2">2 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="3">3 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="4">4 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="5">5 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="6">6 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="7">7 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="8">8 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="9">9 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="10">10 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="11">11 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="12">12 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="13">13 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="14">14 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="15">15 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="16">16 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="17">17 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="18">18 MOD_TRAVEL_SEARCH_DAYS</option>
	<option value="19">19 MOD_TRAVEL_SEARCH_DAYS</option>
</select>
            </div>
        
                <div class="col-md-4"></div>
                <div class="col-md-5" style="margin-bottom: 10px">
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
        <div class="col-md-3">
            <a href=""
               style="float: right; margin-right: 60px; color: red;margin-top: 15px;font-size: 18px">GO&nbsp;<span
                    class="glyphicon glyphicon-play-circle"></span> </a>
        </div>
    </div>
    <input type="hidden" name="option" value="com_bookpro">
    <input type="hidden" name="controller" value="tour">
    <input type="hidden" name="task" value="searchadv">
    <input type="hidden" name="843cb6dbd7419a1b3a5e64f8047656f7" value="1" /></form>

<script type="text/javascript">
    jQuery(document).ready(function ($) {

        $("#btn-toursearch").click(function () {
            $("#frm_tour_search").submit();
        });

        returnDestinations();

        $("select#country_id").change(function () {
            returnDestinations();
        });

        function returnDestinations() {
            $selected_country = $("select#country_id").val();
            if ($selected_country > 0) {
                $.ajax({
                    type: "GET",
                    url: "index.php?option=com_bookpro&controller=customer&task=getcity&format=raw",
                    data: "country_id=" + $selected_country,
                    beforeSend: function () {
                        $("select#dest_id")
                            .html('<option>MOD_LOADING</option>');
                    },
                    success: function (result) {
                        $("select#dest_id").html(result);
                        $("select#dest_id").val("");
                    }
                });

            }
        }
    });
</script>
        </div>
        <div role="tabpanel" class="tab-pane" id="hotel">
            <form action='/component/bookpro/?view=tour&amp;Itemid=' method="post" id="frm_tour_search" name="tour_search" class="form-inline">
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
    <input type="hidden" name="843cb6dbd7419a1b3a5e64f8047656f7" value="1" /></form>
        </div>
        <div role="tabpanel" class="tab-pane" id="flight">
            <form action='/component/bookpro/?view=tour&amp;Itemid=' method="post" id="frm_tour_search" name="tour_search" class="form-inline">
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
    <input type="hidden" name="843cb6dbd7419a1b3a5e64f8047656f7" value="1" /></form>
        </div>
        <div role="tabpanel" class="tab-pane" id="car">
            <form action='/component/bookpro/?view=tour&amp;Itemid=' method="post" id="frm_tour_search" name="tour_search" class="form-inline">
    <div class="row col-md-12">
        <div class="form-group col-md-3">
            <span class="glyphicon glyphicon-map-marker"></span>
            <input type="text" class="form-control" id="exampleInputName2" placeholder="From...">
        </div>
        <div class="form-group col-md-3">
            <span class="glyphicon glyphicon-map-marker"></span>
            <input type="text" class="form-control" id="exampleInputName2" placeholder="To...">
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
                    <td style="padding: 5px 10px">Adults</td>
                    <td style="padding: 5px 10px">Children</td>
                    <td style="padding: 5px 10px">Seniors</td>
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
    <input type="hidden" name="843cb6dbd7419a1b3a5e64f8047656f7" value="1" /></form>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#tab_search_1070 a:last').tab('show')
    })
</script>

";}