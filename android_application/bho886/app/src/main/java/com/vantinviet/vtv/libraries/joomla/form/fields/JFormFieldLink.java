package com.vantinviet.vtv.libraries.joomla.form.fields;

import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.beardedhen.androidbootstrap.BootstrapEditText;
import com.beardedhen.androidbootstrap.BootstrapLabel;
import com.vantinviet.vtv.libraries.joomla.JFactory;
import com.vantinviet.vtv.libraries.joomla.form.JFormField;
import com.vantinviet.vtv.libraries.legacy.application.JApplication;
import com.vantinviet.vtv.libraries.utilities.JUtilities;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by cuongnd on 6/11/2016.
 */
public class JFormFieldLink extends JFormField{
    static Map<String, JFormFieldText> map_form_field_text = new HashMap<String, JFormFieldText>();
    public JFormFieldLink(JSONObject field, String type, String name, String group, String value){
        this.type=type;
        this.name=name;
        this.group=group;
        this.option=field;
        this.value=value;
    }
    public JFormFieldLink(){
    }


    @Override
    public View getInput() {
        LinearLayout linear_layout = new LinearLayout(context);
        JSONObject option=this.option;
        boolean show_label=true;
        try {
            show_label = option.has("show_label")?option.getBoolean("show_label"):false;
        } catch (JSONException e) {
            e.printStackTrace();
        }

        if(show_label){
            BootstrapLabel label_text = new BootstrapLabel(context);
            label_text.setText(this.label);
            label_text.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT));
            ((LinearLayout) linear_layout).addView(label_text);
        }
        TextView text_view = new TextView(context);
        text_view.setText(this.value);
        text_view.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.FILL_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT));

        this.key_id= JUtilities.getRandomInt(0,999999);
        text_view.setId(this.key_id);
        text_view.setTag(this.key);
        text_view.setOnClickListener(new View.OnClickListener() {

            @Override
            public void onClick(View v) {
                String tag= (String) v.getTag();
                JFormFieldLink form_field_link=(JFormFieldLink)JFormField.map_form_field.get(tag);
                JSONObject option=form_field_link.option;
                String link="";
                JApplication app=JFactory.getApplication();
                JSONArray config_property;
                try {
                    config_property = option.has("config_property")?option.getJSONArray("config_property"):new JSONArray();
                    for(int i = 0; i < config_property.length(); i++){
                        String property_key = config_property.getJSONObject(i).getString("property_key");
                        String property_value = config_property.getJSONObject(i).getString("property_value");
                        if(property_key.equals("link"))
                        {
                            link=property_value;
                            break;
                        }
                    }
                    link=link.replaceAll("&amp;", "&");
                    app.setRedirect(link);

                    System.out.println(config_property);
                } catch (JSONException e) {
                    e.printStackTrace();
                }


                // TODO Auto-generated method stub
                //DO you work here

            }
        });
        ((LinearLayout) linear_layout).addView(text_view);
        linear_layout.setGravity(LinearLayout.TEXT_ALIGNMENT_GRAVITY);
        return (View)linear_layout;
    }
    public String getValue(){
        JApplication app= JFactory.getApplication();
        BootstrapEditText output_box = (BootstrapEditText) app.activity.findViewById(this.key_id);
        return output_box.getText().toString();
    }


    public void getOnClickListener() {

    }

}
