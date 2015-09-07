/**
 * Created by cuongnd on 7/8/2015.
 */
jQuery(document).ready(function($){
    blockPropertiesUtil.savePropertyBlock=function(property,block_id,close)
    {
        console.log('hello savePropertyBlock2342423');
        if(typeof ajaxSavePropertyBlock !== 'undefined'){
            ajaxSavePropertyBlock.abort();
        }
        post=property.find('select,textarea, input:not([readonly])').serialize();
        ajaxSavePropertyBlock=$.ajax({
            type: "POST",
            dataType: "json",
            url: this_host+'/index.php?option=com_utility&task=utility.ajaxSavePropertyBlock&block_id='+block_id,
            data: post,
            beforeSend: function () {
                $('.div-loading').css({
                    display: "block"


                });
                // $('.loading').popup();
            },
            success: function (response) {
                $('.div-loading').css({
                    display: "none"


                });
                panelItemField=property.closest('.itemField');
                panelItemField.find(':input[name*="jform"]').each(function(){
                    self=$(this);
                    name=self.attr('name');
                    $('.block-properties').find(':input[name="'+name+'"]').val(self.val());
                });
                $('.block-properties').find('textarea[name="jform[advanced_params]"]').val(response.advanced_params.toString());
                if(close)
                    panelItemField.remove();
            }
        });
    }
});
