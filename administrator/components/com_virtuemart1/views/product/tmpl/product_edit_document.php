c<?php
/**
*
* The main product images
*
* @package	VirtueMart
* @subpackage Product
* @author RolandD
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: product_edit_images.php 5887 2012-04-14 13:16:20Z electrocity $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
$prodcut_model = VmModel::getModel('product');
$document=JFactory::getDocument();

if(count($this->list_document)) $this->list_document;  
?>
<?php if(!$this->product->virtuemart_product_id){?>
<span><?php echo JText::_('please save product'); ?></span>
<?php return;?>
<?php }?>
<div id="upload" ><input type="file" name="uploadfile"> </div>
<input type="button" name="submit" id="submit" value="Submit" />
<span id="status" ></span><ul id="files" ></ul>

<table  class="table_document" width="100%" >
    <thead style="text-align: left">
        <tr>
            <th><?php echo Jtext::_('Document name') ?></th>
            <th><?php echo Jtext::_('Path') ?></th>
            <th><?php echo Jtext::_('Input file') ?></th>
            <th><?php echo Jtext::_('Delete') ?></th>
            <th><?php echo Jtext::_('Add row') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1 ?>
        <?php if(count($this->list_document))foreach ($this->list_document as $document): ?>
            
        <tr class="row">
            <td><input type="hidden" id="document_ids" class="document_ids" name="document_ids[]" value="<?php echo $document->id ?>" />
                <input type="text" class="document_names" name="document_names[]" value="<?php echo $document->name ?>" /></td>
            <td class="document_path"><?php echo $document->path ?></td>
            <td><input type="file" class="document_files" name="document_files[]" /></td>
            <td><input type="button" class="deleterow" name="deleterow" value="<?php echo JText::_('Delete') ?>" />
                <input type="hidden" class="delete_documents" name="delete_documents[]" value=""  />
            </td>
            <td><input type="button" class="addrow" name="addrow" value="<?php echo JText::_('Add row') ?>" /></td>
            <td><input type="button" class="addrow" name="uploadrow" value="<?php echo JText::_('Upload') ?>" /></td>
        </tr>
        <?php $i++ ?>
        <?php endforeach ?>
    </tbody>
   
</table>


<script type="text/jscript">
    jQuery(document).ready(function($) {
        jQuery(".addrow").live("click", function() {
            var row=jQuery(this).closest(".row");
            row=row.clone().insertAfter(".row:last");
            row.find(".document_ids").val(null);
            row.find(".document_path").html(null);
            row.find(".document_names").val(null);
            row.find(".document_files").val(null);
            row.find(".delete_documents").val(null);
        });
        
        var btnUpload = $('#upload');
        var status = $('#status');
        new AjaxUpload(btnUpload, {
            action: 'index.php?option=com_virtuemart&controller=product',
            name: 'uploadfile',  
            autoSubmit: true,
            onSubmit: function(file, ext){
                // if (! (ext && /^(pdf)$/.test(ext))){ 
                if (!(ext && /^(jpg|png|jpeg|gif|pdf)$/.test(ext))) { 
                    // extension is not allowed 
                    status.text('Only JPG, PNG or GIF files are allowed');
                    return false;
                }

                upload.setData({'example_key': 'value'});
            },
            onComplete: function(file, response) {
                //On completion clear the status
                status.text('');

                //Add uploaded file to list
                if (response === "success") {
                    $('<li></li>').appendTo('#files').html('<img src="./uploads/'+file+'" alt="" /><br />'+file).addClass('success');
                } 
                else {
                    $('<li></li>').appendTo('#files').text(file).addClass('error');
                }
            }
        });
        
        jQuery(".deleterow").live("click", function() {
            var row=jQuery(this).closest(".row");
            row.find(".delete_documents").val('delete');
            if(jQuery(".table_document tbody tr.row:visible").length==1)
            {
                row.find(".document_ids").val(null);
                row.find(".document_path").html(null);
                row.find(".document_names").val(null);
                row.find(".document_files").val(null);
                return false;
            }
            row.hide();
        });
        
    });
</script>