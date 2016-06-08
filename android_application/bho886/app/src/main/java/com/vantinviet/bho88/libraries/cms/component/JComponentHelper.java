package com.vantinviet.bho88.libraries.cms.component;

import com.vantinviet.bho88.configuration.configuration;
import com.vantinviet.bho88.libraries.joomla.cache.cache;
import com.vantinviet.bho88.libraries.joomla.factory;
import com.vantinviet.bho88.libraries.utilities.md5;
import com.vantinviet.bho88.libraries.utilities.utilities;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by cuongnd on 6/8/2016.
 */
public class JComponentHelper {
    public static Map<String, String> content_component =new HashMap<String, String>();
    public static String getContentComponent(String link) {
        configuration config= factory.getConfig();
        String content ="";
        String md5_link= md5.encryptMD5(link);
        int caching=config.caching;
        if(caching==1)
        {
            content= cache.get_content_component(md5_link);
            if(content != null && !content.isEmpty()){
                content = utilities.callURL(link);
            }
            cache.set_content_component(md5_link, content);
        }else {
            content = content_component.get(md5_link);
            if(content != null && !content.isEmpty()){
                content = utilities.callURL(link);
                content_component.put(md5_link,content);
            }
        }
        return content;
    }
}
