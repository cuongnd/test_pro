jQuery("select#country_id").change(function($) {
		$.ajax({
			type:"GET",
			url: "index.php?option=com_bookpro&controller=customer&task=getcity&format=raw",
			data:"country_id="+$(this).val(),
			beforeSend : function() {
				$("select#dest_id")
						.html('<option>'+loading_txt+'</option>');
			},
			success:function(result){
					$("select#dest_id").html(result);
				}
			});
	 });