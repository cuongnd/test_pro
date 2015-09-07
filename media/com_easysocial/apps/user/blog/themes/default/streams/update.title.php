<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<a href="<?php echo $actor->getPermalink();?>"><?php echo $actor->getName();?></a> <?php echo JText::_( 'APP_BLOG_STREAM_UPDATED_BLOG_POST' );?> 
<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=entry&id=' . $blog->id );?>"><?php echo $blog->title;?></a>.