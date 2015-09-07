<?php
/**
 * @package		Google Analytics Dashboard - Module for Joomla!
 * @author		DeConf - http://deconf.com
 * @copyright	Copyright (c) 2010 - 2012 DeConf.com
 * @license		GNU/GPL license: http://www.gnu.org/licenses/gpl-2.0.html
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' ); 
 
class modGoogleAnalyticsDashboardHelper
{
	
	public static function store_token ($token){
		
		$db = JFactory::getDBO();
		try { 
			$query = "UPDATE #__ga_dash SET token='$token' WHERE id=1;";
			$db->setQuery($query);
			$result = $db->query();
		}  
			catch(exception $e) {
				$query = "CREATE TABLE IF NOT EXISTS #__ga_dash (id INT NOT NULL , token VARCHAR(255) NOT NULL);";
				$db->setQuery($query);
				$result = $db->query();		

				$query = "INSERT INTO #__ga_dash (id, token) VALUES (1, '$token');";
				$db->setQuery($query);
				$result = $db->query();		
		}		
	
	}		
	
	public static function get_token (){

		$db = JFactory::getDBO();
		try { 
		$query = "SELECT token FROM #__ga_dash";
		$db->setQuery($query);		
		$result = $db->loadResult();
		}  
			catch(exception $e) {
			return; 
		}
		return $result;
	
	}
	
    public static function ga_generate_code( $params ){
		
		require_once dirname(__FILE__).'/functions.php';
		
		$scriptUri = JURI::current();
		
		if (isset($_REQUEST['ga_dash_reset_token'])){
			$db = JFactory::getDBO();
			$query = "DROP TABLE IF EXISTS #__ga_dash";
			$db->setQuery($query);		
			$result = $db->loadResult();
			ga_dash_clear_cache();			
			header("Location: ".$scriptUri);
			return;
		}
		
		if (isset($_REQUEST['ga_dash_clear_cache'])){
			ga_dash_clear_cache();
			header("Location: ".$scriptUri);
			return;
		}		
	
		
		if (!class_exists('Google_Exception')) {
			require_once dirname(__FILE__).'/src/Google_Client.php';
		}
			
		require_once dirname(__FILE__).'/src/contrib/Google_AnalyticsService.php';

		$client = new Google_Client();
		$client->setAccessType('offline'); // default: offline
		$client->setApplicationName('Google Analytics Dashboard for Joomla');
		$client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
		
		if ($params->get('ga_api_key')){	
			$client->setClientId($params->get('ga_client_id'));
			$client->setClientSecret($params->get('ga_client_secret'));
			$client->setDeveloperKey($params->get('ga_api_key')); // API key
		}else{
			$client->setClientId('866889662555.apps.googleusercontent.com');
			$client->setClientSecret('0fqZWhlMsXHnrYG_8V9sndRi');
			$client->setDeveloperKey('AIzaSyBTwsIsXFHIpa8RhpziT8cbzg7iJ-bpZFo');
		}
		
		$service = new Google_AnalyticsService($client);

		if (self::get_token()) { // extract token from session and configure client
			$token = self::get_token();
			$client->setAccessToken($token);
		}

		if (!$client->getAccessToken()) { // auth call to google
			
			$authUrl = $client->createAuthUrl();
			
			if (!isset($_REQUEST['ga_dash_authorize'])){
					return '<div style="padding:20px;">'.JText::_('GAD_CODE_ACTION_D').' <a href="'.$authUrl.'" target="_blank">'.JText::_('GAD_CODE_ACTION').'</a><br /><br />'.
					'<form name="ga_dash_input" action="'.$scriptUri.'" method="get">
						<p><b>'.JText::_('GAD_ACCESS_CODE').' </b><input type="text" name="ga_dash_code" value="" size="61"></p>
						<input type="submit" class="button button-primary" name="ga_dash_authorize" value="'.JText::_('GAD_SAVE_CODE').'"/>
					</form>
				</div>';
			}else{
				if ($_REQUEST['ga_dash_code']){
					$client->authenticate($_REQUEST['ga_dash_code']);
					self::store_token($client->getAccessToken());
				} else{
					header("Location: ".$scriptUri);
				}	

			}
		}	

		$projectId = ga_dash_get_profiles($service,$client,$params);
		
		if (!$projectId){
			ga_dash_clear_cache();
			return "<br />&nbsp;&nbsp;Error: ".JText::_('GAD_INVALID')." - <a href='http://forum.deconf.com/en/joomla-extensions-f181/' target='_blank'>".JText::_('GAD_HELP')."</a><br /><br />";
		}
		
		if (isset($_REQUEST['query'])){
			$query = $_REQUEST['query'];
		}else{
			$query = "visits";
		}	
		
		if (isset($_REQUEST['period'])){
			$period = $_REQUEST['period'];
		}else{
			$period = "last30days";
		}

		switch ($period){

			case 'today'	:	$from = date('Y-m-d'); 
								$to = date('Y-m-d');
								break;

			case 'yesterday'	:	$from = date('Y-m-d', time()-24*60*60);
									$to = date('Y-m-d', time()-24*60*60);
									break;
			
			case 'last7days'	:	$from = date('Y-m-d', time()-7*24*60*60);
								$to = date('Y-m-d');
								break;	

			case 'last14days'	:	$from = date('Y-m-d', time()-14*24*60*60);
								$to = date('Y-m-d');
								break;	
								
			default	:	$from = date('Y-m-d', time()-30*24*60*60);
						$to = date('Y-m-d');
						break;

		}

		switch ($query){

			case 'visitors'	:	$title=JText::_('GAD_VISITORS'); break;

			case 'pageviews'	:	$title=JText::_('GAD_PAGE_VIEWS'); break;
			
			case 'visitBounceRate'	:	$title=JText::_('GAD_BOUNCE_RATE'); break;	

			case 'organicSearches'	:	$title=JText::_('GAD_ORGANIC_SEARCHES'); break;
			
			default	:	$title=JText::_('GAD_VISITS');

		}

		$metrics = 'ga:'.$query;
		$dimensions = 'ga:year,ga:month,ga:day';
		try{
				$serial='gadash_qr2'.str_replace(array('ga:',',','-',date('Y')),"",$projectId.$from.$to.$metrics);
				$transient = ga_dash_cache_get($serial);
				if ( !$transient ){
					$data = $service->data_ga->get('ga:'.$projectId, $from, $to, $metrics, array('dimensions' => $dimensions));
					ga_dash_cache_set( $serial, $data, $params->get('ga_dash_cache') );
				}else{
					$data = $transient;
				}
			}	
			catch (Google_ServiceException $e) {
			return "<br />&nbsp;&nbsp;Error: ".$e->getMessage()." - <a href='http://forum.deconf.com/en/joomla-extensions-f181/' target='_blank'>".JText::_('GAD_HELP')."</a><br /><br />"; 
		}
		$chart1_data="";
		for ($i=0;$i<$data['totalResults'];$i++){

			$chart1_data.="['".$data['rows'][$i][0]."-".$data['rows'][$i][1]."-".$data['rows'][$i][2]."',".round($data['rows'][$i][3],2)."],";

		}

		$metrics = 'ga:visits,ga:visitors,ga:pageviews,ga:visitBounceRate,ga:organicSearches,ga:timeOnSite';
		$dimensions = 'ga:year';
		try{
			$serial='gadash_qr3'.str_replace(array('ga:',',','-',date('Y')),"",$projectId.$from.$to);
			$transient = ga_dash_cache_get($serial);
			if ( !$transient ){
				$data = $service->data_ga->get('ga:'.$projectId, $from, $to, $metrics, array('dimensions' => $dimensions));
				ga_dash_cache_set( $serial, $data, $params->get('ga_dash_cache') );
			}else{
				$data = $transient;
			}		
		}  
			catch (Google_ServiceException $e) {
			return "<br />&nbsp;&nbsp;Error: ".$e->getMessage()." - <a href='http://forum.deconf.com/en/joomla-extensions-f181/' target='_blank'>".JText::_('GAD_HELP')."</a><br /><br />"; 
		}	
		
    $code='<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(ga_dash_callback);

	  function ga_dash_callback(){
			ga_dash_drawstats();
			if(typeof ga_dash_drawmap == "function"){
				ga_dash_drawmap();
			}
			if(typeof ga_dash_drawpgd == "function"){
				ga_dash_drawpgd();
			}			
			if(typeof ga_dash_drawrd == "function"){
				ga_dash_drawrd();
			}
			if(typeof ga_dash_drawsd == "function"){
				ga_dash_drawsd();
			}
			if(typeof ga_dash_drawtraffic == "function"){
				ga_dash_drawtraffic();
			}			
	  }	

      function ga_dash_drawstats() {
        var data = google.visualization.arrayToDataTable(['."
          ['".JText::_('GAD_DATE')."', '".$title."'],"
		  .$chart1_data.
		"  
        ]);

        var options = {
		  legend: {position: 'none'},	
		  pointSize: 3,
          title: '".$title."',
		  chartArea: {width: '80%', height: '50%'},
          hAxis: { title: '".JText::_('GAD_DATE')."',  titleTextStyle: {color: 'darkblue'}, showTextEvery: 5}
		};

        var chart = new google.visualization.AreaChart(document.getElementById('chart1_div'));
		chart.draw(data, options);
		
      }";

	if ($params->get('ga_enable_map')){
		$ga_dash_visits_country=ga_dash_visits_country($service, $projectId, $from, $to, $params);
		if ($ga_dash_visits_country){
		 $code.='
			google.load("visualization", "1", {packages:["geochart"]});
			function ga_dash_drawmap() {
			var data = google.visualization.arrayToDataTable(['."
			  ['Country', 'Visits'],"
			  .$ga_dash_visits_country.
			"  
			]);
			
			var options = {
				colors: ['white', 'blue']
			};
			
			var chart = new google.visualization.GeoChart(document.getElementById('ga_dash_mapdata'));
			chart.draw(data, options);
			
		  }";
		}
	}

	if ($params->get('ga_enable_traffic')){
		$ga_dash_traffic_sources=ga_dash_traffic_sources($service, $projectId, $from, $to, $params);
		$ga_dash_new_return=ga_dash_new_return($service, $projectId, $from, $to, $params);
		if ($ga_dash_traffic_sources AND $ga_dash_new_return){
		 $code.='
			google.load("visualization", "1", {packages:["corechart"]});
			function ga_dash_drawtraffic() {
			var data = google.visualization.arrayToDataTable(['."
			  ['Source', 'Visits'],"
			  .$ga_dash_traffic_sources.
			'  
			]);

			var datanvr = google.visualization.arrayToDataTable(['."
			  ['Type', 'Visits'],"
			  .$ga_dash_new_return.
			"  
			]);
			
			var chart = new google.visualization.PieChart(document.getElementById('ga_dash_trafficdata'));
			chart.draw(data, {
				is3D: true,
				tooltipText: 'percentage',
				legend: 'none',
				title: 'Traffic Sources'
			});
			
			var chart1 = new google.visualization.PieChart(document.getElementById('ga_dash_nvrdata'));
			chart1.draw(datanvr,  {
				is3D: true,
				tooltipText: 'percentage',
				legend: 'none',
				title: 'New vs. Returning'
			});
			
		  }";
		}
	}
	
	if ($params->get('ga_enable_pgd')){
		$ga_dash_top_pages=ga_dash_top_pages($service, $projectId, $from, $to, $params);
		if ($ga_dash_top_pages){
		 $code.='
			google.load("visualization", "1", {packages:["table"]});
			function ga_dash_drawpgd() {
			var data = google.visualization.arrayToDataTable(['."
			  ['Top Pages', 'Visits'],"
			  .$ga_dash_top_pages.
			"  
			]);
			
			var options = {
				page: 'enable',
				pageSize: 6,
				width: '100%'
			};        
			
			var chart = new google.visualization.Table(document.getElementById('ga_dash_pgddata'));
			chart.draw(data, options);
			
		  }";
		}
	}

	if ($params->get('ga_enable_rd')){
		$ga_dash_top_referrers=ga_dash_top_referrers($service, $projectId, $from, $to, $params);
		if ($ga_dash_top_referrers){
		 $code.='
			google.load("visualization", "1", {packages:["table"]});
			function ga_dash_drawrd() {
			var datar = google.visualization.arrayToDataTable(['."
			  ['Top Referrers', 'Visits'],"
			  .$ga_dash_top_referrers.
			"  
			]);
			
			var options = {
				page: 'enable',
				pageSize: 6,
				width: '100%'
			};        
			
			var chart = new google.visualization.Table(document.getElementById('ga_dash_rdata'));
			chart.draw(datar, options);
			
		  }";
		}
	}
	
	if ($params->get('ga_enable_sd')){
		$ga_dash_top_searches=ga_dash_top_searches($service, $projectId, $from, $to, $params);
		if ($ga_dash_top_searches){
		 $code.='
			google.load("visualization", "1", {packages:["table"]});
			function ga_dash_drawsd() {
			
			var datas = google.visualization.arrayToDataTable(['."
			  ['Top Searches', 'Visits'],"
			  .$ga_dash_top_searches.
			"  
			]);
			
			var options = {
				page: 'enable',
				pageSize: 6,
				width: '100%'
			};        
			
			var chart = new google.visualization.Table(document.getElementById('ga_dash_sdata'));
			chart.draw(datas, options);
			
		  }";
		}
	}	
	
    $code.="</script>".'
	<div id="ga-dash">
	<center>
		<div id="buttons_div">
		<center>
			<input class="gabutton" type="button" value="'.JText::_('GAD_TODAY').'" onClick="window.location=\'?period=today&query='.$query.'\'" />
			<input class="gabutton" type="button" value="'.JText::_('GAD_YESTERDAY').'" onClick="window.location=\'?period=yesterday&query='.$query.'\'" />
			<input class="gabutton" type="button" value="'.JText::_('GAD_LAST7DAYS').'" onClick="window.location=\'?period=last7days&query='.$query.'\'" />
			<input class="gabutton" type="button" value="'.JText::_('GAD_LAST14DAYS').'" onClick="window.location=\'?period=last14days&query='.$query.'\'" />
			<input class="gabutton" type="button" value="'.JText::_('GAD_LAST30DAYS').'" onClick="window.location=\'?period=last30days&query='.$query.'\'" />
		</center>
		</div>
		
		<div id="chart1_div"></div>
		
		<div id="details_div">
			<center>
			<table class="gatable" cellpadding="4">
			<tr>
			<td width="24%">'.JText::_('GAD_VISITS').':</td>
			<td width="12%" class="gavalue"><a href="?query=visits&period='.$period.'" class="gatable">'.$data['rows'][0][1].'</td>
			<td width="24%">'.JText::_('GAD_VISITORS').':</td>
			<td width="12%" class="gavalue"><a href="?query=visitors&period='.$period.'" class="gatable">'.$data['rows'][0][2].'</a></td>
			<td width="24%">'.JText::_('GAD_PAGE_VIEWS').':</td>
			<td width="12%" class="gavalue"><a href="?query=pageviews&period='.$period.'" class="gatable">'.$data['rows'][0][3].'</a></td>
			</tr>
			<tr>
			<td>'.JText::_('GAD_BOUNCE_RATE').':</td>
			<td class="gavalue"><a href="?query=visitBounceRate&period='.$period.'" class="gatable">'.round($data['rows'][0][4],2).'%</a></td>
			<td>'.JText::_('GAD_ORGANIC_SEARCHES').':</td>
			<td class="gavalue"><a href="?query=organicSearches&period='.$period.'" class="gatable">'.$data['rows'][0][5].'</a></td>
			<td>'.JText::_('GAD_PAGES_VISIT').':</td>
			<td class="gavalue"><a href="#" class="gatable">'.(($data['rows'][0][1]) ? round($data['rows'][0][3]/$data['rows'][0][1],2) : '0').'</a></td>
			</tr>
			</table>
			</center>		
		</div>
	</center>		
	</div><center>';
	if ($params->get('ga_enable_map')){
		$code.='<div id="ga_dash_mapdata"></div>';
	}
	if ($params->get('ga_enable_traffic')){
		$code.='<table width="90%"><tr><td width="50%"><div id="ga_dash_trafficdata"></div></td><td width="50%"><div id="ga_dash_nvrdata"></div></td></tr></table>';
	}	
	if ($params->get('ga_enable_rd')){
		$code .= '<div id="ga_dash_rdata"></div>';
	}		
	if ($params->get('ga_enable_sd')){
		$code .= '<div id="ga_dash_sdata"></div>';
	}		
	if ($params->get('ga_enable_pgd')){
		$code .= '<div id="ga_dash_pgddata"></div>';
	}		
	$code.="</center>";
	return $code;    
	}
}
?>
