<?php
$total_template_per_page=24;

$list_template_website=$this->list_template_website;
$list_template_website_per_page=array_chunk($list_template_website,$total_template_per_page);
$col=3;

$list_list_template_website=array_chunk($list_template_website_per_page[$this->page_selected-1],$col);
?>
<?php foreach($list_list_template_website as $list_template_website){
 ?>
    <div class="row form-group ">
        <?php foreach($list_template_website as $template){ ?>
            <div class="col-md-<?php echo round(12/$col) ?>">
                <div class="product">
                    <img class="img-responsive img-thumbnail" src="<?php echo $template->image_url ?>">
                    <div class="mask img-responsive img-thumbnail">
                        <div class="link-detail">
                            <a href="<?php echo JRoute::_('index.php?option=com_websitetemplatepro&view=template&layout=demo&id='.$template->id) ?>" target="_blank" class="btn btn-primary"><?php echo JText::_('Demo') ?></a>
                            <a href="<?php echo JRoute::_('index.php?option=com_websitetemplatepro&task=template.front_end_user_edit_website&id='.$template->id) ?>" target="_blank" class="btn btn-primary"><?php echo JText::_('Edit') ?></a>

                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php
}
?>
<div class="row form-group">
    <div class="col-md-12">
        <ul  data-page_selected="<?php echo $this->page_selected ?>" data-category_id="<?php echo $this->category_id ?>" data-total_page="<?php echo count($list_template_website_per_page)  ?>" id="pagination" class="pagination-sm"></ul>
    </div>
</div>
