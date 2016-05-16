<?php
$list_template_website=$this->list_template_website;
$col=2;
$list_list_template_website=array_chunk($list_template_website,$col);
?>
<?php foreach($list_list_template_website as $list_template_website){
 ?>
    <div class="row form-group ">
        <?php foreach($list_template_website as $template){ ?>
            <div class="col-md-<?php echo round(12/$col) ?>">
                <div class="product img-thumbnail">
                    <img class="img-responsive" src="<?php echo $template->image_url ?>">
                </div>
            </div>
        <?php } ?>
    </div>
<?php
}
?>
