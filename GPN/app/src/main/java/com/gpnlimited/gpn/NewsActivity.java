package com.gpnlimited.gpn;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.res.TypedArray;
import android.location.Location;
import android.preference.PreferenceManager;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesClient;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.LocationServices;
import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.JsonObject;
import com.koushikdutta.async.future.Future;
import com.koushikdutta.async.future.FutureCallback;
import com.koushikdutta.ion.Ion;

import java.util.ArrayList;


public class NewsActivity extends ActionBarActivity implements GooglePlayServicesClient.ConnectionCallbacks,
        GooglePlayServicesClient.OnConnectionFailedListener, GoogleApiClient.ConnectionCallbacks,
        GoogleApiClient.OnConnectionFailedListener {
    ListView newsListView;
    String[] newsTitles;
    TypedArray newsIcon;
    NewsListFragmentAdapter newsListFragmentAdapter;
    ArrayList<NewsBean> rowItems;
    Future<JsonObject> loading;
    JsonArray dataArray;
    GoogleApiClient mGoogleApiClient;
    Location mLastLocation;
    static String currentLatitude, currentLongitude;
    Button mostPopular;
    Button nearbyNews;
    TextView report, share;
    public static final String MyPREFERENCES = "MyPrefs" ;
    public static final String serIP = "";

    SharedPreferences sharedpreferences;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_news);
        this.setTitle("GPN");
        findView();
        setResources();
        setSer2();
        Toast.makeText(getApplicationContext(), "Backup Server: " + sharedpreferences.getString(serIP, "NO IP"), Toast.LENGTH_LONG).show();
        setButtonListener();
        buildGoogleApiClient();
//        setListView(currentLatitude, currentLongitude);
    }

    private void findView() {
        newsListView = (ListView) this.findViewById(R.id.NewsListView);
        mostPopular = (Button) findViewById(R.id.MostPopular);
        nearbyNews = (Button) findViewById(R.id.NearbyNews);
        report = (TextView) findViewById(R.id.report);
        share = (TextView) findViewById(R.id.share);
    }

    private void setResources() {
        newsTitles = getResources().getStringArray(R.array.titles);
        newsIcon = getResources().obtainTypedArray(R.array.icons);
        rowItems = new ArrayList<NewsBean>();
//        sharedpreferences = getSharedPreferences(MyPREFERENCES, Context.MODE_PRIVATE);
        sharedpreferences = PreferenceManager.getDefaultSharedPreferences(this);

    }

    private void setListViewFirstTime(String currentLatitude, String currentLongitude) {
        //Download the news from server and set the adapter as well
        loading = Ion.with(this)
                //This comment is for changing the user MK after implementing Login activity
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cnewsposts?recordCount=30&registereduser_mk=37&sort=-proximity&currentLat="
                        + currentLatitude + "&currentLong=" + currentLongitude)
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
                                NewsBean bean = gson.fromJson(dataArray.get(i), NewsBean.class);
                                Log.e("FROMJSON(bean)toJSON:", gson.toJson(bean));
                                rowItems.add(bean);
                                Log.e("******RowItems size: ", "" + rowItems.size());
                                Log.e("RowItems " + i + ": ", rowItems.get(i).latitude);
                            }

                            //Set the adapter of News List View
                            newsListFragmentAdapter = new NewsListFragmentAdapter(NewsActivity.this, getApplicationContext(), rowItems);
                            newsListView.setAdapter(newsListFragmentAdapter);

                        } catch (Exception ex) {
                            Log.e("debuginformation", "exception: " + ex.toString());
                        }
                    }
                });

        newsListView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                String mk = String.valueOf(rowItems.get(position).mk);
                Intent intent = new Intent(getApplicationContext(), CommentActivity.class);
                intent.putExtra("newsMK", mk);
                startActivity(intent);
            }
        });
    }


    private void setListViewNearbyNews() {
        //Clear the rowItems (DataSet)
        rowItems.clear();

        //Download the news from server and set the adapter as well
        loading = Ion.with(this)
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cnewsposts?recordCount=30&registereduser_mk=37&sort=-proximity&currentLat="
                        + currentLatitude + "&currentLong=" + currentLongitude)
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

                            dataArray = result.getAsJsonArray("data");
                            Gson gson = new Gson();
                            for (int i = 0; i < dataArray.size(); i++) {
                                NewsBean bean = gson.fromJson(dataArray.get(i), NewsBean.class);
                                rowItems.add(bean);
                            }

                            //Change the data of the adapter of news list
                            BaseAdapter adapter = (BaseAdapter) newsListView.getAdapter();
                            adapter.notifyDataSetChanged();

                        } catch (Exception ex) {
                            Log.e("debuginformation", "setListViewMostPopular's exception: " + ex.toString());
                        }
                    }
                });
    }

    private void setListViewMostPopular() {
        //Clear the rowItems (DataSet)
        rowItems.clear();

        //Download the news from server and set the adapter as well
        loading = Ion.with(this)
                //This comment is for changing the user MK after implementing Login activity
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cnewsposts?recordCount=30&sort=-viewcount&registereduser_mk=37")
//                .load("http://14.199.123.48:689/fyp/laravel/public/cnewsposts?recordCount=30&sort=-viewcount&registereduser_mk=37")
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
                                NewsBean bean = gson.fromJson(dataArray.get(i), NewsBean.class);
                                rowItems.add(bean);
                            }

                            //Change the data of the adapter of news list
                            BaseAdapter adapter = (BaseAdapter) newsListView.getAdapter();
                            adapter.notifyDataSetChanged();

                        } catch (Exception ex) {
                            Log.e("debuginformation", "setListViewMostPopular's exception: " + ex.toString());
                        }
                    }
                });
    }

    private void setButtonListener() {
        mostPopular.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                setListViewMostPopular();
            }
        });

        nearbyNews.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                setListViewNearbyNews();
            }
        });
    }

    /**
     * ***************************For Option Setting***********************************
     */
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_news, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.ser_1) {
            setSer1();
            Toast.makeText(getApplicationContext(), "Main Server: " + sharedpreferences.getString(serIP, "NO IP"), Toast.LENGTH_LONG).show();
        }

        if (id == R.id.ser_2) {
            setSer2();
            Toast.makeText(getApplicationContext(), "Backup Server: " + sharedpreferences.getString(serIP, "NO IP"), Toast.LENGTH_LONG).show();
        }

        return super.onOptionsItemSelected(item);
    }

    private void setSer2() {
        SharedPreferences.Editor editor = sharedpreferences.edit();
        editor.putString(serIP, "http://14.199.123.48:689/");
        editor.commit();
    }

    private void setSer1() {
        SharedPreferences.Editor editor = sharedpreferences.edit();
        editor.putString(serIP, "http://14.199.123.48:689/");
        editor.commit();
    }
    /******************************End of Option Setting************************************/

    /**
     * ***************************For Retrieving Current Location***********************************
     */
    @Override
    protected void onStart() {
        super.onStart();
        mGoogleApiClient.connect();
    }

    @Override
    protected void onStop() {
        super.onStop();
        if (mGoogleApiClient.isConnected()) {
            mGoogleApiClient.disconnect();
        }
    }

    protected synchronized void buildGoogleApiClient() {
        mGoogleApiClient = new GoogleApiClient.Builder(this)
                .addConnectionCallbacks(this)
                .addOnConnectionFailedListener(this)
                .addApi(LocationServices.API)
                .build();
    }

    @Override
    public void onConnected(Bundle bundle) {
        mLastLocation = LocationServices.FusedLocationApi.getLastLocation(
                mGoogleApiClient);
        if (mLastLocation != null) {
            currentLatitude = (String.valueOf(mLastLocation.getLatitude()));
            currentLongitude = (String.valueOf(mLastLocation.getLongitude()));
            setListViewFirstTime(currentLatitude, currentLongitude);
        }

        Log.e("NewsActivity", "currentLatitude :" + currentLatitude);
        Log.e("NewsActivity", "currentLongitude :" + currentLongitude);

    }

    @Override
    public void onConnectionSuspended(int i) {

    }

    @Override
    public void onDisconnected() {

    }

    @Override
    public void onConnectionFailed(ConnectionResult connectionResult) {

    }
    /******************************End of Retrieving Current Location************************************/
}
