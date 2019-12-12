package com.gpnlimited.gpn;

import android.app.ActionBar;
import android.app.TabActivity;
import android.content.Intent;
import android.content.pm.ActivityInfo;
import android.graphics.Color;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.TabHost;


public class MainActivity extends TabActivity implements TabHost.OnTabChangeListener {
    TabHost tabHost;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        //Disable the Landscape Orientation
        setRequestedOrientation (ActivityInfo.SCREEN_ORIENTATION_PORTRAIT);
        setContentView(R.layout.activity_main);

//        Setup all the view by FindViewById
        getAllView();

//        Setup the Tab in this MainActivity
        setUpTab();
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

    /************************Johnny's Method*******************************/
    public void getAllView() {
//        Tabhost is used to hold the tabs
        tabHost = (TabHost)findViewById(android.R.id.tabhost);
        tabHost.setup();
    }

    public void setUpTab() {
        //Add Tab

        tabHost.addTab(tabHost.newTabSpec("news")
                .setIndicator("")
                .setContent((new Intent(this, NewsActivity.class))));

        tabHost.addTab(tabHost.newTabSpec("map")
                .setIndicator("")
                .setContent((new Intent(this, MapActivity.class))));

        tabHost.addTab(tabHost.newTabSpec("camera")
                .setIndicator("")
                .setContent((new Intent(this, CameraActivityListview.class))));

        tabHost.addTab(tabHost.newTabSpec("PM")
                .setIndicator("")
                .setContent((new Intent(this, PMActivity.class))));

        tabHost.addTab(tabHost.newTabSpec("camera")
                .setIndicator("")
                .setContent((new Intent(this, ProfileActivity.class))));

        tabHost.setCurrentTab(0);
        tabHost.setOnTabChangedListener(this);

        //Set the color to the News tab and reset its height
        for(int i = 0; i < 5; i++) {
            tabHost.getTabWidget().getChildAt(i).getLayoutParams().height = 125;
            tabHost.getTabWidget().getChildAt(i).setBackgroundColor(Color.WHITE);
            switch(i) {
                case 0:
                    tabHost.getTabWidget().getChildAt(i).setBackgroundResource(R.drawable.navbarnews);
                    break;
                case 1:
                    tabHost.getTabWidget().getChildAt(i).setBackgroundResource(R.drawable.navbargps);
                    break;
                case 2:
                    tabHost.getTabWidget().getChildAt(i).setBackgroundResource(R.drawable.navbarcamera);
                    break;
                case 3:
                    tabHost.getTabWidget().getChildAt(i).setBackgroundResource(R.drawable.navbarpm);
                    break;
                case 4:
                    tabHost.getTabWidget().getChildAt(i).setBackgroundResource(R.drawable.navbarprofile);
                    break;
            }

        }
    }

    @Override
    public void onTabChanged(String tabId) {

    }
}
