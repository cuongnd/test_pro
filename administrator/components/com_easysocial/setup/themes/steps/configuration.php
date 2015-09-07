<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Let's try to detect if there are any files in the /packages/ folder.
jimport( 'joomla.filesystem.folder' );
$packages 	= JFolder::files( ES_PACKAGES );
?>
<div class="row-fluid">
	<div class="span12">
		<h2 class="section-heading">
			<i class="icon-jar jar-folder_blue"></i>
			<span><?php echo JText::_( 'Extracting Archives' );?></span>
		</h2>

		<hr class="section-separator" />

		<p>
			EasySocial allows you to install the component via the network or via a normal package upload. Please select your desired installation method:
		</p>

		<ul class="unstyled">
			<li>
				<input type="radio" name="installmethod" value="network" id="network" />

				<label for="network">
				<h5>Install over the network <span class="label label-important">Recommended</span></h5>
				<p>
					By installing over the network, files will be transmitted over the internet. Downloaded packages will then be uploaded accordingly.
				</p>
				</label>
			</li>
			<li>
				<input type="radio" name="installmethod" value="upload" id="upload"/>
				<label for="upload">
				<h5>Install by manual upload</h5>
				<?php if( empty( $packages ) ){ ?>
				<div class="alert alert-error">
					There are no packages found. You need to download the correct installation file.
				</div>
				<?php } ?>
				<p>
					In order to perform installation via manual upload, you will need to download the second package which is much larger.
				</p>
				</label>

				<?php if( $packages ){ ?>
				<span>Select a package:</span>
				<select>
					<?php foreach( $packages as $package ){ ?>
					<option value="<?php echo $package;?>"><?php echo $package; ?></option>
					<?php } ?>
				</select>
				<?php } ?>
			</li>
		</ul>

	</div>
</div>