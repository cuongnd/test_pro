<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	13 March 2012
 * @file name	:	views/admproject/tmpl/editproject.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows JoomBri Lance Credit (jblance)
 */
 	defined('_JEXEC') or die('Restricted access');
?>

<table width="100%" style="table-layout:fixed;">
	<tr><td height="15"></td></tr>
	<tr>
		<td style="vertical-align:middle;" align="center">
			<img alt="" src="data:image/png;base64,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" />
		</td>
	</tr>
</table>
