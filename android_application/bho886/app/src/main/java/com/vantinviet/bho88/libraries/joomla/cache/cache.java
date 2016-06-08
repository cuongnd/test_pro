package com.vantinviet.bho88.libraries.joomla.cache;

import com.vantinviet.bho88.configuration.configuration;
import com.vantinviet.bho88.libraries.joomla.cache.jstorage.JCacheFile;
import com.vantinviet.bho88.libraries.joomla.factory;

/**
 * Created by cuongnd on 6/8/2016.
 */
public class cache {
    public static String get_content_component(String md5_link) {
        String content_component;
        configuration config= factory.getConfig();
        String cache_handler=config.cache_handler;
        if(cache_handler.equals("JCacheFile"))
        {
            content_component= JCacheFile.get_content_component(md5_link);
        }
        else{
            content_component="";
        }
        return content_component;
    }

    public static void set_content_component(String md5_link, String content) {
        configuration config= factory.getConfig();
        String cache_handler=config.cache_handler;
        if(cache_handler.equals("JCacheFile"))
        {
            JCacheFile.set_content_component(md5_link, content);
        }
        else{
        }
    }
}
