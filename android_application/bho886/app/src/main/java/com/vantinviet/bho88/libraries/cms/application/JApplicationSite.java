package com.vantinviet.bho88.libraries.cms.application;

import com.vantinviet.bho88.libraries.cms.component.JComponentHelper;
import com.vantinviet.bho88.libraries.cms.component.JPluginHelper;
import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.joomla.user.JUser;
import com.vantinviet.bho88.libraries.legacy.request.JRequest;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.Hashtable;
import java.util.Map;

/**
 * Created by cuongnd on 6/10/2016.
 */
public class JApplicationSite extends JApplicationCms {
    private static JApplicationSite ourInstance;
    public  String client="";

    public static JApplicationSite getInstance(String client) {
        if (ourInstance == null) {
            ourInstance = new JApplicationSite(client);
        }
        return ourInstance;
    }

    public JApplicationSite(String client) {
        this.client=client;
    }

    public static void execute(JSONObject json_object) {
        doExecute(json_object);
    }

    public static void doExecute(JSONObject json_object) {
        initialiseApp(json_object);
    }

    public static void initialiseApp(JSONObject json_object) {
        try {
            JSONObject request = null;
            request = json_object.has("request")?json_object.getJSONObject("request"):new JSONObject();
            JRequest.request=request;
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JUser user = JFactory.getUser();
        int guestUserGroup=1;
        if(user.guest)
        {
            try {
                guestUserGroup = JComponentHelper.getParams("com_users").getInt("guest_usergroup");
            } catch (JSONException e) {
                e.printStackTrace();
            }
            user.groups.add(guestUserGroup);
        }
        JPluginHelper.importPlugin("system", "languagefilter");
        Map<String,String> option=new Hashtable<>();

    }


}
