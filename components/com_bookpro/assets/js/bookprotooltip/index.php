<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
  <title>index</title>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
  <script src="jquery.datetimebookingpick.js" type="text/javascript" charset="utf-8"></script>
  <link rel="stylesheet" href="jquery.datetimebookingpick.css" type="text/css" />
</head>
<body id="index" >
	<?php echo "<pre>"; print_r($_POST); ?>
	<form action="index.php" method="get" accept-charset="utf-8">
		  <div id="datetimebookingpick">
	    
	  </div>
	<p><input type="submit" value="Continue &rarr;"/></p>
	</form>
  	
</body>
<?php




$listdate=Array();
$listdate['2013118']['class']="";
$listdate['2013118']['style']="";
$listdate['2013118']['atrrib']='id="test"';
$listdate['2013118']['status']="status";
$listdate['2013118']['qty']="qty";
 
?>
<script type="text/javascript" charset="utf-8">
	
	jQuery(document).ready(function($){
		
		$('#datetimebookingpick').datetimebookingpick({
			//htmls:{"1383714000000":{"css":"","status":"status","qty":"qty"}}
			htmls:<?php echo json_encode($listdate)?>
		});
	});	
</script>