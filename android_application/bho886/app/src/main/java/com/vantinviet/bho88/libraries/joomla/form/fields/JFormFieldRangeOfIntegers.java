package com.vantinviet.bho88.libraries.joomla.form.fields;

import android.graphics.Color;
import android.text.InputType;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsoluteLayout;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.beardedhen.androidbootstrap.BootstrapEditText;
import com.beardedhen.androidbootstrap.BootstrapLabel;
import com.beardedhen.androidbootstrap.api.defaults.DefaultBootstrapBrand;
import com.beardedhen.androidbootstrap.api.defaults.DefaultBootstrapSize;
import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.joomla.form.JFormField;
import com.vantinviet.bho88.libraries.legacy.application.JApplication;
import com.vantinviet.bho88.libraries.utilities.JUtilities;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by cuongnd on 6/11/2016.
 */
public class JFormFieldRangeOfIntegers extends JFormField{
    static Map<String, JFormFieldRangeOfIntegers> map_form_field_text = new HashMap<String, JFormFieldRangeOfIntegers>();
    private AbsoluteLayout control_linear_layout;

    public JFormFieldRangeOfIntegers(JSONObject field, String type, String name, String group, String value){
        this.type=type;
        this.name=name;
        this.group=group;
        this.option=field;
        this.value=value;
    }
    public JFormFieldRangeOfIntegers(){
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
        BootstrapEditText input_number = new BootstrapEditText(context);
        input_number.setBootstrapBrand(DefaultBootstrapBrand.PRIMARY);
        input_number.setBootstrapSize(DefaultBootstrapSize.LG);
        input_number.setInputType(InputType.TYPE_CLASS_NUMBER);
        input_number.setText(this.value);
        input_number.setTag(this.key);
        input_number.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.FILL_PARENT, ViewGroup.LayoutParams.WRAP_CONTENT));

        this.key_id= JUtilities.getRandomInt(0,999999);
        input_number.setId(this.key_id);



        this.control_linear_layout = new AbsoluteLayout (context);

        TextView stateTitletv = new TextView(context);

        stateTitletv.setText("tv1");






        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(ViewGroup.LayoutParams.FILL_PARENT, ViewGroup.LayoutParams.FILL_PARENT);
        params.setMargins(0, 100, 0, 10);




        stateTitletv.setLayoutParams(params);
        LinearLayout content_control_linear_layout=new LinearLayout(context);
        ((LinearLayout ) content_control_linear_layout).addView(stateTitletv);
        //content_control_linear_layout.setLayoutParams(params);


        content_control_linear_layout.setBackgroundColor(Color.parseColor("#FF0000"));
        this.control_linear_layout.setVisibility(LinearLayout.INVISIBLE);
        ((AbsoluteLayout) this.control_linear_layout).addView(content_control_linear_layout,params);
        ((LinearLayout) linear_layout).addView(this.control_linear_layout);


        input_number.setOnFocusChangeListener(new View.OnFocusChangeListener() {
            public String tag;
            BootstrapEditText input_number;
            @Override
            public void onFocusChange(View v, boolean hasFocus) {
                if(hasFocus){
                    input_number = (BootstrapEditText)v.findFocus();
                    tag = (String)input_number.getTag();
                    JFormFieldRangeOfIntegers FormFieldRangeOfInteger=(JFormFieldRangeOfIntegers)JFormField.map_form_field.get(tag);
                    FormFieldRangeOfInteger.control_linear_layout.setVisibility(LinearLayout.VISIBLE);
                }else {
                    JFormFieldRangeOfIntegers FormFieldRangeOfInteger=(JFormFieldRangeOfIntegers)JFormField.map_form_field.get(tag);
                    FormFieldRangeOfInteger.control_linear_layout.setVisibility(LinearLayout.INVISIBLE);
                }
            }
        });



        ((LinearLayout) linear_layout).addView(input_number);





        linear_layout.setGravity(LinearLayout.TEXT_ALIGNMENT_GRAVITY);
        return (View)linear_layout;
    }


    public String getValue(){
        JApplication app= JFactory.getApplication();
        BootstrapEditText output_box = (BootstrapEditText) app.activity.findViewById(this.key_id);
        return output_box.getText().toString();
    }



}
