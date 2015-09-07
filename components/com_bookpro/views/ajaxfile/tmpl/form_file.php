<style type="text/css">
.file_preview {
    margin-top: 15px;
    position: relative;
}

.img_close {
    cursor: pointer;
    position: absolute;
    right: -8px;
    top: -8px;
}
</style>
	<input type="file" name="ajaxfile" id="path_to_file_file">
	<?php
	$file_no="no_img.jpg";
	$urlfilebasic = JURI::root() . 'components/com_bookpro/assets/files/filesmd5/';
	if($this->obj->file){
		$filedisplay=$this->obj->file;
		$filevalue=$this->obj->file;
	}else{
		$filedisplay=$file_no;
		$filevalue='';
	}
	?>

	<div class="file_preview">
		<a target="_blank" id="returnuploadfile" href="<?php echo $urlfilebasic.$this->obj->file; ?>">
			<?php echo $this->obj->file;?>
		</a>
		
			<span class="file_close" id="file_close">
				 <a onclick="closeImageFile();" href="javascript:void(0);">
				 	<img src="<?php echo JURI::root() ; ?>components/com_bookpro/assets/images/imagesmd5/img_close.png" style="padding-left:0px; padding-right:0px;">
				 </a>
			</span> 
		<input type="hidden" name="file" value="<?php echo $filevalue; ?>" id="filevalue" />
	</div>

	<script>
     jQuery(document).ready(function()
            {
         		if(!"<?php echo $filevalue;?>"){
         				jQuery("#file_close").hide();  
             		}

                var arrAllowType = new Array();
                arrAllowType = ['.jpg', '.gif', '.png','.jpeg','.bmp','.pjpeg'];
                        function uploadValidExtension(fileName, arrAllowType)
                        {
                            if(fileName == "")
                            {
                                return false;
                            }
                            fileName = fileName.toLowerCase();
                            
                            var extension = fileName.substr(fileName.toLowerCase().lastIndexOf('.'), fileName.length);
                            var check = false;
                            for(var i=0; i <arrAllowType.length; i++)
                            {
                                    if(arrAllowType[i] == extension)
                                    {
                                        check = true;
                                        break;
                                    }
                            }
                            return check;
                        }

                        jQuery("#path_to_file_file").change(function(event){
                        	 if(jQuery("#filevalue").val()!=''){
                         		alert("<?php echo JText::_('Delete file'); ?>");
                             }else{
		                                    var data = new FormData();
		                                    element = document.getElementById("path_to_file_file");
		                                    data.append("newFile",element.files[0]);
		                                    jQuery.ajax({
		                                        url: "insex.php?option=com_bookpro&view=ajaxfile&task=uploadfile&tmpl=component",
		                                        type: "POST",
		                                        data: data,
		                                        cache: false,
		                                        processData: false,
		                                        contentType: false,
		                                        success: uploadDoneFile
		                                    });
                             }        
                        });
                        
            });

     		var urlfilebasic = '<?php echo $urlfilebasic; ?>';
     
            function uploadDoneFile(returns)
            {
                jQuery("#returnuploadfile").attr("href", urlfilebasic+returns);
                jQuery("#returnuploadfile").html(returns);
                jQuery("#filevalue").val(returns);
                jQuery("#file_close").show();  
                
            }
            
            function closeImageFile()
            {
            	var data = new FormData();
            	data.append("file",jQuery("#filevalue").val());
                jQuery.ajax({
                    url: "insex.php?option=com_bookpro&view=ajaxfile&task=remove&tmpl=component",
                    type: "POST",
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: uploadDeleteFile
                });
            }
            function uploadDeleteFile(returns)
            {
                jQuery("#returnuploadfile").html('');
                jQuery("#path_to_file_file").val('');
                jQuery("#filevalue").val('');
                jQuery("#file_close").hide();   
                             
            }
</script>