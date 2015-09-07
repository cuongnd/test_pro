<!-- Modal -->
<div class="modal fade" id="popup-addflight" tabindex="-1" role="dialog" data-backdrop="true" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg add-add-flight">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add flight</h4>
        </div>
        <div class="modal-content">
            <div class="modal-body ">
                <div class="col-md-12">
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>

        </div>
    </div>
</div>
