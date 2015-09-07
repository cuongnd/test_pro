<?php

// no direct access
defined('_JEXEC') or die;

class plgSystemSubiz_live_chat extends JPlugin
{
    public function onAfterRender()
        {
            if(JFactory::getApplication()->isSite())
            {
                $str = '';
                $subiz_licence_id = $this->params->get('subiz_licence_id', '');
                $str = '<script type="text/javascript">window._sbzq||function(e){e._sbzq=[];var t=e._sbzq;t.push(["_setAccount",' . $subiz_licence_id . ']);var n=e.location.protocol=="https:"?"https:":"http:";var r=document.createElement("script");r.type="text/javascript";r.async=true;r.src=n+"//static.subiz.com/public/js/loader.js";var i=document.getElementsByTagName("script")[0];i.parentNode.insertBefore(r,i)}(window);</script> ';
                $buffer = JResponse::getBody();
                if ($buffer) {
                    $str_re = str_replace("</body>", $str."</body>",$buffer);
                    JResponse::setBody($str_re);
                    return true;
                }
            }

		} 
}

?>