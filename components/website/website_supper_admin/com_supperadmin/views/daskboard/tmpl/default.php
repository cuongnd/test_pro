<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">

</script>
<?php echo $this->render_toolbar() ?>
<form action="<?php echo JRoute::_('index.php?option=com_supperadmin&view=domain&layout=edit&id=' . (int) $this->item->id); ?>" method="post"  name="adminForm" id="adminForm" class="form-validate">


	<input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
