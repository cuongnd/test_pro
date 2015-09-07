<div class="row">
    <div class="panel panel-primary toggle panelRefresh  showControls tab_booking_detail">
        <!-- Start .panel -->
        <div class=panel-heading>
            <div class=panel-heading-content></div>
        </div>
        <div class=panel-body>
            <div class="row">
                <div class="col-md-4 booking-details">
                    <fieldset>
                        <legend>BOOKING DETAILS</legend>
                        <form class="form-horizontal">
                            <div class="form-group">
                                <div class="col-md-3">
                                    <h6>Tour Name</h6>
                                </div>
                                <div class="col-md-9 booking-details-info">
                                    <h6><?php echo $this->data->title . '(' . $this->data->code . ')' ?></h6>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <h6>Depart City</h6>
                                </div>
                                <div class="col-md-9 booking-details-info">
                                    <h6>Dia Diem +  <?php echo JHtml::_('date', $this->data->start) ?></h6>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <h6>Tour Class</h6>
                                </div>
                                <div class="col-md-9 booking-details-info">
                                    <h6><?php echo $this->data->title_packagetype; ?></h6>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <h6>Customers</h6>
                                </div>
                                <div class="col-md-9 booking-details-info">
                                    <h6><i class="st-settings i-customer" title="edit"></i>

                                        <p class="customer"><?php echo $this->data->firstname . ' ' . $this->data->lastname; ?></p>
                                    </h6>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <h6>Passenger</h6>
                                </div>
                                <div class="col-md-9 booking-details-info">
                                    <h6>
                                        <?php
                                        $adult = $this->data->adult;
                                        $child = $this->data->child;
                                        $enfant = $this->data->enfant;
                                        $infant = $this->data->infant;
                                        if ($adult > 0) {
                                            $adult1 = "$adult adult";
                                        }
                                        if ($child > 0) {
                                            $child1 = ", $child child";
                                        }
                                        if ($enfant > 0) {
                                            $enfant1 = ", $enfant enfant";
                                        }
                                        if ($infant > 0) {
                                            $infant1 = ", $infant infant";
                                        }
                                        echo $adult + $child + $enfant + $infant;
                                        echo ' (' . $adult1 . $child1 . $enfant1 . $infant1 . ')';
                                        ?>
                                    </h6>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <h6>Room List</h6>
                                </div>
                                <div class="col-md-9 booking-details-info">
                                    <h6>
                                        <?php

                                        if (count($single) > 0) {
                                            echo count($single) . ' ' . $single[0];
                                        }
                                        if (count($twin) > 0) {
                                            echo ', ' . count($twin) . ' ' . $twin[0];
                                        }
                                        if (count($double) > 0) {
                                            echo ', ' . count($double) . $double[0];
                                        }
                                        if (count($triple) > 0) {
                                            echo ', ' . count($triple) . $triple[0];
                                        }
                                        ?>
                                    </h6>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <h6>Booked by</h6>
                                </div>
                                <div class="col-md-9 booking-details-info">
                                    <h6><?php echo JHtml::_('date', $this->order->created) ?></h6>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-3">
                                    <h6>Assigned</h6>
                                </div>
                                <div class="col-md-9 booking-details-info">
                                    <h6>
                                        <i class="st-settings i-assigned" title="edit"></i>

                                        <p class="assigned"><?php echo $this->data->assigned_name; ?></p>
                                    </h6>
                                </div>
                            </div>
                        </form>
                    </fieldset>
                </div>

                <div class="col-md-2 notes">
                    <fieldset>
                        <legend>NOTES<i class="fa-plus-sign add-note" data-toggle="modal"
                                        data-target=".bs-example-modal-sm"></i></legend>
                        <div>
                                <textarea disabled class="info-textarea"><?php echo trim($this->data->notes); ?>
                                </textarea>
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-3 documents">
                    <fieldset>
                        <legend>DOCUMENTS<i class="fa-plus-sign"></i></legend>
                        <ul class="info-documents">
                            <li>ITINERARY CREATION<i class="fa-plus-sign"></i></li>
                            <li>PRO FORMA INVOICE<i class="fa-plus-sign"></i></li>
                            <li>TOUR FINAL INVOICE<i class="fa-plus-sign"></i></li>
                            <li>VOUCHERS CREATION<i class="fa-plus-sign"></i></li>
                            <li>UPLOAD DOCUMENTS<i class="fa-plus-sign"></i></li>
                        </ul>
                    </fieldset>
                </div>

                <div class="col-md-3 transaction">
                    <fieldset>
                        <legend>TRANSACTION<i class="fa-plus-sign"></i></legend>
                        <div class="col-md-7">
                            <label><b>Total Value</b></label>
                            <label><b>Paid Amount</b>(30 Sep, 2014)</label>
                            <label><b>Remainding</b>(31 Jun, 2015)</label>
                        </div>
                        <div class="col-md-5">
                            <label class="price"><?php echo $this->data->total; ?> </label>
                            <label class="price">US$ 3000 </label>
                            <label class="price">US$ 12000</label>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>SET STATUS<i class="fa-plus-sign"></i></legend>
                        <div class="col-md-7">
                            <label>Current Status</label>
                        </div>
                        <div class="col-md-5">
                            <label
                                id="current-selected-paystatus"><?php echo $this->data->title_order_status ?></label>
                        </div>
                        <div class="col-md-12">
                            <?php
                            $cpayorderstatus = new BookProModelCpayorderstatus();
                            $paystatus = $cpayorderstatus->getListOrderstatus();
                            ?>
                            <select class="orderstatus">
                                <?php foreach ($paystatus as $item): ?>
                                    <option value="<?php echo $item->id; ?>"
                                            id="<?php echo $item->id; ?>" <?php if ($this->data->title_order_status == $item->title) echo "selected='selected'"; ?>><?php echo $item->title; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>


