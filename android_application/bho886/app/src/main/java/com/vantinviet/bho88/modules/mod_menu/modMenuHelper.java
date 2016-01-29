package com.vantinviet.bho88.modules.mod_menu;

import android.content.Context;
import android.view.View;

import com.vantinviet.bho88.libraries.android.registry.Registry;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by cuongnd on 12/17/2015.
 */
public class modMenuHelper {

    public static  Context main_context;
    public static void render_menu(final Context context, View parent_object, JSONObject object, int width)
    {
        main_context=context;
        try {
            String params=object.getString("params");
            JSONObject json_object_params=new JSONObject(params);
            Registry a_params = new Registry(json_object_params);
            System.out.println(json_object_params);
            String layout="";
            layout=a_params.get("layout","","string");
            if(layout.equals("sprflat:leftmenutour")){
                vertical_menu.render_menu_vertical(context, parent_object, object, width);
            }else{
                horizontal_menu.render_menu_horizontal(context, parent_object, object, width);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }

    }





}
