<?php if( !isset( $init ) || $init ){ ?>
EasySocial.module("init", function($) {

	this.resolve();

	<?php echo $contents; ?>
});

<?php } else { ?>
	<?php echo $contents; ?>
<?php } ?>