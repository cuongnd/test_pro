package com.vantinviet.bho88.libraries.joomla.form.fields;

import android.view.View;

import com.beardedhen.androidbootstrap.BootstrapButton;
import com.vantinviet.bho88.libraries.cms.component.JComponentHelper;
import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.joomla.application.JApplication;
import com.vantinviet.bho88.libraries.joomla.form.JFormField;
import com.vantinviet.bho88.libraries.utilities.JUtilities;

import org.json.JSONArray;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;
import java.util.HashMap;
import java.util.Map;

/**
 * Created by cuongnd on 6/11/2016.
 */
public class JFormFieldButton extends JFormField{
    static Map<String, JFormFieldButton> map_form_field_button = new HashMap<String, JFormFieldButton>();
    public JFormFieldButton(){

    }

    public JFormFieldButton(JSONObject field,String type, String name, String group,String value){
        this.type=type;
        this.name=name;
        this.group=group;
        this.field=field;
        this.value=value;
    }
    @Override
    public View getInput() {
        BootstrapButton button= new BootstrapButton(context);
        button.setText(this.label);
        button.setPadding(20, 10, 20, 10);
        button.setTextSize(23);
        button.setOnClickListener(getOnClickDoSomething(button));
        return button;
    }

    private View.OnClickListener getOnClickDoSomething(BootstrapButton button) {
        return new View.OnClickListener() {
            public void onClick(View v) {
                try {
                    JSONArray list_hidden_field_item= JComponentHelper.list_hidden_field_item;
                    Map<String, String>  map_str_hidden_field_item=JUtilities.getMapString(list_hidden_field_item,"name","default");
                    Map<String, String>  map_input=JComponentHelper.getMapStringInputComponent();
                    String link ="index.php?"+ JUtilities.http_build_query(map_str_hidden_field_item)+"&"+JUtilities.http_build_query(map_input);
                    System.out.println("link:"+link);
                    JApplication app= JFactory.getApplication();
                    app.setRedirect(link);
                } catch (UnsupportedEncodingException e) {
                    e.printStackTrace();
                }
            }
        };
    }

}
