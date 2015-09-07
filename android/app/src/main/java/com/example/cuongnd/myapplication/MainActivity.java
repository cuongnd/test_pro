package com.example.cuongnd.myapplication;

import android.app.ActionBar;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.app.ActionBarActivity;
import android.util.DisplayMetrics;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;


public class MainActivity extends ActionBarActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        View linearLayout =  findViewById(R.id.info);
        //LinearLayout layout = (LinearLayout) findViewById(R.id.info);


        TextView valueTV = new TextView(this);
        valueTV.setText("hallo hallo");
        valueTV.setId(Integer.parseInt("4"));
        valueTV.setLayoutParams(new ActionBar.LayoutParams(ActionBar.LayoutParams.FILL_PARENT, ActionBar.LayoutParams.WRAP_CONTENT));

        ((LinearLayout) linearLayout).addView(valueTV);
        // we will using AsyncTask during parsing
        DisplayMetrics displaymetrics = new DisplayMetrics();
        getWindowManager().getDefaultDisplay().getMetrics(displaymetrics);
        int height = displaymetrics.heightPixels;
        int width = displaymetrics.widthPixels;
        //String host="http://10.0.2.2:81/index.php?os=android&screen_size_height="+Integer.toString(height)+"&screen_size_width="+Integer.toString(width);
        String host="http://192.168.0.109:81/index.php?os=android&screen_size_height="+Integer.toString(height)+"&screen_size_width="+Integer.toString(width);
        new AsyncTaskParseJson().execute(host);
    }
    // you can make this class as another java file so it will be separated from your main activity.
    public class AsyncTaskParseJson extends AsyncTask<String, String, String> {

        final String TAG = "AsyncTaskParseJson.java";


        // contacts JSONArray
        JSONArray children = null;

        @Override
        protected void onPreExecute() {}

        @Override
        protected String doInBackground(String... arg0) {

            try {

                // instantiate our json parser
                JSONParser jParser = new JSONParser();

                // get json string from url
                JSONObject json = jParser.getJSONFromUrl(arg0[0]);
                int root_id = json.getInt("root_id");
                children = json.getJSONArray("children");
                tree_recurse(root_id,children);
                // loop through all users
/*
                for (int i = 0; i < dataJsonArr.length(); i++) {

                    JSONObject c = dataJsonArr.getJSONObject(i);

                    // Storing each json item in variable
                    String firstname = c.getString("firstname");
                    String lastname = c.getString("lastname");
                    String username = c.getString("username");

                    // show the values in our logcat
                    Log.e(TAG, "firstname: " + firstname
                            + ", lastname: " + lastname
                            + ", username: " + username);

                }
*/

            } catch (JSONException e) {
                e.printStackTrace();
            }

            return null;
        }

        @Override
        protected void onPostExecute(String strFromDoInBg) {}
    }
    public  void tree_recurse(int root_id,JSONArray children){
        System.out.println(children.toString());
        Toast toast=Toast.makeText(MainActivity.this,children.toString() ,   Toast.LENGTH_LONG);
        toast.show();
        //System.out.println(root_id);
        /*try {
            for (int i = 0; i < children.length(); i++) {


                JSONArray c = children.getJSONArray();
                System.out.println(c);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }*/

    }
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.action_settings) {
            return true;
        }

        return super.onOptionsItemSelected(item);
    }
}
