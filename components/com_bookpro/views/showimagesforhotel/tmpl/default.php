<?php           
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();       
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/css/showimagesforhotel.css');
$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/jquery-1.4.1.js');

$images = explode(";", $this->hotel->images);
                  
?>
    <div id='wrapper' style="margin-bottom:0px;">
        <div id='body'>
            <div id="bigPic">
              <?php
                if ($images) {       
                    for ($i = 0; $i < count($images); $i++) {
                        if ($images[$i]) {
                
                                     $ipath = '';
                                     $ipath = BookProHelper::getIPath($images[$i]);
                                     $thumb = AImage::thumb($ipath, 940, 300);
                                     ?>
                                <img src="<?php echo $thumb; ?>" alt="">
                                
                             <?php
                        }
                    }
                }
                ?>
             
            </div>
            
            
            <ul id="thumbs">
              <?php
                if ($images) {       
                    for ($i = 0; $i < count($images); $i++) {
                        if ($images[$i]) {
                
                                     $ipath = '';
                                     $ipath = BookProHelper::getIPath($images[$i]);
                                     $thumb = AImage::thumb($ipath, 160, 100);
                                     ?>
                                <li <?php if($i == 0){ ?>class='active' <?php }?> rel='<?php echo $i+1; ?>'><img src="<?php echo $thumb; ?>" alt="" /></li>   
                             <?php
                        }
                    }
                }
                ?>              
            </ul>             
        </div>       
        <div class='clearfix'></div>      
    </div>

    <script type="text/javascript">
    var currentImage;
    var currentIndex = -1;
    var interval;
    function showImage(index){
        if(index < $('#bigPic img').length){
            var indexImage = $('#bigPic img')[index]
            if(currentImage){   
                if(currentImage != indexImage ){
                    $(currentImage).css('z-index',2);
                    clearTimeout(myTimer);
                    $(currentImage).fadeOut(250, function() {
                        myTimer = setTimeout("showNext()", 3000);
                        $(this).css({'display':'none','z-index':1})
                    });
                }
            }
            $(indexImage).css({'display':'block', 'opacity':1});
            currentImage = indexImage;
            currentIndex = index;
            $('#thumbs li').removeClass('active');
            $($('#thumbs li')[index]).addClass('active');
        }
    }
    
    function showNext(){
        var len = $('#bigPic img').length;
        var next = currentIndex < (len-1) ? currentIndex + 1 : 0;
        showImage(next);
    }
    
    var myTimer;
    
    $(document).ready(function() {
        myTimer = setTimeout("showNext()", 3000);
        showNext(); //loads first image
        $('#thumbs li').bind('click',function(e){
            var count = $(this).attr('rel');
            showImage(parseInt(count)-1);
        });
    });
    
    
    </script>    