<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

?>
<div class="row form-group">
	<div class="col-md-12">
		<div class="form-inline">
			<?php foreach ($displayData->filters as $filter) : ?>
				<div class="form-group">
					<label for="<?php echo $filter['name']; ?>"><?php echo $filter['label']; ?></label>
					<select name="<?php echo $filter['name']; ?>" id="<?php echo $filter['name']; ?>" class="form-control small" style="width: auto" onchange="this.form.submit()">
						<?php if (!$filter['noDefault']) : ?>
							<option value=""><?php echo $filter['label']; ?></option>
						<?php endif; ?>
						<?php echo $filter['options']; ?>
					</select>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
