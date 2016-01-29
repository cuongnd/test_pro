<?php
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
	function ga_dash_clear_cache(){
		$db = JFactory::getDBO();
		$query = "DROP TABLE IF EXISTS #__ga_dash_cache";
		$db->setQuery($query);		
		$result = $db->loadResult();
	}
	
	function pretty_domain_name($domain){
	
		return str_replace(array("https://","http://"," "),"",rtrim($domain,"/"));
	
	}

	function ga_dash_cache_set($name, $data_in, $time){
		
		$db = JFactory::getDBO();
		$expire = time() + $time;
		$data = base64_encode(serialize( $data_in ));
		try { 
			$query = "REPLACE INTO #__ga_dash_cache (name, data, expire) VALUES ('$name', '$data', '$expire');";
			$db->setQuery($query);
			$result = $db->query();
		}catch(exception $e) {
			$query = "CREATE TABLE IF NOT EXISTS #__ga_dash_cache (name VARCHAR(255) NOT NULL , data TEXT NOT NULL, expire INT NOT NULL, UNIQUE KEY (name));";
			$db->setQuery($query);
			$result = $db->query();		

			$query = "INSERT INTO #__ga_dash_cache (name, data, expire) VALUES ('$name', '$data', '$expire');";
			$db->setQuery($query);
			$result = $db->query();		
		}
		
	}
	
	function ga_dash_cache_get($name){
		$expire = time();
		$db = JFactory::getDBO();
		try { 
			$query = "SELECT data FROM #__ga_dash_cache WHERE expire>'$expire' AND name='$name'";
			$db->setQuery($query);		
			$result = $db->loadResult();
			if ($result){
				$data = unserialize(base64_decode( $result ));
				return $data;
			}else{	
				return false;
			}		
		}  
			catch(exception $e) {
			return false; 
		}		

	}	
	
	function ga_dash_visits_country($service, $projectId, $from, $to, $params){

		$metrics = 'ga:visits'; 
		$dimensions = 'ga:country';
		try{
			$serial='gadash_qr7'.str_replace(array('ga:',',','-',date('Y')),"",$projectId.$from.$to);
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
		if (!$data['rows']){
			return 0;
		}
		
		$ga_dash_data="";
		for ($i=0;$i<$data['totalResults'];$i++){
			$ga_dash_data.="['".str_replace("'"," ",$data['rows'][$i][0])."',".$data['rows'][$i][1]."],";
		}

		return $ga_dash_data;
	}

// Get Traffic Sources
	function ga_dash_traffic_sources($service, $projectId, $from, $to, $params){

		$metrics = 'ga:visits'; 
		$dimensions = 'ga:medium';
		try{
			$serial='gadash_qr8'.str_replace(array('ga:',',','-',date('Y')),"",$projectId.$from.$to);
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
		if (!$data['rows']){
			return 0;
		}
		
		$ga_dash_data="";
		for ($i=0;$i<$data['totalResults'];$i++){
			$ga_dash_data.="['".str_replace("(none)","direct",$data['rows'][$i][0])."',".$data['rows'][$i][1]."],";
		}

		return $ga_dash_data;

	}

// Get New vs. Returning
	function ga_dash_new_return($service, $projectId, $from, $to, $params){

		$metrics = 'ga:visits'; 
		$dimensions = 'ga:visitorType';
		try{
			$serial='gadash_qr9'.str_replace(array('ga:',',','-',date('Y')),"",$projectId.$from.$to);
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
		
		if (!$data['rows']){
			return 0;
		}
		
		$ga_dash_data="";
		for ($i=0;$i<$data['totalResults'];$i++){
			$ga_dash_data.="['".str_replace("'"," ",$data['rows'][$i][0])."',".$data['rows'][$i][1]."],";
		}

		return $ga_dash_data;

	}	
	
// Get Top Pages
	function ga_dash_top_pages($service, $projectId, $from, $to, $params){

		$metrics = 'ga:pageviews'; 
		$dimensions = 'ga:pageTitle';
		try{
			$serial='gadash_qr4'.str_replace(array('ga:',',','-',date('Y')),"",$projectId.$from.$to);
			$transient = ga_dash_cache_get($serial);
			if ( !$transient ){
				$data = $service->data_ga->get('ga:'.$projectId, $from, $to, $metrics, array('dimensions' => $dimensions, 'sort' => '-ga:pageviews', 'max-results' => '24', 'filters' => 'ga:pagePath!=/'));
				ga_dash_cache_set( $serial, $data, $params->get('ga_dash_cache') );
			}else{
				$data = $transient;
			}	
		}  
			catch (Google_ServiceException $e) {
			return "<br />&nbsp;&nbsp;Error: ".$e->getMessage()." - <a href='http://forum.deconf.com/en/joomla-extensions-f181/' target='_blank'>".JText::_('GAD_HELP')."</a><br /><br />"; 
		}	
		if (!$data['rows']){
			return 0;
		}
		
		$ga_dash_data="";
		$i=0;
		while (isset($data['rows'][$i][0])){
			$ga_dash_data.="['".str_replace("'"," ",$data['rows'][$i][0])."',".$data['rows'][$i][1]."],";
			$i++;
		}

		return $ga_dash_data;
	}

// Get Top referrers
	function ga_dash_top_referrers($service, $projectId, $from, $to, $params){

		$metrics = 'ga:visits'; 
		$dimensions = 'ga:source,ga:medium';
		try{
			$serial='gadash_qr5'.str_replace(array('ga:',',','-',date('Y')),"",$projectId.$from.$to);
			$transient = ga_dash_cache_get($serial);
			if ( !$transient ){
				$data = $service->data_ga->get('ga:'.$projectId, $from, $to, $metrics, array('dimensions' => $dimensions, 'sort' => '-ga:visits', 'max-results' => '24', 'filters' => 'ga:medium==referral'));	
				ga_dash_cache_set( $serial, $data, $params->get('ga_dash_cache') );
			}else{
				$data = $transient;
			}
		}  
			catch (Google_ServiceException $e) {
			return "<br />&nbsp;&nbsp;Error: ".$e->getMessage()." - <a href='http://forum.deconf.com/en/joomla-extensions-f181/' target='_blank'>".JText::_('GAD_HELP')."</a><br /><br />"; 
		}	
		if (!$data['rows']){
			return 0;
		}
		
		$ga_dash_data="";
		$i=0;
		while (isset($data['rows'][$i][0])){
			$ga_dash_data.="['".str_replace("'"," ",$data['rows'][$i][0])."',".$data['rows'][$i][2]."],";
			$i++;
		}

		return $ga_dash_data;
	}

// Get Top searches
	function ga_dash_top_searches($service, $projectId, $from, $to, $params){

		$metrics = 'ga:visits'; 
		$dimensions = 'ga:keyword';
		try{
			$serial='gadash_qr6'.str_replace(array('ga:',',','-',date('Y')),"",$projectId.$from.$to);
			$transient = ga_dash_cache_get($serial);
			if ( !$transient ){
				$data = $service->data_ga->get('ga:'.$projectId, $from, $to, $metrics, array('dimensions' => $dimensions, 'sort' => '-ga:visits', 'max-results' => '24', 'filters' => 'ga:keyword!=(not provided);ga:keyword!=(not set)'));
				ga_dash_cache_set( $serial, $data, $params->get('ga_dash_cache') );
			}else{
				$data = $transient;
			}		
		}  
			catch (Google_ServiceException $e) {
			return "<br />&nbsp;&nbsp;Error: ".$e->getMessage()." - <a href='http://forum.deconf.com/en/joomla-extensions-f181/' target='_blank'>".JText::_('GAD_HELP')."</a><br /><br />"; 
		}	
		if (!$data['rows']){
			return 0;
		}
		
		$ga_dash_data="";
		$i=0;
		while (isset($data['rows'][$i][0])){
			$ga_dash_data.="['".str_replace("'"," ",$data['rows'][$i][0])."',".$data['rows'][$i][1]."],";
			$i++;
		}

		return $ga_dash_data;
	}

	function ga_dash_get_profiles($service, $client, $params){
	
		try {
			$client->setUseObjects(true);
			$profile_switch="";
			$serial='gadash_qr1';
			$transient = ga_dash_cache_get($serial);
			if ( empty( $transient ) ){
				$profiles = $service->management_profiles->listManagementProfiles('~all','~all');
				ga_dash_cache_set( $serial, $profiles, 60*60*24 );
			}else{
				$profiles = $transient;		
			}
			
			$client->setUseObjects(false);
		}
		catch (Google_ServiceException $e) {
			return "<br />&nbsp;&nbsp;Error: ".$e->getMessage()." - <a href='http://forum.deconf.com/en/joomla-extensions-f181/' target='_blank'>".JText::_('GAD_HELP')."</a><br /><br />";
		}
		
		$items = $profiles->getItems();
		
		if (count($items) != 0) {
			foreach ($items as &$profile) {
				$profileid=$profile->getId();
				if (pretty_domain_name($profile->getwebsiteUrl())==pretty_domain_name($params->get('ga_domain'))){
					return $profile->getId();
				}
			}
			return $profileid;
		}
	
	}	

?>	