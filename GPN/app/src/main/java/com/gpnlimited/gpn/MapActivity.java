package com.gpnlimited.gpn;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.location.Location;
import android.media.ThumbnailUtils;
import android.preference.PreferenceManager;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.JsonObject;
import com.koushikdutta.async.future.Future;
import com.koushikdutta.async.future.FutureCallback;
import com.koushikdutta.ion.Ion;
import com.koushikdutta.ion.ProgressCallback;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.concurrent.ExecutionException;


public class MapActivity extends ActionBarActivity implements OnMapReadyCallback {
    ArrayList<NewsMarker> newsMarkerRowItems;
    Future<JsonObject> loading;
    JsonArray dataArray;
    GoogleMap mMap;
    LatLng loc = null;
    double currentLat = 0.0;
    double currentLong = 0.0;
    SharedPreferences sharedpreferences;
    public static final String serIP = "";
    private HashMap<Marker, NewsMarker> newsMarkersHashMap;
    ProgressDialog loadMapDlg;
    Boolean isLoaded = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_map);
        setResources();

    }

    private void setResources() {
        sharedpreferences = PreferenceManager.getDefaultSharedPreferences(this);
        newsMarkerRowItems = new ArrayList<NewsMarker>();
        newsMarkersHashMap = new HashMap<Marker, NewsMarker>();
    }

    @Override
    public void onMapReady(GoogleMap googleMap) {
        mMap = googleMap;

        //Map Setting
        //Disable the toolbar at the bottom right when the user pressed the marker
        mMap.getUiSettings().setMapToolbarEnabled(false);
        mMap.setMyLocationEnabled(true);
        mMap.setOnMyLocationChangeListener(myLocationChangeListenerForFirstTime);

        mMap.addMarker(new MarkerOptions()
                .title("Hard Code Testing Point")
                .position(new LatLng(-33.867, 151.206)));

        loadMarkers();
//        setUpMap();
//        plotMarkers(newsMarkerRowItems);
    }

    private void loadMarkers() {
        //When downloading the news info, a dialog will be shown
        showLoadingMapDialog();

        loading = Ion.with(MapActivity.this)
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cnewsposts?recordCount=15")
//                .load("http://14.199.123.48:689/fyp/laravel/public/cnewsposts?recordCount=50")
                .progressDialog(loadMapDlg)
                .setLogging("MapActivity", Log.DEBUG)
                .asJsonObject()
                .setCallback(new FutureCallback<JsonObject>() {
                    @Override
                    public void onCompleted(Exception e, JsonObject result) {
                        try {
                            if (e != null) {
                                throw e;
                            }

//                            Log.e("debugsomethingMAP", "All JSON " + result);
//                            Log.e("debugsomethingMAP", "STATUS: " + result.get("success"));

                            dataArray = result.getAsJsonArray("data");
//                            Log.e("debugsomethingMAP", "data Array: " + dataArray);
//                            Log.e("debugsomethingMAP", "data Array Size: " + dataArray.size());

                            Gson gson = new Gson();
                            for (int i = 0; i < dataArray.size(); i++) {
                                NewsBean newsBean = gson.fromJson(dataArray.get(i), NewsBean.class);

                                //Prepare the info of the new in order to create NewsMarker
                                String newsMK = String.valueOf(newsBean.mk);
                                String username = String.valueOf(newsBean.username);
                                String newsTitle = newsBean.newstitle;
                                String latitude = newsBean.latitude;
                                String longitude = newsBean.longitude;
                                String newsPhoto;
                                Bitmap newsPhotoBitmap = null;

                                Log.e("debuginformationMAP", "Bean ingfo: " + newsTitle + " " + newsBean.latitude);
                                if ((newsBean.photos.size() != 0)) {
                                    newsPhoto = newsBean.photos.get(0).jpg;
                                    newsPhotoBitmap = createNewsThumbnail(loadNewsIcons(newsPhoto));

                                } else {
                                    newsPhoto = "only_video";
                                }


                                NewsMarker newsMarker = new NewsMarker(newsMK, username, newsTitle, latitude, longitude, newsPhoto, newsPhotoBitmap);

                                newsMarkerRowItems.add(newsMarker);
                                Log.e("debuginformationMAP", "Marker Info: " + newsMarkerRowItems.get(i).getLatitude()
                                        + "Photo path: " + newsMarkerRowItems.get(i).getNewsPhoto());

//                                loc = new LatLng(Double.parseDouble(rowItems.get(i).latitude), Double.parseDouble(rowItems.get(i).longitude));
//                                mMap.addMarker(new MarkerOptions().title("" + rowItems.get(i).newstitle).position(loc));
                            }
                            setUpMap();
                            plotMarkers(newsMarkerRowItems);
                            //Dismiss the dialog after the markers are prepared
                            loadMapDlg.dismiss();

                        } catch (Exception ex) {
                            Log.e("debuginformationMAP", "exception: " + ex.toString());
                        }
                    }
                });
    }

    private void showLoadingMapDialog() {
        loadMapDlg = new ProgressDialog(this, AlertDialog.THEME_DEVICE_DEFAULT_DARK);
        loadMapDlg.setTitle("Loading the Map...");
        loadMapDlg.setMessage("Preparing the News Marker");
        loadMapDlg.setIndeterminate(true);
        loadMapDlg.setProgressStyle(ProgressDialog.STYLE_SPINNER);
        loadMapDlg.show();
        Window window = loadMapDlg.getWindow();
        window.setLayout(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT);
    }

    private void setUpMap() {
        Log.e("debuginformationMAP", "setUpMap() executed");
        mMap = null;
        // Do a null check to confirm that we have not already instantiated the map.
        if (mMap == null) {
            Log.e("debuginformationMAP", "setUpMap(): mMap is null");
            // Try to obtain the map from the SupportMapFragment.
            mMap = ((MapFragment) getFragmentManager().findFragmentById(R.id.map)).getMap();

            // Check if we were successful in obtaining the map.

            if (mMap != null) {
                Log.e("debuginformationMAP", "setUpMap(): mMap is not null");
                mMap.setOnMarkerClickListener(new GoogleMap.OnMarkerClickListener() {
                    @Override
                    public boolean onMarkerClick(com.google.android.gms.maps.model.Marker marker) {
                        marker.showInfoWindow();
                        return true;
                    }
                });

                mMap.setOnInfoWindowClickListener(new GoogleMap.OnInfoWindowClickListener() {
                    @Override
                    public void onInfoWindowClick(Marker marker) {
                        NewsMarker newsMarker = newsMarkersHashMap.get(marker);
                        String newsMK = newsMarker.getNewsMK();
                        Intent intent = new Intent(getApplicationContext(), CommentActivity.class);
                        intent.putExtra("newsMK", newsMK);
                        startActivity(intent);
                    }
                });

            } else
                Toast.makeText(getApplicationContext(), "Unable to create Maps", Toast.LENGTH_SHORT).show();
        }
    }

    private void plotMarkers(ArrayList<NewsMarker> markers) {
        if (markers.size() > 0) {
            for (NewsMarker newsMarker : markers) {

                // Create user marker with custom icon and other options
                MarkerOptions markerOption = new MarkerOptions().position(new LatLng(Double.parseDouble(newsMarker.getLatitude()), Double.parseDouble(newsMarker.getLongitude())));
                markerOption.icon(BitmapDescriptorFactory.defaultMarker(BitmapDescriptorFactory.HUE_VIOLET));


                Marker currentMarker = mMap.addMarker(markerOption);
                newsMarkersHashMap.put(currentMarker, newsMarker);

                mMap.setInfoWindowAdapter(new MarkerInfoWindowAdapter());
            }
        }
    }

    //When the current lat and current long are received, the map will be animated to the user's location promptly
    private GoogleMap.OnMyLocationChangeListener myLocationChangeListenerForFirstTime = new GoogleMap.OnMyLocationChangeListener() {
        @Override
        public void onMyLocationChange(Location location) {
            currentLat = location.getLatitude();
            currentLong = location.getLongitude();
            loc = new LatLng(location.getLatitude(), location.getLongitude());

            if (mMap != null) {
                mMap.animateCamera(CameraUpdateFactory.newLatLngZoom(loc, 16.0f));
            }
            mMap.setOnMyLocationChangeListener(null);
        }
    };

    @Override
    protected void onStart() {
        super.onStart();
        if (!isLoaded) {
            MapFragment mapFragment = (MapFragment) getFragmentManager()
                    .findFragmentById(R.id.map);
            isLoaded = true;
            mapFragment.getMapAsync(this);

        }
    }

    @Override
    protected void onResume() {
        super.onResume();
//        MapFragment mapFragment = (MapFragment) getFragmentManager()
//                .findFragmentById(R.id.map);
//        mapFragment.getMapAsync(this);
    }


//    private void loadMarkersBackup() {
//        loading = Ion.with(MapActivity.this)
//                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cnewsposts?recordCount=50")
////                .load("http://14.199.123.48:689/fyp/laravel/public/cnewsposts?recordCount=50")
//                .asJsonObject()
//                .setCallback(new FutureCallback<JsonObject>() {
//                    @Override
//                    public void onCompleted(Exception e, JsonObject result) {
//                        try {
//                            if (e != null) {
//                                throw e;
//                            }
//                            Log.e("debugsomethingMAP", "All JSON " + result);
//                            Log.e("debugsomethingMAP", "STATUS: " + result.get("success"));
//
//                            dataArray = result.getAsJsonArray("data");
//                            Log.e("debugsomethingMAP", "data Array: " + dataArray);
//                            Log.e("debugsomethingMAP", "data Array Size: " + dataArray.size());
//                            Gson gson = new Gson();
//                            for (int i = 0; i < dataArray.size(); i++) {
//                                NewsBean bean = gson.fromJson(dataArray.get(i), NewsBean.class);
//                                Log.e("debugsomethingMAP", "FROMJSON(bean): " + bean.latitude);
//                                Log.e("debugsomethingMAP", "dataArray.get(i): " + dataArray.get(i));
//
//                                rowItems.add(bean);
//
////                                Log.e("Marker(Long JSON): ", "" + dataArray.get(i).getAsJsonObject().get("longitude"));
//                                Log.e("debugsomethingMAP ", "Rowitems size:" + rowItems.size());
//
//                                Log.e("debugsomethingMAP ", "rowitem longitude" + rowItems.get(i).longitude);
//                                loc = new LatLng(Double.parseDouble(rowItems.get(i).latitude), Double.parseDouble(rowItems.get(i).longitude));
//                                mMap.addMarker(new MarkerOptions().title("" + rowItems.get(i).newstitle).position(loc));
//                            }
//
//                        } catch (Exception ex) {
//                            Log.e("debuginformationMAP", "exception: " + ex.toString());
//                        }
//                    }
//                });
//    }


    /**
     * ***********************************OPTION SETTING****************************************************
     */
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_map, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.refresh_map) {
            MapFragment mapFragment = (MapFragment) getFragmentManager()
                    .findFragmentById(R.id.map);
            mapFragment.getMapAsync(this);
        }

        return super.onOptionsItemSelected(item);
    }

    /**
     * ***********************************OPTION SETTING END****************************************************
     */

    private class MarkerInfoWindowAdapter implements GoogleMap.InfoWindowAdapter {
        public MarkerInfoWindowAdapter() {
        }

        @Override
        public View getInfoWindow(Marker marker) {
            return null;
        }

        @Override
        public View getInfoContents(Marker marker) {
            View v = getLayoutInflater().inflate(R.layout.map_custom_infowindow, null);
            NewsMarker newsMarker = newsMarkersHashMap.get(marker);

            TextView markerUserName = (TextView) v.findViewById(R.id.marker_user_name);
            TextView markerNewsTitleTV = (TextView) v.findViewById(R.id.marker_news_title);
            ImageView markerNewsIconTV = (ImageView) v.findViewById(R.id.marker_news_icon);

            Toast.makeText(getApplicationContext(), "Hello! I'm a marker", Toast.LENGTH_LONG).show();
            Toast.makeText(getApplicationContext(), "The photo path is: " + newsMarker.getNewsPhoto(), Toast.LENGTH_LONG).show();

            if (newsMarker.getNewsPhotoBitmap()==null) {
                markerNewsIconTV.setImageResource(R.drawable.videoicon);
            } else {
                markerNewsIconTV.setImageBitmap(newsMarker.getNewsPhotoBitmap());
            }

            markerUserName.setText("User: " + newsMarker.getUsername());
            markerNewsTitleTV.setText(newsMarker.getNewsTitle());

            return v;
        }
    }

    private Bitmap createNewsThumbnail(Bitmap loadBitmap) throws ExecutionException, InterruptedException {
        Bitmap bitmap = loadBitmap;
        // 获取缩略图
        bitmap = ThumbnailUtils.extractThumbnail(bitmap, 150, 100,
                ThumbnailUtils.OPTIONS_RECYCLE_INPUT);
        return bitmap;
    }

    public Bitmap loadNewsIcons(String url) throws ExecutionException, InterruptedException {
        Bitmap loadBitmap;
        if(url.equals("only_video")) {
            return null;
        } else {
            loadBitmap = Ion.with(this)
                    .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/newspost_photos/" + url)
//                .load("http://14.199.123.48:689/fyp/laravel/public/newspost_photos/" + url);
                    .withBitmap()
                    .asBitmap().get();
            return loadBitmap;
        }
    }

    public void loadMediaPhotos(ImageView imageView, String url) {
        Ion.with(imageView)
                .centerCrop()
                .fitXY()
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/newspost_photos/" + url);
//                .load("http://14.199.123.48:689/fyp/laravel/public/newspost_photos/" + url);
    }


}

