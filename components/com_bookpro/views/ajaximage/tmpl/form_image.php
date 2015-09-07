<style type="text/css">
.img_preview {
    margin-top: 25px;
    position: relative;
    width: 175px;
}

.img_close {
    cursor: pointer;
    position: absolute;
    right: -8px;
    top: -8px;
}
</style>
	<input type="file" name="ajaximage" id="path_to_file">
	<?php
	$image_no="no_img.jpg";
	$urlimagebasic = JURI::root() . 'components/com_bookpro/assets/images/imagesmd5/';
	if($this->obj->image){
		$imagedisplay=$this->obj->image;
		$imagevalue=$this->obj->image;
	}else{
		$imagedisplay=$image_no;
		$imagevalue='';
	}
	?>

	<div class="img_preview">
		<img id="returnuploadimage"
			src="<?php echo $urlimagebasic.$imagedisplay; ?>"
			style="width: 100%; padding-left:0px; padding-right:0px;" />
			<span class="img_close" id="img_close">
			 <a onclick="closeImage();">
			 	<img src="<?php echo $urlimagebasic; ?>img_close.png" style="padding-left:0px; padding-right:0px;">
			 </a>
			</span> 
		<input type="hidden" name="image" value="<?php echo $imagevalue; ?>" id="imagevalue" />
	</div>

	<script>
     jQuery(document).ready(function()
            {
         		if(!"<?php echo $imagevalue;?>"){
         				jQuery("#img_close").hide();  
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

                        jQuery("#path_to_file").change(function(event){
                            
                            var file = event.target.files[0];
                            var kb = 1024;
                            var size = (file.size/kb).toFixed(2);                                                                   
                                if(!uploadValidExtension(jQuery("#path_to_file").val(),arrAllowType)){
                                    alert("<?php echo JText::_('COM_BOOKPRO_IMAGE_NAME_IS_FORMAT_JPG_GIF_PNGJPEGBMPPJPEG'); ?>");
                                    jQuery("#path_to_file").val('');
                                }else{
                                    if(jQuery("#imagevalue").val()!=''){
                                    		alert("<?php echo JText::_('COM_BOOKPRO_DELETE_IMAGE'); ?>");
                                        }else{
		                                    var data = new FormData();
		                                    element = document.getElementById("path_to_file");
		                                    data.append("newFile",element.files[0]);
		                                    jQuery.ajax({
		                                        url: "index.php?option=com_bookpro&view=ajaximage&task=uploadimage&tmpl=component",
		                                        type: "POST",
		                                        data: data,
		                                        cache: false,
		                                        processData: false,
		                                        contentType: false,
		                                        success: uploadDone
		                                    });
                                        } 
                                    }
                        });
            });

     		var urlbasic = '<?php echo $urlimagebasic; ?>';
     
            function uploadDone(returns)
            {
                jQuery("#returnuploadimage").attr("src", urlbasic+returns);
                jQuery("#imagevalue").val(returns);
                jQuery("#img_close").show();  
                
            }
            
            function closeImage()
            {
            	var data = new FormData();
            	data.append("image",jQuery("#imagevalue").val());
                jQuery.ajax({
                    url: "index.php?option=com_bookpro&view=ajaximage&task=remove&tmpl=component",
                    type: "POST",
                    data: data,
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: uploadDelete
                });
                
              
            }
            function uploadDelete(returns)
            {
                jQuery("#returnuploadimage").attr("src", urlbasic+"<?php echo $image_no;?>");
                jQuery("#path_to_file").val('');
                jQuery("#imagevalue").val('');
                jQuery("#img_close").hide();   
                             
            }
</script>