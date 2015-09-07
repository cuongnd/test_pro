<script>
    jQuery(document).ready(function ($) {
        function changeSelect() {
            id = $("#addone_id").val();
            $.ajax({
                method: "POST",
                url: "index.php?option=com_bookpro&task=addons.ajaxGetAgentByAddon&tmpl=component",
                //url: "index.php?option=com_bookpro&controller=addons&task=ajaxGetAgentByAddon&tmpl=component",
                data: {
                    id: id
                },
                dataType: "text",
                beforeSend: function () {
                    $("#agent_id").html("<option>loading...</option>");
                },
                success: function (data) {
                    $("#agent_id").html(data);
                },
                error: function () {
                    alert('Error');
                }

            })
        }

        $(".add-book").on("click", changeSelect)
        $("#addone_id").on("change", changeSelect);

        $("#add_addons_process").click(function () {
            data = $.param($(".wrapper-add-addons").find(':input'), false);
            //data2 = $.param($(".wrapper-add-addons").find(':input:checked'), false);
            //$(".wrapper-add-addons").find(":input:checked").each(function () {
            // data2=$.param($(this),false);
            //});
            data2 = {};
            var i = 0;
            $(".wrapper-add-addons").find(":input:checked").each(function () {
                data2[i] = {
                    id: $(this).val(),
                    title: $(this).next().text(),

                };
                i++;


            });
            $.ajax({
                method: "POST",
                url: "index.php?option=com_bookpro&task=addons.ajaxAddAddon&tmpl=component",

                data: data2,

                dataType: "text",
                beforeSend: function () {
                    $(".wait").css({"display": "block"});
                },
                success: function () {
                    $(".wait").css("display", "none");
                    alert("Success");
                },
                error: function () {
                    alert('Error');
                }

            })
        })

    })
</script>

<?php
AImporter::model('addons');
$model = new BookProModelAddons();
$listAddon = $model->getFullAddons();
?>

<div class="col-md-12 wrapper-add-addons">
    <fieldset>
        <legend><?php echo JText::_('GENERAL'); ?></legend>
        <div class="col-md-6 info-left">
            <div class="col-md-12">
                <h6><?php echo JText::_('Service Name'); ?></h6>

                <select name="addone_id" id="addone_id">
                    <option value="0">__SelectService__</option>
                    <?php foreach ($listAddon as $item): ?>
                        <option value="<?php echo $item->id; ?>"
                                id="<?php echo $item->id; ?>"><?php echo $item->title; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-12">
                <h6><?php echo JText::_('Supplier Name'); ?></h6>
                <select name="agent_id" id="agent_id">
                    <?php
                    $modelSup = new BookProModelAgents();
                    $selectSup = $modelSup->getData();
                    ?>
                    <?php foreach ($selectSup as $item): ?>
                        <option
                            value="<?php echo $item->id; ?>"><?php echo $item->company; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-12">
                <h6><?php echo JText::_('Select Destination'); ?></h6>
                <?php
                $modelDes = new BookProModelDestinations();
                $selectDes = $modelDes->getFullDes();
                ?>
                <select name="selectDes" id="selectDes">
                    <?php foreach ($selectDes as $item): ?>
                        <option
                            value="<?php echo $item->id; ?>"><?php echo $item->title; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>


            <div class="col-md-12">
                <h6><?php echo JText::_('Service Date'); ?></h6>
                <input type="text" class="datepicker" id="service_date"
                       name="service_date">
            </div>

            <div class="col-md-6 reference-no">
                <h6><?php echo JText::_('Reference No'); ?></h6>
                <input type="text" name="reference-no" id="reference-no">
            </div>
            <div class="col-md-6 assign-to">
                <h6><?php echo JText::_('Assign To'); ?></h6>
                <input type="text" name="assign-to" id="assign-to">
            </div>

        </div>
        <div class="col-md-6 info-right">
            <h6><?php echo JText::_('Service Description'); ?></h6>
            <textarea id="service_des" name="service_des"></textarea>
            <h6><?php echo JText::_('Service Notes'); ?></h6>
            <textarea id="service-notes" name="service-notes"></textarea>
        </div>
    </fieldset>

    <fieldset id="lastfieldset">
        <div class="col-md-6 col-md-offset-6"><h5>SELECT PASSENGER</h5></div>
        <div class="col-md-6">
            <div class="col-md-12"><h5>DETAILS:</h5></div>
            <div class="col-md-12"><p>Transfer from airport to hotel in Hanoi , Vietnam
                    on 05 July
                    2015</p></div>
            <div class="col-md-7 col-md-offset-3"><h5>TOTAL COST:</h5></div>
        </div>
        <div class="col-md-6">
            <div class="col-md-12">
                <div class="col-md-6"></div>
                <div class="col-md-3">Flight No</div>
                <div class="col-md-3 arival-time-text">Arival time</div>
            </div>
            <div class="col-md-12" style="float:left;">
                <?php foreach ($this->data->data_passenger as $item): ?>
                    <div class="form-group">
                        <div class="col-md-6">
                            <p class="info-passenger-addon">
                                <input type="checkbox" class="id_passenger noStyle"
                                       name="id_passenger"
                                       value="<?php echo $item->id; ?>"
                                       style="margin-right:5px;"><?php echo $item->firstname . ' ' . $item->lastname; ?>
                            </p>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="flight-no">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="arrival-time">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </fieldset>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"
            id="add_passenger_cancel"><?php echo JText::_('Close'); ?></button>
    <button type="button" class="btn btn-primary"
            id="add_addons_process"><?php echo JText::_('Save'); ?></button>
</div>