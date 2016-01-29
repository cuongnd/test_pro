package com.vantinviet.bho88.modules.mod_menu;

import android.content.Context;
import android.view.View;
import android.widget.LinearLayout;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by cuongnd on 12/17/2015.
 */
public class modMenuHelper {
    public static void render_menu(Context context,View parent_object, JSONObject object)
    {
        System.out.println(object);
        LinearLayout linear_layout = new LinearLayout(context);
        linear_layout.setOrientation(LinearLayout.HORIZONTAL);
        try {
            JSONArray list_menu_item=object.getJSONArray("list_menu_item");
            for (int i=0;i<list_menu_item.length();i++) {
                /*BootstrapButton btn = new BootstrapButton(context);
                JSONObject menu_item=list_menu_item.getJSONObject(i);
                int id = menu_item.getInt("id");
                String title = menu_item.getString("title");
                btn.setId(id);
                btn.setText(title);
                Resources resource = context.getResources();
                ((LinearLayout) linear_layout).addView(btn);*/
            }

        } catch (JSONException e) {
            e.printStackTrace();
        }
        ((LinearLayout) parent_object).addView(linear_layout);

    }
}
