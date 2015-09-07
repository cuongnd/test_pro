<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 3/24/2015
 * Time: 10:02 AM
 */


$lessInput=JPATH_ROOT.'/administrator/components/com_bookpro/assets/less/view-reservation-default.less';
$cssOutput=JPATH_ROOT.'/administrator/components/com_bookpro/assets/css/view-reservation-default.css';
BookProHelper::compileLess($lessInput,$cssOutput);
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root().'/administrator/components/com_bookpro/assets/css/view-reservation-default.css');


?>
<div class="col-md-12">
    <div class="row tab-infomation">
        <div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
            <ul id="myTab" class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#general" role="tab" id="general-tab" data-toggle="tab" aria-controls="home" aria-expanded="true">GENERAL</a></li>
                <li role="presentation" class=""><a href="#passenger" role="tab" id="passenger-tab" data-toggle="tab" aria-controls="profile" aria-expanded="false">PASSENGER</a></li>
                <li role="presentation" class=""><a href="#rooming" role="tab" id="rooming-tab" data-toggle="tab" aria-controls="rooming" aria-expanded="false">ROOMING</a></li>
                <li role="presentation" class=""><a href="#addons" role="tab" id="addons-tab" data-toggle="tab" aria-controls="addons" aria-expanded="false">ADD ONS</a></li>
                <li role="presentation" class=""><a href="#conversation" role="tab" id="conversation-tab" data-toggle="tab" aria-controls="conversation" aria-expanded="false">CONVERSATION</a></li>
                <li role="presentation" class=""><a href="#payment" role="tab" id="payment-tab" data-toggle="tab" aria-controls="payment" aria-expanded="false">PAYMENT</a></li>
                <li role="presentation" class=""><a href="#operation" role="tab" id="operation-tab" data-toggle="tab" aria-controls="operation" aria-expanded="false">OPERATION</a></li>

            </ul>
            <div id="myTabContent" class="tab-content">
                <!--info tab general-->
                <div role="tabpanel" class="col-md-12 tab-pane fade active in" id="general" aria-labelledby="reviews-tab">
                    <div class="col-md-12 wrapper-general">
                        <div class="col-md-4 booking-details">
                            <fieldset>
                                <legend>BOOKING DETAILS</legend>
                                    <div class="col-md-4">
                                        <h6>Tour Name</h6>
                                        <h6>Depart City</h6>
                                        <h6>Tour Class</h6>
                                        <h6>Customers</h6>
                                        <h6>h6assenger</h6>
                                        <h6>Room List</h6>
                                        <h6>Booked by</h6>
                                        <h6>Assigned</h6>
                                    </div>

                                <div class="col-md-8">
                                    <h6>Passion of Indochina (PI123456)</h6>
                                    <h6>Hanoi, Vietnam on 28 Sep,2015</h6>
                                    <h6>Standard Class</h6>
                                    <h6>Mr Peter Garbiel<i class="st-settings"></i></h6>
                                    <h6>7  ( 2 adults, 2 Teen, 3 Children )</h6>
                                    <h6>1 single, 1 double, 1 twin</h6>
                                    <h6>Aget 1  on 10 July,2015</h6>
                                    <h6>Nguyen Thu Hien<i class="st-settings"></i></h6>
                                </div>
                            </fieldset>
                        </div>

                        <div class="col-md-2 notes">
                            <fieldset>
                                <legend>NOTES<i class="fa-plus-sign"></i> </legend>
                                <textarea rows="11">
                                    1.This group has special requirement  on hotel room.
                                    2. Transport must be minivan.
                                    3. Food must be no pork
                                </textarea>
                            </fieldset>
                        </div>

                        <div class="col-md-3 documents">
                            <fieldset>
                                <legend>DOCUMENTS<i class="fa-plus-sign"></i> </legend>
                                <ul class="info-documents">
                                    <li>ITINERARY  CREATION<i class="fa-plus-sign"></i> </li>
                                    <li>PRO FORMA  INVOICE<i class="fa-plus-sign"></i> </li>
                                    <li>TOUR  FINAL INVOICE<i class="fa-plus-sign"></i> </li>
                                    <li>VOUCHERS CREATION<i class="fa-plus-sign"></i> </li>
                                    <li>UPLOAD DOCUMENTS<i class="fa-plus-sign"></i> </li>
                                </ul>
                            </fieldset>
                        </div>

                        <div class="col-md-3 transaction">
                            <fieldset>
                                <legend>TRANSACTION<i class="fa-plus-sign"></i> </legend>
                                <div class="col-md-7">
                                    <label><b>Total  Value</b></label>
                                    <label><b>Paid Amount</b>(30 Sep, 2014)</label>
                                    <label><b>Remainding</b>(31 Jun, 2015)</label>
                                </div>
                                <div class="col-md-5">
                                    <label class="price">US$ 15000 </label>
                                    <label class="price">US$ 3000 </label>
                                    <label class="price">US$ 12000</label>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>SET STATUS<i class="fa-plus-sign"></i> </legend>
                                <div class="col-md-7">
                                    <label>Current Status</label>
                                </div>
                                <div class="col-md-5">
                                    <label>Confirmed</label>
                                </div>
                                <div class="col-md-12">
                                    <select>
                                        <option value="1">finished/pending/cancel</option>
                                    </select>
                                </div>
                            </fieldset>
                        </div>


                        <div class="col-md-12 button">
                            <div class="add-adhoc"><h6><i class="fa-plus-sign"></i>Add  ad-hoc item</h6></div>
                            <div class="add-book"><h6><i class="fa-plus-sign"></i>Add  ad-hoc item</h6></div>
                            <div class="add-passenger"><h6><i class="fa-plus-sign"></i>Add  ad-hoc item</h6></div>
                            <div class="add-flight"><h6><i class="fa-plus-sign"></i>Add  ad-hoc item</h6></div>
                        </div>

                        <div class="col-md-12 table">
                            <table class="adminlist table-striped table sortingtable">
                                <thead>
                                <tr>
                                    <th colspan="10" style="background: #e8e8e8;">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th>ID<div class="icon-sorting" position="1"></div></th>
                                    <th>CUSTOMER NAME<div class="icon-sorting" position="1"></div></th>
                                    <th>TYPE<div class="icon-sorting" position="1"></div></th>
                                    <th>TOUR NAME<div class="icon-sorting" position="1"></div></th>
                                    <th>SER. CLASS<div class="icon-sorting" position="1"></div></th>
                                    <th>TOUR DATE<div class="icon-sorting" position="1"></div></th>
                                    <th>DEP. CODE<div class="icon-sorting" position="1"></div></th>
                                    <th>TOTAL VALUE<div class="icon-sorting" position="1"></div></th>
                                    <th>ASSIGN<div class="icon-sorting" position="1"></div></th>
                                    <th>ACTION <div class="icon-sorting" position="1"></div></th>
                                </tr>

                                </thead>

                                <tbody>
                                <tr>
                                    <td>12345</td>
                                    <td>Peter Garbiel</td>
                                    <td>Guest</td>
                                    <td>Passion of Asia</td>
                                    <td>Standard</td>
                                    <td>10 Oct. 2014</td>
                                    <td>PA12345</td>
                                    <td>$15000</td>
                                    <td>Hien</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="10" style="background: #ffffff;">&nbsp;</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>




                </div>
                <!--end info tab general-->





                <!--info tab passenger-->
                <div role="tabpanel" class="col-md-12 tab-pane fade" id="passenger" aria-labelledby="overview-tab">
                    sdc
                </div>
                <!--end tab passenger-->

                <!--info tab rooming-->
                <div role="tabpanel" class="col-md-12 tab-pane fade" id="itinerary" aria-labelledby="itinerary-tab">
sadsad

                </div>
                <!--end tab rooming-->


                <!--tab price-addons-->
                <div role="tabpanel" class="col-md-12 tab-pane fade" id="price-date" aria-labelledby="price-date-tab">
111
                </div>
                <!--end tab addons-->

                <!---tab conversation-->
                <div role="tabpanel" class="col-md-12 tab-pane fade" id="documents" aria-labelledby="documents-tab">
444
                </div>
                <!---end tab conversation-->

                <!---tab payment-->
                <div role="tabpanel" class="col-md-12 tab-pane fade" id="documents" aria-labelledby="documents-tab">

                </div>
                <!---end tab payment-->

                <!---tab operation-->
                <div role="tabpanel" class="col-md-12 tab-pane fade" id="documents" aria-labelledby="documents-tab">

                </div>
                <!---end tab operation-->

            </div>

        </div>
    </div>
</div>