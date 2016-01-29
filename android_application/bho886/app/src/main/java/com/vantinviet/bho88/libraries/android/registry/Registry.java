package com.vantinviet.bho88.libraries.android.registry;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by cuongnd on 11/01/2016.
 */
public class Registry {
    JSONObject json_object_params;
    public Registry(JSONObject json_object_params) {
        this.json_object_params=json_object_params;
    }
    protected String _get(String path, String r_default, String type,JSONObject json_object_params) {
        String r_string="";
        if(path.indexOf(".")==-1)
        {
            if(json_object_params.has(path))
            {
                try {
                    r_string=json_object_params.getString(path);
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }else{
                r_string= r_default;
            }
        }
        else {
            String[] parts = path.split("\\.");
            String fist_item = "";
            String else_string = "";
            ArrayList<String> mylist = new ArrayList<String>();
            for(int i=0;i<parts.length;i++)
            {
                if(i==0)
                {

                    fist_item=parts[i];
                    System.out.println(fist_item);
                }else
                {
                    mylist.add(parts[i]); //this adds an element to the list.
                }
            }

            String[] stockArr = new String[mylist.size()];
            stockArr = mylist.toArray(stockArr);
            else_string=implodeArray(stockArr,".");

            if (json_object_params.has(fist_item)) {
                try {
                    json_object_params = json_object_params.getJSONObject(fist_item);
                    r_string= this._get(else_string, r_default, type, json_object_params);
                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }
        return r_string;

    }
    public String get(String path, String r_default, String type) {
        return this._get(path, r_default, type, this.json_object_params);

    }
    /**
     * Method to join array elements of type string
     * @author Hendrik Will, imwill.com
     * @param inputArray Array which contains strings
     * @param glueString String between each array element
     * @return String containing all array elements seperated by glue string
     */
    public static String implodeArray(String[] inputArray, String glueString) {

/** Output variable */
        String output = "";

        if (inputArray.length > 0) {
            StringBuilder sb = new StringBuilder();
            sb.append(inputArray[0]);

            for (int i=1; i<inputArray.length; i++) {
                sb.append(glueString);
                sb.append(inputArray[i]);
            }

            output = sb.toString();
        }

        return output;
    }
}
