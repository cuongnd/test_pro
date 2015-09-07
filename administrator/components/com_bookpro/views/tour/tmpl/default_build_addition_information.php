<?php
/**
 * Created by PhpStorm.
 * User: Administrator PC
 * Date: 3/16/2015
 * Time: 9:10 AM
 */
?>
<div class="row">
    <div class="col-md-12 build-form-edit">
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <label class="col-md-12 text-info">Highlights</label>
                </div>
                <div class="row">
                    <div class="col-md-12 add-info"><textarea rows="7" name="highlights"><?php echo $this->obj->highlights; ?></textarea></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <label class="col-md-12 text-info">Inclusions</label>
                </div>
                <div class="row">
                    <div class="col-md-12 add-info"><textarea rows="7" name="inclusions"><?php echo $this->obj->inclusions; ?></textarea></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <label class="col-md-12 text-info">Exclusions</label>
                </div>
                <div class="row">
                    <div class="col-md-12 add-info"><textarea rows="7" name="exclusions"><?php echo $this->obj->exclusions; ?></textarea></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 build-form-edit">
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <label class="col-md-12 text-info">Meta name</label>
                </div>
                <div class="row">
                    <div class="col-md-12 add-info"><textarea rows="7" name="meta_name"><?php echo $this->obj->meta_name; ?></textarea></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <label class="col-md-12 text-info">Short description</label>
                </div>
                <div class="row">
                    <div class="col-md-12 add-info"><textarea rows="7" name="short_description"><?php echo $this->obj->short_description; ?></textarea></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <label class="col-md-12 text-info">Long description</label>
                </div>
                <div class="row">
                    <div class="col-md-12 add-info"><textarea rows="7" name="long_description"><?php echo $this->obj->long_description; ?></textarea></div>
                </div>
            </div>
        </div>
    </div>
</div>