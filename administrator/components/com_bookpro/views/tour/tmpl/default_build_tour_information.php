<?php
/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 3/2/2015
 * Time: 10:00 AM
 */
?>
<div class="row">
    <div class="col-md-12 build-form-edit">
        <div class="row">
            <div class="col-md-4">
                <div class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_TITLE') ?>
                            *</label>

                        <div class="controls">
                            <input class="text_area required" type="text" name="title"
                                   id="title" size="60" maxlength="255"
                                   value="<?php echo $this->obj->title; ?>"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_CODE') ?>
                            *</label>

                        <div class="controls">
                            <input class="text_area" type="text" name="code" id="code" size="20"
                                   maxlength="255" value="<?php echo $this->obj->code; ?>"/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_LENGTH') ?>
                            *</label>

                        <div class="controls">
                            <input class="text_area" type="text" name="code" id="code" size="20"
                                   maxlength="255" value=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_MINPERSON') ?>
                            *</label>

                        <div class="controls">
                            <?php echo $this->min_person_id; ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_MAXPERSON') ?>
                            *</label>

                        <div class="controls">
                            <?php echo JHtmlSelect::integerlist(0, 10, 1, 'grade', null, $this->obj->max_person) ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="min_age"> <?php echo JText::_('COM_BOOKPRO_TOUR_MIN_AGE') ?>
                            *</label>

                        <div class="controls">
                            <?php echo JHtmlSelect::integerlist(0, 100, 1, 'grade', null, $this->obj->min_age) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_COUNTRY') ?>*</label>
                        <div class="controls">
                            <?php echo $this->country_id;?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_START_CITY') ?>
                            *</label>

                        <div class="controls">
                            <input class="text_area" type="text" name="code" id="code" size="20"
                                   maxlength="255" value=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"
                               for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_FINISH_CITY') ?>*</label>

                        <div class="controls">
                            <input class="text_area" type="text" name="code" id="code" size="20"
                                   maxlength="255" value=""/>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"
                               for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_STYLE') ?></label>

                        <div class="controls">
                            <?php echo JHtmlSelect::integerlist(0, 5, 1, 'grade', null, $this->obj->grade) ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_GRADE') ?>
                            *</label>

                        <div class="controls">
                            <?php echo JHtmlSelect::integerlist(0, 5, 1, 'grade', null, $this->obj->grade) ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_MAX_AGE') ?>
                            *</label>

                        <div class="controls">
                            <?php echo JHtmlSelect::integerlist(0, 100, 1, 'grade', null, $this->obj->max_age) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-horizontal">
                    <div class="control-group">
                        <label class="control-label control-label-type" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_TYPE') ?>*</label>
                        <div class="controls type-checkbox">
                            <?php echo $this->tour_type;?>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label control-label-type" for="pickup">
                            <?php echo JText::_('COM_BOOKPRO_TOUR_SERVICE_CLASS') ?>*
                        </label>

                        <div class="controls type-checkbox">
                            <table>
                                <tr>
                                    <td>Basic</td>
                                    <td>Standard</td>
                                    <td>Superior</td>
                                    <td>Deluxe</td>
                                    <td>Luxury</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>
                                    <td><input type="checkbox"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function () {
        CheckTourType();
    });
    function CheckTourType() {
        if (jQuery("#domestic").is(":checked")) {
            jQuery("#sharedOnly").show();
            jQuery("#privateOnly").hide();
        }
        if (jQuery("#international").is(":checked")) {
            jQuery("#sharedOnly").show();
            jQuery("#privateOnly").hide();
        }
        if (jQuery("#private").is(":checked")) {
            jQuery("#sharedOnly").hide();
            jQuery("#privateOnly").show();
        }
        if (jQuery("#joint_group").is(":checked")) {
            jQuery("#sharedOnly").hide();
            jQuery("#privateOnly").show();
        }
    }
</script>
<!--<div class="form-horizontal">


    <div class="control-group">
        <label class="control-label" for="private"><?php /*echo JText::_('COM_BOOKPRO_TOUR_DAYTRIP'); */?>
        </label>

        <div class="form-inline">
            <?php /*echo JHtmlSelect::booleanlist('daytrip', '', $this->obj->daytrip) */?>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label"><?php /*echo JText::_('COM_BOOKPRO_TOUR_DAYS'); */?>
        </label>

        <div class="controls">
            <?php /*//echo JHtml::_("select.integerlist", 0, 50, 1, 'days', ' class="inputbox input-small"', $this->obj->days); */?>

            <?php /*echo $this->days; */?>
            <?php /*echo $this->days_daytrip; */?>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="pickup"> <?php /*echo JText::_('COM_BOOKPRO_TOUR_CATEGORY') */?>
        </label>

        <div class="controls">
            <?php /*echo $this->categories; */?>
        </div>
    </div>


    <div class="control-group">
        <?php /*$checked1 = $checked2 = $checked3 = $checked4 =''; */?>
        <?php
/*        if ($this->obj->stype == "Domestic") {
            $checked1 = 'checked="checked"';
        }
        */?>
        <?php
/*        if ($this->obj->stype == "International") {
            $checked2 = 'checked="checked"';
        }
        */?>
        <?php
/*        if ($this->obj->stype == "Private") {
            $checked3 = 'checked="checked"';
        }
        */?>
        <?php
/*        if ($this->obj->stype == "Joint Group") {
            $checked4 = 'checked="checked"';
        }
        */?>
        <label class="control-label"><?php /*echo JText::_('COM_BOOKPRO_TOUR_TYPE'); */?>
        </label>

        <div class="controls">
            <label class="radio inline">
                <input type="checkbox" id="domestic" value="shared" name="stype" <?php /*echo $checked1; */?> onclick="CheckTourType();"> <?php /*echo JText::_('COM_BOOKPRO_TOUR_DOMESTIC'); */?>
            </label>
            <label class="radio inline">
                <input type="checkbox" id="international" value="private" name="stype" <?php /*echo $checked2; */?> onclick="CheckTourType();"> <?php /*echo JText::_('COM_BOOKPRO_TOUR_INTERNATIONAL'); */?>
            </label>
            <label class="radio inline">
                <input type="checkbox" id="private" value="private" name="stype" <?php /*echo $checked3; */?> onclick="CheckTourType();"> <?php /*echo JText::_('COM_BOOKPRO_TOUR_PRIVATE'); */?>
            </label>
            <label class="radio inline">
                <input type="checkbox" id="joint_group" value="private" name="stype" <?php /*echo $checked4; */?> onclick="CheckTourType();"> <?php /*echo JText::_('COM_BOOKPRO_TOUR_JOINT_GROUP'); */?>
            </label>

        </div>
    </div>

    <div class="control-group" id="privateOnly">
        <label class="control-label" for="pickup"> <?php /*echo JText::_('COM_BOOKPRO_TOUR_PAX_GROUP') */?>
        </label>

        <div class="controls">
            <input class="text_area required" type="text" name="pax_group"
                   id="pax_group" size="60" maxlength="255"
                   value="<?php /*echo $this->obj->pax_group; */?>"/>
        </div>
    </div>


    <div class="control-group" id="sharedOnly">
        <label class="control-label" for="capacity"> <?php /*echo JText::_('COM_BOOKPRO_TOUR_CAPACITY') */?>
        </label>

        <div class="controls">
            <input class="text_area" type="text" name="capacity" id="total_pax"
                   size="60" maxlength="255"
                   value="<?php /*echo $this->obj->capacity; */?>"/>
        </div>
    </div>




    <div class="control-group">
        <label class="control-label" for="pickup"> <?php /*echo JText::_('COM_BOOKPRO_STATE') */?>
        </label>

        <div class="form-inline">
            <?php /*echo JHtmlSelect::booleanlist('state', '', $this->obj->state, 'Active', 'Inactive', 'state_id') */?>
        </div>
    </div>
</div>-->