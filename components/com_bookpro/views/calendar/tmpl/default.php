<?php // no direct access

defined('_JEXEC') or die('Restricted access');

?>
	<?php 
		$layout = new JLayoutFile('monthlycal', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
		$html = $layout->render($passengers);
		echo $html;
	?>


 