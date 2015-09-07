<?php
AImporter::model("passenger",'countries');
$id = JFactory::getApplication()->input->get('cid', 0);
$model= new BookProModelPassenger();
$dataLeader=$model->getPassengerLeaderByOrderid($id);

$model = new BookProModelCountries();
$listCountry = $model->getFullItems();
?>


<script type="text/javascript">
    jQuery(document).ready(function($){
        $("#add_passenger_process").click(function(){
            data_passenger=$.param( $(".wrapper-add-passenger").find(':input'), false);

            data_passenger2=$.param( $(".wrapper-add-passenger").find(':input:checked'), false);
            data_passenger+=data_passenger2!=''?'&'+data_passenger2:'';
//           a=$.parseParams(data_passenger);
//            console.log(a);

            $.ajax({
                method: "POST",
                //url: "index.php?option=com_bookpro&controller=order&task=ajax_add_passenger",
                url: "index.php",
                data:(function () {
                    basePost={
                        option:'com_bookpro',
                        task:'order.ajax_add_passenger'
                    };
                    dataPost= $.param(basePost)+'&'+data_passenger;
                    return dataPost;
                })(),

                dataType: "text",
                beforeSend: function () {
                    $(".wait").css({"display":"block"});
                },
                success: function () {
                    $(".wait").css("display","none");
                    alert("Success");
                },
                error: function () {
                    alert('Error');
                }

            })
        })

        function showHidePreExisting(){
            if($(".fill-conditions:checked").val()=="yes"){
                $(".warning-info-pre-existing").find("label").css("display","none");
                $(".warning-info-pre-existing").find("textarea").css("display","block");
            }else{
                $(".warning-info-pre-existing").find("textarea").css("display","none");
                $(".warning-info-pre-existing").find("label").css("display","block");
            }
        }
        function showHideRequirements(){
            if($(".fill-requirements:checked").val()=="yes"){
                $(".warning-info-special").find("label").css("display","none");
                $(".warning-info-special").find("textarea").css("display","block");
            }else{
                $(".warning-info-special").find("textarea").css("display","none");
                $(".warning-info-special").find("label").css("display","block");
            }
        }
        showHidePreExisting();
        $(".fill-conditions").on("click",showHidePreExisting);
        showHideRequirements();
        $(".fill-requirements").on("click",showHideRequirements);


        $(".radio-leader").click(function(){
           if($(".radio-leader:checked").val()=="yes"){
                $("#postcode_zip").val("<?php echo $dataLeader->postcode_zip; ?>").attr("disabled","disabled");
                $("#country1").val("<?php echo $dataLeader->country_id; ?>").attr("disabled","disabled");
                $("#emergency_name").val("<?php echo $dataLeader->firstname.' '.$dataLeader->lastname; ?>").attr("disabled","disabled");
               $("#emergency_address").val("<?php echo $dataLeader->email; ?>").attr("disabled","disabled");
               $("#emergency_code").val("<?php echo $dataLeader->code_zip; ?>").attr("disabled","disabled");
               $("#emergency_mobile").val("<?php echo $dataLeader->mobile; ?>").attr("disabled","disabled");
           }else{
               $("#postcode_zip").val("").removeAttr("disabled");
               $("#country1").val("").removeAttr("disabled");
               $("#emergency_name").val("").removeAttr("disabled");
               $("#emergency_address").val("").removeAttr("disabled");
               $("#emergency_code").val("").removeAttr("disabled");
               $("#emergency_mobile").val("").removeAttr("disabled");
           }

        })
    });
</script>
<!-- Large modal addpasenger -->
<div class="bookpro-modal modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="popup-addpassenger">

    <div class="wait" style="display:none;width:100%;height:170%;position:absolute;z-index:100;background:black;opacity: 0.7;text-align:center;">
        <img src='<?php echo JUri::root(); ?>/administrator/components/com_bookpro/assets/images/waitting.gif' style="width:200px;height:200px;margin-top:30%" />
    </div>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="col-md-12">
                <div class="row tab-infomation">
                    <div class="bs-example bs-example-tabs" role="tabpanel" data-example-id="togglable-tabs">
                        <ul id="myTab" class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#ad-hoc" role="tab" id="ad-hoc-tab" data-toggle="tab" aria-controls="home" aria-expanded="true"><?php echo JText::_('ADD PASSENGER'); ?></a></li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <!--info tab general-->
                            <div role="tabpanel" class="col-md-12 tab-pane fade active in" id="add-passenger" aria-labelledby="reviews-tab">
                                <div class="col-md-12 wrapper-add-passenger">
                                    <input type="text" name="order_id" value="<?php echo $id; ?>" style="display:none;">
                                    <fieldset>
                                        <legend><?php echo JText::_('GENERAL'); ?></legend>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Title") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <select id="title" name="title">
                                                        <option value="mr">Mr</option>
                                                        <option value="mrs">Mrs</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("First Name") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="firstname" name="firstname">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Last Name") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="lastname" name="lastname">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Gender") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="radio" name="gender" class="gender noStyle" value="male">Male
                                                    <input type="radio" name="gender" class="gender noStyle" value="female">Female
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Date of Birth") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="birthday" name="birthday" class="datepicker">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Nationality") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <select id="country_id" name="country_id">
                                                        <?php foreach($listCountry as $item):?>
                                                        <option value="<?php echo $item->id; ?>"><?php echo $item->country_name; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Passport No") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="passport" name="passport">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("P. Issue Date") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="passport_issue" name="passport_issue" class="datepicker">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("P. Expiry Date") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="passport_expiry" name="passport_expiry" class="datepicker">
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-md-12"></div>
                                        <div class="col-md-6">
                                            <h5>CONTACT DETAILS</h5>
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Phone No") ?></label>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" id="code_zip" name="code_zip" placeholder="code">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" id="mobile" name="mobile" placeholder="phone number">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("E-mail Address") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="email" name="email">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Confirm E-mail") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="confirm-email" name="confirm-email">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Street Address") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="address" name="address">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Suburb/Town") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="suburb" name="suburb">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("State/Province") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="province" name="province">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="col-md-3">
                                            </div>
                                            <div class="col-md-9">
                                                <h5>USE  THE SAME LEADER CONTACT<i class="im-info2"></i></h5>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                </div>
                                                <div class="col-md-8 radio-confirm">
                                                    <input type="radio" name="radio-confirm-leadercontact" value="yes" class="radio-leader noStyle">Yes
                                                    <input type="radio" name="radio-confirm-leadercontact" value="no" class="radio-leader noStyle">No
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Postcode/Zip") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="postcode_zip" name="postcode_zip">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Res. Country") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="country1" name="country1">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <h5><?php echo JText::_("EMERGENCY CONTACT") ?></h5>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Contact Name") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="emergency_name" name="emergency_name">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("E-mail Address") ?></label>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="emergency_address" name="emergency_address">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-4">
                                                    <label><?php echo JText::_("Phone No") ?></label>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" id="emergency_code" name="emergency_code" placeholder="code">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" id="emergency_mobile" name="emergency_mobile" placeholder="phone number">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6 col-md-offset-2">(* Required Field)</div>
                                            </div>
                                        </div>


                                    </fieldset>
                                    <fieldset>
                                        <div class="col-md-12 wraper-addition-info">
                                            <div class="col-md-9 addition-info">
                                                <h5>ADDITION INFORMATION</h5>
                                                <p>Do you have any pre-existing medical conditions ?</p>
                                            </div>
                                            <div class="col-md-3">
                                                <label>( You can fill up later )</label>
                                            </div>
                                            <div class="col-md-3 radio-confirm">
                                                Yes<input type="radio" name="fill-conditions" value="yes" class="fill-conditions noStyle">
                                                No<input type="radio" name="fill-conditions" value="no" class="fill-conditions noStyle">
                                            </div>

                                            <div class="col-md-12 warning-info-pre-existing">
                                                <label>( If click yes, this box will be showed up, otherwise, no. )</label>
                                                <textarea name="aditional_request" style="display:none;width:100%;height:100%"></textarea>
                                            </div>

                                            <div class="col-md-9 addition-info">
                                                <p>Do you have any special meal requirements ?</p>
                                            </div>
                                            <div class="col-md-3 radio-confirm">
                                                Yes<input type="radio" name="fill-requirements" value="yes" class="fill-requirements noStyle">
                                                No<input type="radio" name="fill-requirements" value="no" class="fill-requirements noStyle">
                                            </div>

                                            <div class="col-md-12 warning-info-special">
                                                <label>( If click yes, this box will be showed up, otherwise, no. )</label>
                                                <textarea name="meal_requement" style="display:none;width:100%;height:100%"></textarea>
                                            </div>

                                        </div>
                                    </fieldset>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" id="add_passenger_cancel"><?php echo JText::_('Close'); ?></button>
                                    <button type="button" class="btn btn-primary" id="add_passenger_process"><?php echo JText::_('Save'); ?></button>
                                </div>
                            </div>
                            <!--end info tab general-->
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
