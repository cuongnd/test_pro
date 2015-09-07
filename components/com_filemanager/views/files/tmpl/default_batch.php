<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_product
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$app=JFactory::getApplication();
$published = $this->state->get('filter.published');
?>
<div class="modal hide fade" id="collapseModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&#215;</button>
		<h3><?php echo JText::_('com_product_BATCH_OPTIONS'); ?></h3>
	</div>
	<div class="modal-body modal-batch">
		<p><?php echo JText::_('com_product_BATCH_TIP'); ?></p>
		<div class="row-fluid">
			<div class="control-group span6">
				<div class="controls">
					<?php echo JHtml::_('batch.tag'); ?>
				</div>
			</div>
			<div class="control-group span6">
				<div class="controls">
					<?php echo JHtml::_('batch.language'); ?>
				</div>
			</div>
		</div>
        <div class="row-fluid">
            <div class="control-group">
                <label id="batch-choose-action-lbl" for="batch-category-id" class="control-label">
                    <?php echo JText::_('Select website'); ?>
                </label>

                <div id="batch-choose-action" class="combo controls">
                    <?php require_once JPATH_ROOT.'/administrator/components/com_website/helpers/website.php'; ?>
                    <?php echo websiteHelperFrontEnd::getGenericlistWebsite('website_id','onchange="changeWebsite()"') ?>
                </div>
            </div>
        </div>
		<div class="row-fluid">
			<div class="control-group span6">
				<div class="controls">
					<?php echo JHtml::_('batch.access'); ?>
				</div>
			</div>
			<?php if ($published >= 0) : ?>
				<div class="control-group span6">
					<div class="controls">
						<?php echo JHtml::_('batch.item', 'com_product'); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn" type="button" onclick="document.id('batch-category-id').value='';document.id('batch-access').value='';document.id('batch-language-id').value='';document.id('batch-tag-id)').value=''" data-dismiss="modal">
			<?php echo JText::_('JCANCEL'); ?>
		</button>
		<button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('product.batch');">
			<?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
		</button>
	</div>
</div>
<script type="text/javascript">
    function changeWebsite()
    {
        $=jQuery;
        website_id=$('#website_id').val();
        extension='<?php echo $app->input->getString('extension','com_product') ?>';
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function() {
                dataPost = {
                    option: 'com_product',
                    task: 'products.aJaxGetOptionsCategory',
                    website_id:website_id,
                    extension:extension
                }
                return dataPost;
            })(),
            beforeSend: function() {
                $('.div-loading').css({
                    display: "block",
                    position: "fixed",
                    "z-index": 1000,
                    top: 0,
                    left: 0,
                    height: "100%",
                    width: "100%"

                });
                // $('.loading').popup();
            },
            success: function(result) {
                $('#batch-category-id').html(result);
                $('#batch-category-id').trigger('liszt:updated');

            }
        });

    }
</script>