<?php
/**
 * ------------------------------------------------------------------------
 * JA Animation module for Joomla 2.5 & 3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<div style="<?php echo $item_width_height;?>" id="<?php echo $element_id;?>" class="ja-anim" title="<?php echo ($showdesc == 1)?$description:'';?>">
	   <?php if($changebg == 1){ ?>
			<div style="" class="changebg" style="">&nbsp;</div>
	   <?php }
		else{
	   ?>
		<div class="anim-img"><img alt="" src="<?php echo $image_url;?>" <?php echo $image_width;?> <?php echo $image_height;?> /></div>
		<?php } ?>
</div>