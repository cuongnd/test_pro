<?php
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Eugen Stranz
 * @author RolandD,
 * @todo handle child products
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default.php 6530 2012-10-12 09:40:36Z alatak $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// addon for joomla modal Box
JHTML::_('behavior.modal');
// JHTML::_('behavior.tooltip');
if(VmConfig::get('usefancy',0)){
	vmJsApi::js( 'fancybox/jquery.fancybox-1.3.4.pack');
	vmJsApi::css('jquery.fancybox-1.3.4');
	$box = "$.fancybox({
				href: '" . $this->askquestion_url . "',
				type: 'iframe',
				height: '550'
			});";
} else {
	vmJsApi::js( 'facebox' );
	vmJsApi::css( 'facebox' );
	$box = "$.facebox({
				iframe: '" . $this->askquestion_url . "',
				rev: 'iframe|550|550'
			});";
}
$document = JFactory::getDocument();
$document->addScriptDeclaration("
//<![CDATA[
	jQuery(document).ready(function($) {
		$('a.ask-a-question').click( function(){
			".$box."
			return false ;
		});
	/*	$('.additional-images a').mouseover(function() {
			var himg = this.href ;
			var extension=himg.substring(himg.lastIndexOf('.')+1);
			if (extension =='png' || extension =='jpg' || extension =='gif') {
				$('.main-image img').attr('src',himg );
			}
			console.log(extension)
		});*/
	});
//]]>
");
/* Let's see if we found the product */
if (empty($this->product)) {
    echo JText::_('COM_VIRTUEMART_PRODUCT_NOT_FOUND');
    echo '<br /><br />  ' . $this->continue_link_html;
    return;
}

?>
<div class="productdetails-view productdetails">

    <?php
    // Product Navigation
    if (VmConfig::get('product_navigation', 1)) {
	?>
        <div class="product-neighbours">
	    <?php
	    if (!empty($this->product->neighbours ['previous'][0])) {
		$prev_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&layout=demo&tmpl=component&virtuemart_product_id=' . $this->product->neighbours ['previous'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id.'&slug='.$this->product->neighbours ['previous'][0] ['slug'], FALSE);
		echo JHTML::_('link', $prev_link, $this->product->neighbours ['previous'][0]
			['product_name'], array('class' => 'previous-page'));
	    }
	    if (!empty($this->product->neighbours ['next'][0])) {
		$next_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&layout=demo&tmpl=component&virtuemart_product_id=' . $this->product->neighbours ['next'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id.'&slug='.$this->product->neighbours ['next'][0] ['slug'], FALSE);
		echo JHTML::_('link', $next_link, $this->product->neighbours ['next'][0] ['product_name'], array('class' => 'next-page'));
	    }
	    ?>
    	<div class="clear"></div>
	</div>
    <?php } // Product Navigation END
    ?>

	<?php // Back To Category Button
	if ($this->product->virtuemart_category_id) {
		$catURL =  JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id='.$this->product->virtuemart_category_id, FALSE);
		$categoryName = $this->product->category_name ;
	} else {
		$catURL =  JRoute::_('index.php?option=com_virtuemart');
		$categoryName = jText::_('COM_VIRTUEMART_SHOP_HOME') ;
	}
	?>
	<div class="back-to-category">
		<a href="<?php echo $catURL ?>" class="product-details" title="<?php echo $categoryName ?>"><?php echo JText::sprintf('COM_VIRTUEMART_CATEGORY_BACK_TO',$categoryName) ?></a>
	</div>

    <?php // Product Title   ?>
    <h1><?php echo $this->product->product_name ?></h1>
    <?php // Product Title END   ?>

    <?php // afterDisplayTitle Event
    echo $this->product->event->afterDisplayTitle ?>

    <?php
    // Product Edit Link
    echo $this->edit_link;
    // Product Edit Link END
    ?>

    <?php
    // PDF - Print - Email Icon
    if (VmConfig::get('show_emailfriend') || VmConfig::get('show_printicon') || VmConfig::get('pdf_button_enable')) {
	?>
        <div class="icons">
	    <?php
	    //$link = (JVM_VERSION===1) ? 'index2.php' : 'index.php';
	    $link = 'index.php?tmpl=component&option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id.'&slug='.$this->product->slug;
	    $MailLink = 'index.php?option=com_virtuemart&view=productdetails&task=recommend&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&tmpl=component'.'&slug='.$this->product->slug;

	    if (VmConfig::get('pdf_icon', 1) == '1') {
		echo $this->linkIcon($link . '&format=pdf', 'COM_VIRTUEMART_PDF', 'pdf_button', 'pdf_button_enable', false);
	    }
	    echo $this->linkIcon($link . '&print=1', 'COM_VIRTUEMART_PRINT', 'printButton', 'show_printicon');
	    echo $this->linkIcon($MailLink, 'COM_VIRTUEMART_EMAIL', 'emailButton', 'show_emailfriend');
	    ?>
    	<div class="clear"></div>
	</div>
    <?php } // PDF - Print - Email Icon END
    ?>

    <?php
    // Product Short Description
    if (!empty($this->product->product_s_desc)) {
	?>
        <div class="product-short-description">
	    <?php
	    /** @todo Test if content plugins modify the product description */
	    echo nl2br($this->product->product_s_desc);
	    ?>
        </div>
	<?php
    } // Product Short Description END


    if (!empty($this->product->customfieldsSorted['ontop'])) {
	$this->position = 'ontop';
	echo $this->loadTemplate('customfields');
    } // Product Custom ontop end
    ?>

  <div class="row-fluid">
		<div class="span6"><?php echo $this->loadTemplate('showprices');?></div>
		<div class="span6"><?php echo $this->loadTemplate('addtocart');?></div>
	</div>
    <?php
    $this->product->linkdemo=$this->product->linkdemo?$this->product->linkdemo:$this->product->link_demo;
    ?>
	<?php if($this->product->linkdemo){?>
	<div class="row-fluid linkdemo">
		<div id="iframelive">
			<div data-template_id="49161" id="headerlivedemo" onmousewheel="SmoothSize; return false" class="relative js-demo-upper-menu">
				<div id="advanced" style="margin-top: 0px; position: relative;">
					<span class="trigger"> <em></em>
					</span>
					<div class="bg">
						<div class="top_container container">
							<a href="/" class="brand brand_livedemo"></a>

							<div class="responsive-block" style="display: block;">
								<ul id="responsivator">
									<li id="desktop" class="response active"><a href="javascript:;"></a></li>
									<li id="tablet-landscape" class="response"><a href="javascript:;"></a></li>
									<li id="tablet-portrait" class="response"><a href="javascript:;"></a></li>
									<li id="iphone-landscape" class="response"><a href="javascript:;"></a></li>
									<li id="iphone-portrait" class="response"><a href="javascript:;"></a></li>

								</ul>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div id="frameWrapper">
				<div class="mark"></div>
				<?php
                    if(!filter_var($this->product->linkdemo, FILTER_VALIDATE_URL)){
                        $this->product->linkdemo='http://templatemonster.com/'.$this->product->linkdemo;
                    }

				?>
				<iframe id="frame" frameborder="0" width="100%" height="1500px" src="<?php echo $this->product->linkdemo?$this->product->linkdemo:$this->product->link_demo ?>"></iframe>
			</div>
		</div>
	</div>
	<?php }else{?>
	<?php echo $this->loadTemplate('images'); ?>
	<?php }?>
	<script type="text/javascript">
             jQuery(document).ready(function($) {

            	 jQuery('iframe').one('load', function() {

            		 //console.log(jQuery('iframe').contents().find('#headerlivedemo').hasClass('relative'));
            	 });
            	 $(document).on("click","#responsivator li.response",function() {
                	 	$('#responsivator li.response').each(function(){
                	 		$('#iframelive').removeClass($(this).attr('id'));
                    	 });
            		    $('#iframelive').addClass($(this).attr('id'));

            		    $('#responsivator li.response').each(function(){
                	 		$('div.mark').removeClass($(this).attr('id'));
                    	 });
            		    $('div.mark').addClass($(this).attr('id'));
            		});


//             	 jQuery('iframe').contents().find('#headerlivedemo').hide();
//             	 jQuery('iframe').contents().find('#header').css('opacity','.2');
});
                </script>
	<style type="text/css">
div.mark {
	width: 100%;
	height: 52px;
	background: #fff;
	z-index: 9999;
	position: absolute;
}

div.mark.desktop {

}

div.mark.tablet-landscape {
	left: 80px;
    top: 51px;
    width: 1039px;
}

div.mark.tablet-portrait {
	 left: 58px;
    top: 65px;
    width: 785px;
}

div.mark.iphone-landscape {
	left: 133px;
	position: absolute;
	top: 28px;
	width: 494px;
}

div.mark.iphone-portrait {
	left: 30px;
    top: 133px;
    width: 337px;
    height: 87px;
}

.linkdemo {

}

#frame {
	top: 0px;
	border: none;
}
</style>