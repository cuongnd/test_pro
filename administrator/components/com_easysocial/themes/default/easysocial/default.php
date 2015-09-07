<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<form id="adminForm" name="adminForm" method="post" action="index.php">
	<div class="row-fluid">

		<div class="span6">
			<?php echo $this->loadTemplate( 'admin/easysocial/widget.registration', array( 'signupData' => $signupData , 'axes' => $axes ) ); ?>
			<div class="row-fluid">
				<div class="span6">
					<?php echo $this->loadTemplate( 'admin/easysocial/widget.news' ); ?>
				</div>
				<div class="span6">
					<?php echo $this->includeTemplate( 'admin/easysocial/widget.stats' , array( 'totalUsers' => $totalUsers , 'totalOnline' => $totalOnline )); ?>
				</div>
			</div>

		</div>

		<div class="span6">
			<?php echo $this->loadTemplate( 'admin/easysocial/widget.emails' , array( 'mailStats' => $mailStats , 'axes' => $axes ) ); ?>

			<?php echo $this->loadTemplate( 'admin/easysocial/widget.pending.users' , array( 'pendingUsers' => $pendingUsers ) ); ?>
		</div>

	</div>

	<input type="hidden" name="boxchecked" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_easysocial" />
	<input type="hidden" name="view" value="" />
	<input type="hidden" name="controller" value="easysocial" />
	<?php echo $this->html( 'form.token' ); ?>
</form>
