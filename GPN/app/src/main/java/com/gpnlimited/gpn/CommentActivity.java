package com.gpnlimited.gpn;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.SharedPreferences;
import android.content.res.TypedArray;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.webkit.WebChromeClient;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.JsonObject;
import com.koushikdutta.async.future.FutureCallback;
import com.koushikdutta.ion.Ion;

import java.util.ArrayList;
import java.util.List;


public class CommentActivity extends Activity {
    EditText editText;
    ListView commentsListView;
    ArrayList<CommentBean> commentBeanRowItems;
    TypedArray userIcons;
//    WebView NewsContent;
    String newsMK;
    JsonArray dataArray;
    ProgressDialog loadDlg;
    ImageView userIcon;
    TextView userName, postedTime, report, share, newsTitle, favouriteCount;
    ImageButton favouriteIcon;
    NewsBean newsBean;
    CommentBean commentBean;
    LinearLayout NewsContentLayout;
    TextView empty;
    private Button submitComment;
    private EditText addCommentEditText;
    public static final String serIP = "";

    SharedPreferences sharedpreferences;



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_comment);

        lostFocusOfEditText();
        saveNewsMK();
        findView();
        setResources();

        //The loadComments() is executed inside the callback method of loadNewsData()
        loadNewsData();
        //For testing, the WebView will be filled in a video by hard coding
//        setTheWebView();


    }


    private void findView() {
        addCommentEditText = (EditText)findViewById(R.id.AddCommentEditText);
        userIcon = (ImageView) findViewById(R.id.UserIcon);
        userName = (TextView) findViewById(R.id.UserName);
        postedTime = (TextView) findViewById(R.id.PostedTime);
        newsTitle = (TextView) findViewById(R.id.NewsTitle);
        favouriteCount = (TextView) findViewById(R.id.FavouriteCount);
        empty = (TextView) findViewById(R.id.empty);
        favouriteIcon = (ImageButton) findViewById(R.id.FavouriteIcon);
        NewsContentLayout = (LinearLayout) findViewById(R.id.NewsContentLayout);
//        NewsContent = (WebView) findViewById(R.id.NewsContent);
        commentsListView = (ListView) findViewById(R.id.CommentsListView);
        submitComment = (Button)findViewById(R.id.SubmitComment);
        report = (TextView) findViewById(R.id.report);
        share = (TextView) findViewById(R.id.share);
    }

    private void setResources() {
        userIcons = getResources().obtainTypedArray(R.array.icons);
        sharedpreferences = PreferenceManager.getDefaultSharedPreferences(this);
        commentBeanRowItems = new ArrayList<CommentBean>();
        submitComment.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                sendComment(addCommentEditText.getText().toString());
            }
        });
    }

    private void sendComment(String comment) {
        Ion.with(this)
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cnewsposts/" + newsMK + "/comments")
                        // embedding twitter api key and secret is a bad idea, but this isn't a real twitter app :)
                .setBodyParameter("registereduser_mk", "37")
                .setBodyParameter("comment", comment)
                .asJsonObject()
                .setCallback(new FutureCallback<JsonObject>() {
                    @Override
                    public void onCompleted(Exception e, JsonObject result) {
                        if (e != null) {
                            Toast.makeText(getApplicationContext(), "Error loading tweets", Toast.LENGTH_LONG).show();
                            return;
                        } else {
                            loadComments("update");
                            Toast.makeText(getApplicationContext(), "Comment is sent.", Toast.LENGTH_LONG).show();
                        }
                    }
                });
    }

    private void updateCommentAdapter() {
        BaseAdapter adapter = (BaseAdapter) commentsListView.getAdapter();
        adapter.notifyDataSetChanged();
    }

    private void lostFocusOfEditText() {
        editText = (EditText) findViewById(R.id.AddCommentEditText);
        editText.clearFocus();
    }

    private void saveNewsMK() {
        if (getIntent().getExtras() != null) {
            Bundle params = getIntent().getExtras();
            newsMK = params.getString("newsMK");
            Toast.makeText(getApplicationContext(), "RECEIVED NEWS MK: " + newsMK, Toast.LENGTH_LONG);
            Log.e("CommentActivity", "RECEIVED NEWS MK: " + newsMK);
        } else {
            Toast.makeText(getApplicationContext(), "NO MK RECEIVED / CANNOT RECEIVE MK", Toast.LENGTH_LONG);
            Log.e("CommentActivity", "RECEIVED NEWS MK: " + newsMK);
        }
    }

    private void loadComments(final String kind) {
        showLoadingDialog("Comments");
        commentBeanRowItems.clear();

        //Download the news from server and set the adapter as well
        Ion.with(this)
                //This comment is for changing the user MK after implementing Login activity
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cnewsposts/" + newsMK + "/comments?registereduser_mk=37")
//                .load("http://14.199.123.48:689/fyp/laravel/public/cnewsposts?recordCount=30&registereduser_mk=37&sort=-proximity&currentLat=" + currentLatitude +
//                        "&currentLong=" + currentLongitude)
                .progressDialog(loadDlg)
                .setLogging("CommentActivity", Log.DEBUG)
                .asJsonObject()
                .setCallback(new FutureCallback<JsonObject>() {
                    @Override
                    public void onCompleted(Exception e, JsonObject result) {
                        try {
                            if (e != null) {
                                throw e;
                            }
                            Log.e("CommentAct", "All JSON - Comment " + result);

                            dataArray = result.getAsJsonArray("data");
                            Gson gson = new Gson();
                            for (int i = 0; i < dataArray.size(); i++) {
                                commentBean = gson.fromJson(dataArray.get(i), CommentBean.class);
                                commentBeanRowItems.add(commentBean);
                                Log.e("CommentAct", "FROMJSON(bean)toJSON -  Comment:" + gson.toJson(commentBean));
                            }
                            loadDlg.dismiss();
                            if (kind.equals("initialize")) {
                                populateComments();
                            } else if (kind.equals("update")) {
                                updateCommentAdapter();
                            }
                        } catch (Exception ex) {
                            Log.e("debuginformation", "exception: " + ex.toString());
                        }
                    }
                });
    }


    private void loadNewsData() {
        showLoadingDialog("News");

        //Download the news from server and set the adapter as well
        Ion.with(this)
                //This comment is for changing the user MK after implementing Login activity
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cnewsposts/" + newsMK + "?registereduser_mk=37")
//                .load("http://14.199.123.48:689/fyp/laravel/public/cnewsposts?recordCount=30&registereduser_mk=37&sort=-proximity&currentLat=" + currentLatitude +
//                        "&currentLong=" + currentLongitude)
                .progressDialog(loadDlg)
                .setLogging("CommentActivity", Log.DEBUG)
                .asJsonObject()
                .setCallback(new FutureCallback<JsonObject>() {
                    @Override
                    public void onCompleted(Exception e, JsonObject result) {
                        try {
                            if (e != null) {
                                throw e;
                            }
                            Log.e("CommentAct", "All JSON " + result);

                            dataArray = result.getAsJsonArray("data");
                            Gson gson = new Gson();
                            for (int i = 0; i < dataArray.size(); i++) {
                                newsBean = gson.fromJson(dataArray.get(i), NewsBean.class);
                                Log.e("CommentAct", "FROMJSON(bean)toJSON:" + gson.toJson(newsBean));
                            }
                            loadDlg.dismiss();
                            populateNewsInfo();
                            loadComments("initialize");
                        } catch (Exception ex) {
                            Log.e("debuginformation", "exception: " + ex.toString());
                        }
                    }
                });
    }

    private void showLoadingDialog(String kind) {
        if (kind.equals("News")) {
            loadDlg = new ProgressDialog(this, AlertDialog.THEME_DEVICE_DEFAULT_DARK);
            loadDlg.setTitle("Loading the " + kind);
            loadDlg.setMessage("Preparing the" + kind);
            loadDlg.setIndeterminate(true);
            loadDlg.setProgressStyle(ProgressDialog.STYLE_SPINNER);
            loadDlg.show();
            Window window = loadDlg.getWindow();
            window.setLayout(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT);
        } else if (kind.equals("Comments")) {
            loadDlg = new ProgressDialog(this, AlertDialog.THEME_DEVICE_DEFAULT_DARK);
            loadDlg.setTitle("Loading the " + kind);
            loadDlg.setMessage("Preparing the" + kind);
            loadDlg.setIndeterminate(true);
            loadDlg.setProgressStyle(ProgressDialog.STYLE_SPINNER);
            loadDlg.show();
            Window window = loadDlg.getWindow();
            window.setLayout(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT);
        }

    }

    private void populateNewsInfo() {
        NewsContentLayout.removeAllViews();
        ArrayList<String> photosPath = new ArrayList<String>();
        ArrayList<String> videosPath = new ArrayList<String>();

        loadPic(userIcon, "profilePic", newsBean.profilepic);
        userName.setText(newsBean.username);
        postedTime.setText(newsBean.postedat);
        newsTitle.setText(newsBean.newstitle);
        favouriteCount.setText(newsBean.favourited);

        //If no any photos or videos in that news, then showing the "Image not available image"
        if (newsBean.photos.size() == 0 && newsBean.videos.size() == 0) {
            //Prepare the ImageView for loading the photos
            ImageView imgForMedia = prepareImageView();
            NewsContentLayout.addView(imgForMedia);
            //Download the error photo into the ImageView of the news with empty medias
            loadErrorPhoto(imgForMedia);
        }

        for (int b = 0; b < newsBean.photos.size(); b++) {
            //Add the photos' path into array list
            photosPath.add(newsBean.photos.get(b).jpg);

            //Prepare the ImageView for loading the photos
            ImageView imgForMedia = prepareImageView();
            NewsContentLayout.addView(imgForMedia);
            //Start loading the photo into the image view
            loadMediaPhotos(imgForMedia, photosPath.get(b));

            Log.e("NewsListFragmentAdapter", "photos path for each row" + photosPath.get(b));
        }

        for (int c = 0; c < newsBean.videos.size(); c++) {
            //Add the videos' path into array list
            videosPath.add(newsBean.videos.get(c).youtube);

            //Prepare the WebView for loading the Videos
            WebView NewsVideos = new WebView(this);
            LinearLayout.LayoutParams webViewParams = new LinearLayout.LayoutParams(
                    680, 500);
            NewsContentLayout.addView(NewsVideos, webViewParams);

            //WebView's setting
            NewsVideos.getSettings().setJavaScriptEnabled(true);
            NewsVideos.setWebChromeClient(new WebChromeClient() {
                public void onProgressChanged(WebView view, int progress) {
                    // Activities and WebViews measure progress with different scales.
                    // The progress meter will automatically disappear when we reach 100%
                    setProgress(progress * 1000);
                }
            });
            NewsVideos.setWebViewClient(new WebViewClient() {
                public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
                    Toast.makeText(getApplicationContext(), "Oh no! " + description, Toast.LENGTH_SHORT).show();
                }
            });

            //Start Loading Video into the WebView
            NewsVideos.loadUrl("https://www.youtube.com/embed/" + videosPath.get(c) + "?autohide=1&fs=0&playsinline=0&loop=1&playlist=" + videosPath.get(c) );

            Log.e("NewsListFragmentAdapter", "videos path for each row" + videosPath.get(c));
        }
    }

    public void loadPic(ImageView imageView, String kind, String url) {
        switch (kind) {
            case "profilePic":
                Ion.with(imageView)
                  .error(R.drawable.crazy)
                  .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/profile_photos/" + url);
//                .load("http://14.199.123.48:689/fyp/laravel/public/profile_photos/" + url);
                break;
            case "MediasPic":
                break;
        }
    }

    private void setTheWebView() {
//        final Activity activity = this;
//
//        NewsContent.getSettings().setJavaScriptEnabled(true);
//        NewsContent.setWebChromeClient(new WebChromeClient() {
//            public void onProgressChanged(WebView view, int progress) {
//                // Activities and WebViews measure progress with different scales.
//                // The progress meter will automatically disappear when we reach 100%
//                activity.setProgress(progress * 1000);
//            }
//        });
//        NewsContent.setWebViewClient(new WebViewClient() {
//            public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
//                Toast.makeText(activity, "Oh no! " + description, Toast.LENGTH_SHORT).show();
//            }
//        });
//        NewsContent.loadUrl("https://www.youtube.com/embed/L8u4QXiRyDk");
    }

    private void populateComments() {
        //Populate the comments by setting the Comment Adapter
        CommentAdapter commentAdapter = new CommentAdapter(this, commentBeanRowItems);
        commentsListView.setAdapter(commentAdapter);
        commentsListView.setEmptyView(empty);
    }

    /**
     * *************************For Option Setting**********************************
     */
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_comment, menu);
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

    /***********************End of Retrieving Current Location*************************/

    private ImageView prepareImageView() {
        ImageView imgForMedia = new ImageView(this);
        imgForMedia.setMinimumHeight(500);
        imgForMedia.setMinimumWidth(680);
        return imgForMedia;
    }

    public void loadErrorPhoto(ImageView imageView) {
        Ion.with(imageView)
                .smartSize(true)
                .load("http://img2.wikia.nocookie.net/__cb20140118173446/wiisportsresortwalkthrough/images/6/60/No_Image_Available.png");
    }

    public void loadMediaPhotos(ImageView imageView, String url) {
        Ion.with(imageView)
                .centerCrop()
                .fitXY()
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/newspost_photos/" + url);
//                .load("http://14.199.123.48:689/fyp/laravel/public/newspost_photos/" + url);
    }
}
