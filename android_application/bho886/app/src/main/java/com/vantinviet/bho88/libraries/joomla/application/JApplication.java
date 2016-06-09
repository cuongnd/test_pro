package com.vantinviet.bho88.libraries.joomla.application;

import com.vantinviet.bho88.configuration.configuration;
import com.vantinviet.bho88.libraries.android.http.JSONParser;
import com.vantinviet.bho88.libraries.cms.menu.menu;
import com.vantinviet.bho88.libraries.joomla.cache.cache;
import com.vantinviet.bho88.libraries.joomla.factory;
import com.vantinviet.bho88.libraries.utilities.md5;
import com.vantinviet.bho88.libraries.utilities.utilities;

import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by cuongnd on 6/7/2016.
 */
public class JApplication {
    public static Map<String, String> content_website =new HashMap<String, String>();
    public menu getMenu() {
        menu menu = com.vantinviet.bho88.libraries.cms.menu.menu.getInstance();
        return  menu;
    }

    public static String get_content_website(String link) {
        String md5_link= md5.encryptMD5(link);
        configuration config= factory.getConfig();
        String content ="";
        int caching=config.caching;
        if(caching==1)
        {
            content= cache.get_content_website(md5_link);
            if(content == null || content.isEmpty()){
                content = call_json_get_content_website(link);
                cache.set_content_website(md5_link, content);
            }
            return content;

        }else {
            content = content_website.get(md5_link);
            if(content != null && !content.isEmpty()){
                content = call_json_get_content_website(link);
                content_website.put(md5_link,content);
            }
        }
        return content;
    }

    private static String call_json_get_content_website(String link) {
        String return_json="";
        try {
            // instantiate our json parser
            JSONParser jParser = new JSONParser();
            // get json string from url
            JSONObject json = jParser.getJSONFromUrl(link);
            System.out.println(json.toString());
            return_json = json.toString();

        } catch (Throwable t) {
            t.printStackTrace();
        }
        return return_json;

    }
}
