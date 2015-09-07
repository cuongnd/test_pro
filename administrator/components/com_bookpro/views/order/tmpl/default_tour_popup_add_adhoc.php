<?php
AImporter::model("curency");
?>
<!--Jquery date picker-->
<script>
    jQuery(document).ready(function ($) {
        $("input.datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            minDate: +0,
            maxDate: "+10M +10D",
            //yearRange: "-100:+0",  //từ 2014 ->1914
            showOn: "button",
            showButtonPanel: true,
            buttonImage: "<?php echo JUri::base(); ?>components/com_bookpro/assets/images/calendar.png",
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true
        });
    });
</script>

<script>
    jQuery(document).ready(function ($) {
        // When click save
        $("#add_adhoc_process").click(function () {
            order_id = "<?php echo JFactory::getApplication()->input->get('cid', 0); ?>";
            service_name = $("#service_name").val();
            supplier_name = $("#selectSup").val();
            destination = $("#selectDes").val();
            start_date = $("#start_date").val();
            finish_date = $("#finish_date").val();
            reference_no = $("#reference-no").val();
            assign_to = $("#assign-to").val();
            service_details = $("#service-details").val();
            conditions_terms = $("#conditions_terms").val();
            currency = $("#currency").val();
            unit = $("#unit").val();
            price = $("#price").val();
            net = $("#net").val();
            margin = $("#margin").val();
            payment_methods = $(".payment_methods:checked").val();

            listId = {};
            var i = 0;
            $(".select-passenger").find(".id_passenger").each(function () {
                if ($(this).is(':checked')) {
                    listId[i] = $(this).val();
                    i++;
                    id_passenger = $(this).val();
                }
            });

            $.ajax({
                method: "POST",
                url: "index.php?option=com_bookpro&task=adhoc.addadhocajax",

//                     data: (function () {
//                         basePost = {
//                             option: 'com_bookpro',
//                             controller: 'order',
//                             task: 'ajax_add_passenger'
//                         };
//                         dataPost = $.param(basePost) + '&' + data_passenger;
//                         return dataPost;
//                     })(),

                data: (function () {
                    data = {
                        ad_hoc: {
                            order_id: order_id,
                            service_name: service_name,
                            supplier_name: supplier_name,
                            destination: destination,
                            start_date: start_date,
                            finish_date: finish_date,
                            reference_no: reference_no,
                            assign_to: assign_to,
                            service_details: service_details,
                            conditions_terms: conditions_terms,
                            currency: currency,
                            unit: unit,
                            price: price,
                            net: net,
                            margin: margin,
                            payment_methods: payment_methods
                        },
                        adhoc_passenger: {
                            passenger_id: listId
                        }
                    };
                    return data;
                })(),
//                      data= {
//
//                         rows: [{
//                                order_id:order_id,
//                                service_name:service_name,
//                                supplier_name:supplier_name,
//                                destination:destination,
//                                start_date:start_date,
//                                finish_date:finish_date,
//                                reference_no:reference_no,
//                                assign_to:assign_to,
//                                service_details:service_details,
//                                conditions_terms:conditions_terms,
//                             }],
//                         test1:{
//                             assign_to:assign_to,
//                             service_details:service_details,
//                             conditions_terms:conditions_terms,
//                         }
//                     };
                dataType: "text",
                beforeSend: function () {
                    $("#wait").css("display", "block");
                },
                success: function () {
                    $("#wait").css("display", "none");
                    alert("success");
                    //   $('.bookpro-modal input,textarea,select').val('');
                    // $('.bookpro-modal').css("display","none");
                    $('#add_adhoc_cancel').click();

                },
                error: function () {
                    alert('Error');
                }
            });
        });


        function changeTotalPrice() {
            margin = 0;
            net = 0;
            margin = parseInt($("#margin").val());
            net = parseInt($("#net").val());
            price = margin + net;
            $("#price").val(price);
            currency = $("#currency").val();
            currency = $("#" + currency).text();
            total = price;
            total = currency + ' ' + total;
            $(".totalprice").text('( ' + total + ' )');
        }

        function changePricePassenger() {
            pricetype = $(".payment_methods:checked").val();
            lengchecked = $(".id_passenger:checked").length;
            pricepassenger = price / lengchecked;

            if (pricetype == "PRICE/GROUP") {
                $(".select-passenger").find(".id_passenger").each(function () {
                    $(this).next().text("");
                    if ($(this).is(':checked')) {
                        $(this).next().text("").text('( ' + currency + ' ' + price + ' )');
                    }
                });
            } else if (pricetype == "PRICE/PERSON") {
                $(".select-passenger").find(".id_passenger").each(function () {
                    $(this).next().text("");
                    if ($(this).is(':checked')) {
                        $(this).next().text("").text('( ' + currency + ' ' + pricepassenger + ' )');
                    }
                });
            }
        }

        changeTotalPrice();
        changePricePassenger();
        $(".payment_methods,.id_passenger").on("click", changePricePassenger);
        $("#margin,#net").on("keyup", changeTotalPrice);
        $("#margin,#net").on("keyup", changePricePassenger);
        $("#currency").on("change", changeTotalPrice);
        $("#currency").on("change", changePricePassenger);
        $("#price").on("keyup", changePricePassenger);

    });
</script>

<!-- Modal -->
<div class="modal fade" id="tour_popup_add_adhoc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add hoc item</h4>
            </div>
            <div class="modal-body add-hoc-item">
                <div class="container-fluid">
                    <div class="col-md-6">
                        <div class="container-fluid">
                             <div class="row">
                                 <div class="form-group">
                                     <label for="selectSup"><?php echo JText::_('Servic Name'); ?></label>
                                     <select class="form-control" name="selectSup" id="selectSup">
                                         <?php
                                         AImporter::model('suppliers');
                                         $modelSup = new BookProModelSuppliers();
                                         $selectSup = $modelSup->getData();
                                         ?>
                                         <?php foreach ($selectSup as $item): ?>
                                             <option value="<?php echo $item->id; ?>"><?php echo $item->company; ?></option>
                                         <?php endforeach; ?>
                                     </select>
                                 </div>
                                 <div class="form-group">
                                     <label for="selectDes"><?php echo JText::_('Select Destination'); ?></label>
                                     <?php
                                     $modelDes = new BookProModelDestinations();
                                     $selectDes = $modelDes->getFullDes();
                                     ?>
                                     <select class="form-control" name="selectDes" id="selectDes">
                                         <?php foreach ($selectDes as $item): ?>
                                             <option value="<?php echo $item->id; ?>"><?php echo $item->title; ?></option>
                                         <?php endforeach; ?>
                                     </select>
                                 </div>

                             </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="selectDes"><?php echo JText::_('Start Date'); ?></label>
                                        <input class="form-control" type="text" class="datepicker" id="start_date">
                                    </div>
                                    <div class="form-group">
                                        <label for="selectDes"><?php echo JText::_('Finish Date'); ?></label>
                                        <input class="form-control" type="text" class="datepicker" id="finish_date">
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="selectDes"><?php echo JText::_('Reference No'); ?></label>
                                        <input class="form-control" type="text" name="reference-no" id="reference-no">
                                    </div>
                                    <div class="form-group">
                                        <label for="selectDes"><?php echo JText::_('Assign To'); ?></label>
                                        <select class="form-control" name="assign_id" id="assign_id">
                                            <?php
                                            $model = new BookProModelUser();
                                            $dataUsers = $model->getItems();
                                            ?>
                                            <?php foreach ($dataUsers as $item): ?>
                                                <option value="<?php echo $item->id; ?>"><?php echo $item->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="selectDes"><?php echo JText::_('Service Details'); ?></label>
                            <textarea class="form-control" id="service-details"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="selectDes"><?php echo JText::_('Conditions & Terms'); ?></label>
                            <textarea class="form-control" id="conditions_terms"></textarea>
                        </div>
                    </div>
                </div>


                <div class="container-fluid price">
                    <div class="col-md-3">
                        <h5>
                            <input type="radio" class="payment_methods noStyle" name="payment_methods"
                                   value="PRICE/GROUP">
                            <?php echo JText::_('PRICE/GROUP'); ?>
                        </h5>
                    </div>
                    <div class="col-md-3">
                        <h5>
                            <input type="radio" class="payment_methods noStyle" name="payment_methods"
                                   value="PRICE/PERSON">
                            <?php echo JText::_('PRICE/PERSON'); ?>
                        </h5>
                    </div>
                </div>

                <div class="container-fluid service">
                    <div class="container-fluid">
                        <div class="col-md-4">
                            <h5>SERVICE NAME</h5>

                            <p>Cooking Class in Hoian</p>
                        </div>

                        <div class="col-md-8">
                            <?php
                            $model = new BookProModelCurency();
                            $data = $model->getItems();
                            ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <h5>CURRENCY</h5>
                                    <select id="currency">

                                        <?php foreach ($data as $item): ?>
                                            <option value="<?php echo $item->id; ?>"
                                                    id="<?php echo $item->id; ?>"><?php echo $item->code; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <h5>UNIT</h5>
                                    <input type="text" id="unit" value="1" disabled>
                                </div>
                                <div class="col-md-2">
                                    <h5>PRICE</h5>
                                    <input type="text" id="price" value="0" disabled>
                                </div>
                                <div class="col-md-2">
                                    <h5>NET</h5>
                                    <input type="text" id="net" value="0">
                                </div>
                                <div class="col-md-3">
                                    <h5>MARGIN</h5>
                                    <input type="text" id="margin" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="col-md-6 col-md-offset-6 class-border">&nbsp;</div>
                        <div class="col-md-4 col-md-offset-8"><p>TOTAL PRICE PER UNIT:<strong class="totalprice"
                                                                                              style="font-weight:normal;"></strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="col-md-7">
                        <h5><?php echo JText::_('DETAILS'); ?></h5>

                        <p>Cooking class in Hoian on 05 July, 2015</p>

                        <div class="col-md-6 col-md-offset-4"><?php echo JText::_('TOTAL COST'); ?><strong
                                style="font-weight:normal;" class="totalprice"></strong></div>
                    </div>
                    <div class="col-md-5 select-passenger">
                        <h5><?php echo JText::_('SELECT PASSENGER'); ?></h5>
                        <?php foreach ($this->data->data_passenger as $item): ?>
                            <div class="col-md-12">
                                <p class="info-passenger-price">
                                    <input type="checkbox" class="id_passenger noStyle" value="<?php echo $item->id; ?>"
                                           style="margin-right:5px;"><?php echo $item->firstname . ' ' . $item->lastname; ?>
                                    <strong id="price-change" style="font-weight: normal;"></strong>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


