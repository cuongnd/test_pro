package com.vantinviet.bho88.libraries.legacy.request;

import org.json.JSONArray;
import org.json.JSONObject;

/**
 * Created by cuongnd on 6/9/2016.
 */
public class JRequest {
    private static JRequest ourInstance = new JRequest();
    private JSONObject request;

    public static JRequest getInstance() {
        return ourInstance;
    }

    private JRequest() {
    }

    public void setRequest(JSONObject request) {
        this.request = request;
    }

    public JSONObject getRequest() {
        return  this.request;
    }
}
