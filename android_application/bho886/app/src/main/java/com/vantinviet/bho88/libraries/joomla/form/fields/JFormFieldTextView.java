package com.vantinviet.bho88.libraries.joomla.form.fields;

import android.content.Context;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.beardedhen.androidbootstrap.BootstrapEditText;
import com.beardedhen.androidbootstrap.BootstrapLabel;
import com.beardedhen.androidbootstrap.api.defaults.DefaultBootstrapBrand;
import com.vantinviet.bho88.libraries.android.registry.JRegistry;
import com.vantinviet.bho88.libraries.cms.menu.JMenu;
import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.legacy.application.JApplication;
import com.vantinviet.bho88.libraries.joomla.form.JFormField;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by cuongnd on 6/11/2016.
 */
public class JFormFieldTextView extends JFormField {
    public Context context;
    public JFormFieldTextView(String type, String fieldName, String group){
    }

    @Override
    public View getInput() {
        JApplication app= JFactory.getApplication();
        context=app.context;
        TextView text_view = new TextView(context);

        text_view.setText(this.value);
        text_view.setPadding(20, 10, 20, 10);
        text_view.setTextSize(23);

        try {
            JMenu menu=JFactory.getMenu();
            JSONObject menu_active=menu.getMenuActive();
            JRegistry menu_active_params = null;
            menu_active_params = JRegistry.getParams(menu_active);
            String android_render_form_type=menu_active_params.get("android_render_form_type","list","String");
            if(android_render_form_type.equals("list"))
            {
                return text_view;
            }else{
                LinearLayout linear_layout = new LinearLayout(context);
                BootstrapLabel label_text = new BootstrapLabel(context);
                label_text.setText(this.label);
                BootstrapEditText edit_text = new BootstrapEditText(context);
                edit_text.setBootstrapBrand(DefaultBootstrapBrand.PRIMARY);
                edit_text.setText(this.value);
                edit_text.setWidth(200);
                ((LinearLayout) linear_layout).addView(label_text);
                ((LinearLayout) linear_layout).addView(edit_text);
                linear_layout.setGravity(LinearLayout.TEXT_ALIGNMENT_GRAVITY);
                return (View)linear_layout;
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }
        return text_view;

    }



}
