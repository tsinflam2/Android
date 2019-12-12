package com.gpnlimited.gpn;

import android.app.ActionBar;
import android.app.Activity;
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
import android.webkit.WebChromeClient;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.Toast;

import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.JsonObject;
import com.koushikdutta.async.future.Future;
import com.koushikdutta.async.future.FutureCallback;
import com.koushikdutta.ion.Ion;

import java.util.ArrayList;
import java.util.List;


public class PMActivity extends ActionBarActivity {

    EditText editText;
    ListView inboxListView;
    List<MessageInboxBean> rowItems;
    TypedArray userIcons;
   // WebView NewsContent;
   Future<JsonObject> loading;
    String newsMK;
    SharedPreferences sharedpreferences;
    MessageInboxAdapter adapter;
    JsonArray dataArray;

    public static final String serIP = "";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_pm);



        findView();
        setResources();
        setListViewFirstTime();
        //setListView();

    }

    private void findView() {
       // NewsContent = (WebView) findViewById(R.id.NewsContent);
        inboxListView = (ListView)findViewById(R.id.InboxListView);
    }

    private void setResources() {
        userIcons = getResources().obtainTypedArray(R.array.icons);
        rowItems = new ArrayList<MessageInboxBean>();
        sharedpreferences = PreferenceManager.getDefaultSharedPreferences(this);
    }


    private void setListViewFirstTime() {
        //Download the news from server and set the adapter as well
        loading = Ion.with(getApplicationContext())
                //This comment is for changing the user MK after implementing Login activity
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cgeneralusers/37/pms")
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
                                MessageInboxBean bean = gson.fromJson(dataArray.get(i), MessageInboxBean.class);
                                Log.e("FROMJSON(bean)toJSON:", gson.toJson(bean));
                                rowItems.add(bean);
                                Log.e("******RowItems size: ", "" + rowItems.size());
                                //Log.e("RowItems " + i + ": ", rowItems.get(i).latitude);
                            }

                            //Set the adapter of News List View
                            adapter = new MessageInboxAdapter(getApplicationContext(),rowItems);
                            inboxListView.setAdapter(adapter);

                        } catch (Exception ex) {
                            Log.e("debuginformation", "exception: " + ex.toString());
                        }
                    }
                });

        inboxListView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Intent i = new Intent().setClass(getApplicationContext(), MessengerActivity.class);
                int mymk = 37;
                int mkFrom = rowItems.get(position).getREGISTEREDUSER_MK_FROM();
                if (mymk == mkFrom)
                    i.putExtra("mkFrom", rowItems.get(position).getREGISTEREDUSER_MK_TO());
                else
                    i.putExtra("mkFrom", mkFrom);
                i.putExtra("chatWithUser", rowItems.get(position).getUSERNAME());
                startActivity(i);
            }
        });
    }

    /*
    private void setListView() {
        //Clear the rowItems (DataSet)
        rowItems.clear();

        //Download the news from server and set the adapter as well
        loading = Ion.with(this)
                //This comment is for changing the user MK after implementing Login activity
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cgeneralusers/37/pms")
//
                .asJsonObject()
                .setCallback(new FutureCallback<JsonObject>() {
                    @Override
                    public void onCompleted(Exception e, JsonObject result) {
                        try {
                            if (e != null) {
                                throw e;
                            }

                            dataArray = result.getAsJsonArray("data");
                            Gson gson = new Gson();
                            for (int i = 0; i < dataArray.size(); i++) {
                                MessageInboxBean bean = gson.fromJson(dataArray.get(i), MessageInboxBean.class);
                                rowItems.add(bean);
                            }

                            //Change the data of the adapter of inbox list
                            BaseAdapter adapter = (BaseAdapter) inboxListView.getAdapter();
                            adapter.notifyDataSetChanged();

                        } catch (Exception ex) {
                            Log.e("debuginformation", "set Inbox shit's exception: " + ex.toString());
                        }
                    }
                });
    }
    */

    /*
    private void saveMsgMK() {
        if (getIntent().getExtras() != null) {
            Bundle params = getIntent().getExtras();
            newsMK = params.getString("newsMK");
            Toast.makeText(getApplicationContext(), "RECEIVED NEWS MK: " + newsMK, Toast.LENGTH_LONG);
            Log.e("debugCommentActivity", "RECEIVED NEWS MK: " + newsMK);
        } else {
            Toast.makeText(getApplicationContext(), "NO MK RECEIVED / CANNOT RECEIVE MK" ,Toast.LENGTH_LONG);
            Log.e("debugCommentActivity" , "RECEIVED NEWS MK: " + newsMK);
        }
    }
*/

    private void setInboxListView() {
        rowItems = new ArrayList<MessageInboxBean>();
        for (int i = 0; i < userIcons.length(); i++) {
            MessageInboxBean messageInboxBean = new MessageInboxBean();
            messageInboxBean.setIcon(userIcons.getResourceId(i, -1));

            rowItems.add(messageInboxBean);
        }
        MessageInboxAdapter msgInboxAdapter = new MessageInboxAdapter(this, rowItems);
        inboxListView.setAdapter(msgInboxAdapter);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_pm, menu);
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
