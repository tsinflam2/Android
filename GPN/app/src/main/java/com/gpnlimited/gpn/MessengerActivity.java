package com.gpnlimited.gpn;

import android.content.Intent;
import android.content.SharedPreferences;
import android.content.res.TypedArray;
import android.preference.PreferenceManager;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;

import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.JsonObject;
import com.koushikdutta.async.future.Future;
import com.koushikdutta.async.future.FutureCallback;
import com.koushikdutta.ion.Ion;

import java.util.ArrayList;
import java.util.List;


public class MessengerActivity extends ActionBarActivity {

    EditText editText;
    ListView messengerListView;
    List<MessageBean> rowItems;
    TypedArray userIcons;
    // WebView NewsContent;
    Future<JsonObject> loading;
    String newsMK;
    SharedPreferences sharedpreferences;
    MessageAdapter adapter;
    JsonArray dataArray;

    Button sendBtn;
    EditText AddMsgEditText;

    int from;
    String to;

    public static final String serIP = "";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_messenger);
        from = getIntent().getIntExtra("mkFrom", 0);
        to = getIntent().getStringExtra("chatWithUser");

        findView();
        setResources();
        setListViewFirstTime();
        //setListView();

    }

    private void findView() {
        // NewsContent = (WebView) findViewById(R.id.NewsContent);
        messengerListView = (ListView) findViewById(R.id.MessengerListView);
        AddMsgEditText = (EditText) findViewById(R.id.AddMsgEditText);
        sendBtn = (Button) findViewById(R.id.sendMsgBtn);

        sendBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                JsonObject json = new JsonObject();
                json.addProperty("message", MessengerActivity.this.AddMsgEditText.getText().toString());

                Ion.with(getApplicationContext())
                        .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cgeneralusers/" + 37 + "/pms/" + getIntent().getIntExtra("mkFrom", 0))
                        .setJsonObjectBody(json)
                        .asJsonObject()
                        .setCallback(new FutureCallback<JsonObject>() {
                            @Override
                            public void onCompleted(Exception e, JsonObject result) {
                                // do stuff with the result or error
                            }
                        });

            }
        });
    }

    private void setResources() {
        userIcons = getResources().obtainTypedArray(R.array.icons);
        rowItems = new ArrayList<MessageBean>();
        sharedpreferences = PreferenceManager.getDefaultSharedPreferences(this);
    }


    private void setListViewFirstTime() {
        //Download the news from server and set the adapter as well
        loading = Ion.with(getApplicationContext())
                //This comment is for changing the user MK after implementing Login activity
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cgeneralusers/" + 37 + "/pms/" + from)
//                .load("http://14.199.123.48:689/fyp/laravel/public/cnewsposts?recordCount=30&registereduser_mk=37&sort=-proximity&currentLat=" + currentLatitude +
//                        "&currentLong=" + currentLongitude)
                .asJsonObject()
                .setCallback(new FutureCallback<JsonObject>() {
                    @Override
                    public void onCompleted(Exception e, JsonObject result) {
                        try {
                            if (e != null) {
                                throw e;
                            }
                            Log.e("debugsomething", "All JSON " + result);

                            dataArray = result.getAsJsonArray("data");
                            Gson gson = new Gson();
                            for (int i = 0; i < dataArray.size(); i++) {
                                MessageBean bean = gson.fromJson(dataArray.get(i), MessageBean.class);
                                Log.e("FROMJSON(bean)toJSON:", gson.toJson(bean));
                                rowItems.add(bean);
                                Log.e("******RowItems size: ", "" + rowItems.size());
                                //Log.e("RowItems " + i + ": ", rowItems.get(i).latitude);
                            }

                            //Set the adapter of News List View
                            adapter = new MessageAdapter(getApplicationContext(), rowItems, MessengerActivity.this);
                            adapter.setMymk(37);
                            MessengerActivity.this.setTitle(rowItems.get(0).getUSERNAME_FROM());
                            messengerListView.setAdapter(adapter);

                        } catch (Exception ex) {
                            Log.e("debuginformation", "exception: " + ex.toString());
                        }
                    }
                });

        messengerListView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {

            }
        });
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_messenger, menu);
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