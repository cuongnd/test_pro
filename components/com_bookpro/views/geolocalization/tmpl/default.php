<?php 

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 26 2012-07-08 16:07:54Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
$langs  = explode('-', $document->getLanguage());
$lang   = $langs[0];
$region = $langs[1];
$document->addScript('https://maps.googleapis.com/maps/api/js?language='.$lang.'&sensor=false');

$this->longitude=JRequest::getVar('long',null);
$this->latitude=JRequest::getVar('lat',null);

if(!$this->longitude)
{
	$this->longitude=-34.397;
	$this->latitude=150.644;	
}

?>

<script type="text/javascript">
     
  var geocoder;
  var map;
  var oldMarker;
  function initialize() {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(<?php echo $this->latitude ?>,<?php echo $this->longitude?>);
    var mapOptions = {
      zoom: 11,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
	
	map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	var marker = new google.maps.Marker({
          position: map.getCenter(),
          map: map,
          title: 'Click to zoom'
        });
	oldMarker=marker;
	google.maps.event.addListener(marker, 'click', function() {
          map.setZoom(12);
          map.setCenter(marker.getPosition());
        });
    google.maps.event.addListener(map, 'click', function(event) {
        var myLatLng = event.latLng;
        var lat = myLatLng.lat();
        var lng = myLatLng.lng();
        document.getElementById('long').value=lng;
        document.getElementById('lat').value=lat;
		placeMarker(event.latLng);
    });
  }
  
   function placeMarker(location) {
            marker = new google.maps.Marker({
                position: location,
                map: map,
                animation: google.maps.Animation.DROP,
            });
            if (oldMarker != undefined){
                oldMarker.setMap(null);
            }
            oldMarker = marker;
            map.setCenter(location);
        }

  function codeAddress() {
    var address = document.getElementById("address").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
        });
        document.getElementById('long').value=results[0].geometry.location.lng();
        document.getElementById('lat').value=results[0].geometry.location.lat();
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }
  google.maps.event.addDomListener(window, 'load', initialize);

	function closeBoxContent(){
		window.parent.setGeo(document.getElementById('long').value,document.getElementById('lat').value);
		window.parent.SqueezeBox.close();
	}  
  </script>
<h2><?php echo JText::_('COM_BOOKPRO_SEARCH AND PICK LOCATION')?></h2>
<div class="form-inline">
	<input id="address" type="text" placeholder="Enter location here to search"> 
	<input type="button" value="Seach" onclick="codeAddress()" class="btn">
</div>

<div id="map_canvas" style="width: 720px; height: 400px; margin-top: 10px;"></div>
<div class="form-inline">
	Long:<input id="long" class="input-medium" type="text" value="<?php echo $this->longitude; ?>">
	at:<input id="lat" type="text" class="input-medium" value="<?php echo $this->latitude; ?>"><input
		type="button" value="Save" onclick="closeBoxContent()" class="btn btn-primary">
	</div>
