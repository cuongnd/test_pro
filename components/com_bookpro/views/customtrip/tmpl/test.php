<?php

JHtmlBehavior::framework ();
JHtml::_ ( 'jquery.ui' );
JHtml::_ ( 'jquery.framework' );
JHtml::_ ( 'behavior.calendar' );
JHtml::_ ( 'behavior.formvalidation' );
JHtml::_ ( 'bootstrap.framework' );

?>
<div id="my-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">The email address is already in use</h4>
            </div>
            <div class="modal-body">
               Do you want login to submit customtrip
            </div>
        </div>
        <div class="modal-footer">
    <a href="#"  class="btn" data-dismiss="modal" >Close</a>
    <a href="<?php echo JUri::root().'index.php?option=com_bookpro&view=login&return='?>" class="btn btn-primary">Login</a>
  </div>
    </div> 
</div>
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($) {
	  $('#my-modal').modal('show');
});
</script>