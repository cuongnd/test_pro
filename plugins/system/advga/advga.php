<?php

/**
 * @package		Advanced Google Analytics - Plugin for Joomla!
 * @author		Alin Marcu - http://deconf.com
 * @copyright	Copyright (c) 2010 - 2014 DeConf.com
 * @license		GNU/GPL license: http://www.gnu.org/licenses/gpl-2.0.html
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.html.parameter' );

class plgSystemadvga extends JPlugin {
 
 private $authorname, $categoryname, $pubyear;
 
 function plgSystemClickyTrackingCode(&$subject, $params) { 
	
	parent::__construct($subject, $params); 
    
	$mode = $this->params->def('mode', 1);
	
}

 function onContentAfterDisplay( $context, &$article, &$params ) {
	if ($context == "com_content.article"){ 
		$this->authorname=($article->created_by_alias) ? $article->created_by_alias : $article->author;
		$this->categoryname=$article->category_title;
		$temp=explode('-',$article->created);
		$this->pubyear=$temp[0];
		//echo $this->authorname.$this->categoryname.$this->pubyear;
	}	
 }

function onAfterRender(){

		$app = JFactory::getApplication();
		$user = JFactory::getUser();		
		
		if ( $app->isAdmin()){
			return;
		}
		
		if ((isset($user->groups[8]) || isset($user->groups[7])) AND (!$this->params->get('advga_trackadmin'))){
			return;
		}
		$tracking_events = "";
		if ($this->params->get('advga_event')){
		if (!$this->params->get('advga_tracktype')){
			JHtml::_('jquery.framework'); 		
			$tracking_events='
<script type="text/javascript">
(function($){
	$(window).load(function() {
		if (this._gat) {
			tks = this._gat._getTrackers();
			ga_track = function(p) {
				for (i=0; i < tks.length; i++) {
					var n = tks[i]._getName() !== "" ? tks[i]._getName()+"." : "";
					a = [];
					for (i2=0; i2 < p.length; i2++) {
						var b = i2===0 ? n+p[i2] : p[i2];
						a.push(b);
					}
					_gaq.push(a);
				}
			};
			$('."'".'a'."'".').filter(function() {
				return this.href.match(/.*\.('.$this->params->get('advga_downloadfjq').')/);
			}).click(function(e) {
				ga_track(['."'".'_trackEvent'."'".', '."'".'download'."'".', '."'".'click'."'".', this.href]);
			});
			$('."'".'a[href^="mailto"]'."'".').click(function(e) {
				ga_track(['."'".'_trackSocial'."'".', '."'".'email'."'".', '."'".'send'."'".', this.href]);
			 });
			var loc = location.host.split('."'".'.'."'".');
			while (loc.length > 2) { loc.shift(); }
			loc = loc.join('."'".'.'."'".');
			var localURLs = [
							  loc,
							  '."'".$this->params->get('advga_domain')."'".'
							];
			$('."'".'a[href^="http"]'."'".').filter(function() {
				for (var i = 0; i < localURLs.length; i++) {
					if (this.href.indexOf(localURLs[i]) == -1) return this.href;
				}
			}).click(function(e) {
				ga_track(['."'".'_trackEvent'."'".', '."'".'outbound'."'".', '."'".'click'."'".', this.href]);
			});
		}
	});
})(jQuery);
</script>';
		} else{
			JHtml::_('jquery.framework'); 		
			$tracking_events="<script type=\"text/javascript\">
(function($){
    $(window).load(function() {
            $('a').filter(function() {
				return this.href.match(/.*\.(".$this->params->get('advga_downloadfjq').")/);
            }).click(function(e) {
                ga('send','event', 'download', 'click', this.href);
            });
            $('a[href^=\"mailto\"]').click(function(e) {
                ga('send','event', 'email', 'send', this.href);
             });
            var loc = location.host.split('.');
            while (loc.length > 2) { loc.shift(); }
            loc = loc.join('.');
            var localURLs = [
                              loc,
                              "."'".$this->params->get('advga_domain')."'"."
                            ];
            $('a[href^=\"http\"]').filter(function() {
                for (var i = 0; i < localURLs.length; i++) {
                    if (this.href.indexOf(localURLs[i]) == -1) return this.href;
                }
            }).click(function(e) {
                ga('send','event', 'outbound', 'click', this.href);
            });
    });
})(jQuery);
</script>";		
		
		}
		}

		if (!$this->params->get('advga_tracktype')){
		
		$tracking_0="
\n<script type=\"text/javascript\">
var _gaq = _gaq || [];";
		
		} else{
		
		$domain = $this->params->get('advga_domain');
		$root = explode ( '/', $domain );
		preg_match ( "/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", str_ireplace ( 'www', '', isset ( $root [2] ) ? $root [2] : $domain ), $root );		
		$tracking_0="\n<script type=\"text/javascript\">
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', '".$this->params->get('advga_googleid')."', '".$root['domain']."');  
  ";		
		
		}
		if (!$this->params->get('advga_tracktype')){
			if ($this->params->get('advga_remarketing')){
				$tracking_2="
\n(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();

</script>";
			}else{
				$tracking_2="
\n(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();

</script>";			
			}	  
		}else{
			$tracking_2="\n</script>";
		}	
		if (!$this->params->get('advga_tracktype')){
			$tracking_push=$tracking_push="['_setAccount', '".$this->params->get('advga_googleid')."']";
		}else{
			$tracking_push="";
		}

		if ($this->params->get('advga_remarketing') AND $this->params->get('advga_tracktype')){
			$tracking_push.="\nga('require', 'displayfeatures');";
		}		
		
		if ($this->params->get('advga_anonim')){
			if (!$this->params->get('advga_tracktype')){
				$tracking_push.=", ['_gat._anonymizeIp']";
			} else{
				$tracking_push.="\nga('set', 'anonymizeIp', true);";
			}		
		}
		if((JRequest::getVar( 'view' ) == 'article' ) OR ( JRequest::getVar( 'view' ) == 'item' )){
		
			if ($this->authorname AND $this->params->get('advga_authors')){
				if ($this->params->get('advga_tracktype')){
					$tracking_push.="\nga('set', 'dimension1', '".$this->authorname."');";
				}else{
					$tracking_push.=", ['_setCustomVar',1,'author','".$this->authorname."',3]";
				}
			}
			
			if ($this->categoryname AND $this->params->get('advga_categories')){
				if ($this->params->get('advga_tracktype')){
					$tracking_push.="\nga('set', 'dimension2', '".$this->categoryname."');";
				}else{			
					$tracking_push.=", ['_setCustomVar',2,'categories','".$this->categoryname."',3]";
				}	
			
			}

			if ($this->pubyear AND $this->params->get('advga_pubyear')){
				if ($this->params->get('advga_tracktype')){
					$tracking_push.="\nga('set', 'dimension3', '".$this->pubyear."');";
				}else{				
					$tracking_push.=", ['_setCustomVar',3,'year','".$this->pubyear."',3]";
				}	
			
			}
			
		}

		if ($this->params->get('advga_usertype')){
			if (isset($user->username)){
				if ($this->params->get('advga_tracktype')){
					$tracking_push.="\nga('set', 'dimension4', 'registered');";
				}else{			
					$tracking_push.=", ['_setCustomVar',4,'user-type','registered',3]";
				}	
			
			}else{
				if ($this->params->get('advga_tracktype')){
					$tracking_push.="\nga('set', 'dimension4', 'guest');";
				}else{				
					$tracking_push.=", ['_setCustomVar',4,'user-type','guest',3]";
				}	
			
			}		
		}	
		
		if (!$this->params->get('advga_tracktype')){
			$tracking_1="\n_gaq.push(".$tracking_push.", ['_trackPageview']);";
			$tracking="\n<!-- BEGIN Advanced Google Analytics - http://deconf.com/advanced-google-analytics-joomla/ -->\n".$tracking_events.$tracking_0.$tracking_1.$tracking_2."\n<!-- END Advanced Google Analytics -->\n\n";			
		} else{
			$tracking_1="\nga('send', 'pageview');";
			$tracking="\n<!-- BEGIN Advanced Google Analytics - http://deconf.com/advanced-google-analytics-joomla/ -->\n".$tracking_events.$tracking_0.$tracking_push.$tracking_1.$tracking_2."\n<!-- END Advanced Google Analytics -->\n\n";			
		}
		
		$buffer = JResponse::getBody();
		$buffer = preg_replace ("/<\/head>/", $tracking."\n</head>", $buffer); 
		JResponse::setBody($buffer);
	
	return;
 
 }
 
}