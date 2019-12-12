package com.gpnlimited.gpn;

import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.preference.PreferenceManager;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.webkit.WebChromeClient;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.devsmart.android.ui.HorizontalListView;
import com.google.gson.JsonObject;
import com.koushikdutta.async.future.FutureCallback;
import com.koushikdutta.ion.Ion;

import java.util.ArrayList;

/**
 * Created by Johnny on 3/29/2015.
 */
public class NewsListFragmentAdapter extends BaseAdapter {
    Context context;
    ArrayList<NewsBean> rowItem;
    Activity activity;
    HorizontalListView horizontalListView;
    ImageView userIcon;
    TextView txtUserName;
    TextView txtNewsTitle;
    TextView txtPostedDateOrTime;
    TextView txtCommentCount;
    TextView report, share;
    LinearLayout NewsContentLayout;
    SharedPreferences sharedpreferences;
    NewsBean row_pos;
    public static final String serIP = "";

    NewsListFragmentAdapter(Activity activity, Context context, ArrayList<NewsBean> rowItem) {
        this.context = context;
        this.rowItem = rowItem;
        this.activity = activity;
    }

    @Override
    public int getCount() {
        return rowItem.size();
    }

    @Override
    public Object getItem(int position) {
        return rowItem.get(position);
    }

    @Override
    public long getItemId(int position) {
        return rowItem.indexOf(getItem(position));
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        if (convertView == null) {
            LayoutInflater mInflater = (LayoutInflater) context
                    .getSystemService(Activity.LAYOUT_INFLATER_SERVICE);
            convertView = mInflater.inflate(R.layout.adapter_news_list_item, null);
        }
        sharedpreferences = PreferenceManager.getDefaultSharedPreferences(context);

        //Set Background color of the list view to be white
        convertView.setBackgroundColor(Color.WHITE);
        findView(convertView);
        //FAIL to use HotizontalListView, use ScrollView Instead
//        setHorizontalListView(position);
        setTextViewListener(position);

        populate(position);

        return convertView;
    }

    private void findView(View convertView) {
//        horizontalListView = (HorizontalListView) convertView.findViewById(R.id.NewsContentWrap);
        NewsContentLayout = (LinearLayout) convertView.findViewById(R.id.NewsContentLayout);
        userIcon = (ImageView) convertView.findViewById(R.id.UserIcon);
        txtUserName = (TextView) convertView.findViewById(R.id.UserName);
        txtNewsTitle = (TextView) convertView.findViewById(R.id.NewsTitle);
        txtPostedDateOrTime = (TextView) convertView.findViewById(R.id.PostedTime);
        txtCommentCount = (TextView) convertView.findViewById(R.id.commentCount);
        report = (TextView) convertView.findViewById(R.id.report);
        share = (TextView) convertView.findViewById(R.id.share);

    }

    private void populate(int position) {
        NewsContentLayout.removeAllViews();
        ArrayList<String> photosPath = new ArrayList<String>();
        ArrayList<String> videosPath = new ArrayList<String>();
        row_pos = rowItem.get(position);
        txtUserName.setText(row_pos.username);
        txtNewsTitle.setText("News Title: " + row_pos.newstitle);
        txtPostedDateOrTime.setText(row_pos.postedat);
        txtCommentCount.setText(row_pos.commentsCount + " Comment(s)");
        loadProfilePic(userIcon, row_pos.profilepic);

        //If no any photos or videos in that news, then showing the "Image not available image"
        if (row_pos.photos.size() == 0 && row_pos.videos.size() == 0) {
            //Prepare the ImageView for loading the photos
            ImageView imgForMedia = prepareImageView();
            NewsContentLayout.addView(imgForMedia);
            //Download the error photo into the ImageView of the news with empty medias
            loadErrorPhoto(imgForMedia);
        }

        share.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                JsonObject json = new JsonObject();
                json.addProperty("newsmk", row_pos.mk);
                json.addProperty("message", "Check this news out:");

                Ion.with(context)
                        .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cgeneralusers/" + 37 + "/pms/" + 73)
                        .setJsonObjectBody(json)
                        .asJsonObject()
                        .setCallback(new FutureCallback<JsonObject>() {
                            @Override
                            public void onCompleted(Exception e, JsonObject result) {
                                if (e != null) {
                                    Toast.makeText(context, "Error loading tweets", Toast.LENGTH_LONG).show();
                                    return;
                                } else {
                                    final Dialog shareMSGDialog = new Dialog(activity);
                                    shareMSGDialog.setContentView(R.layout.dialog_share_news);
                                    shareMSGDialog.setTitle("Share message!: ");

                                    Button ok = (Button) shareMSGDialog.findViewById(R.id.ok);
                                    ok.setOnClickListener(new View.OnClickListener() {
                                        @Override
                                        public void onClick(View v) {
                                            shareMSGDialog.dismiss();
                                        }
                                    });

                                    shareMSGDialog.show();
                                }
                            }
                        });
            }
        });

        for (int b = 0; b < row_pos.photos.size(); b++) {
            //Add the photos' path into array list
            photosPath.add(row_pos.photos.get(b).jpg);

            //Prepare the ImageView for loading the photos
            ImageView imgForMedia = prepareImageView();
            NewsContentLayout.addView(imgForMedia);
            //Start loading the photo into the image view
            loadMediaPhotos(imgForMedia, photosPath.get(b));

            Log.e("NewsListFragmentAdapter", "photos path for each row" + photosPath.get(b));
        }

        for (int c = 0; c < row_pos.videos.size(); c++) {
            //Add the videos' path into array list
            videosPath.add(row_pos.videos.get(c).youtube);

            //Prepare the WebView for loading the Videos
            WebView NewsVideos = new WebView(activity);
            LinearLayout.LayoutParams webViewParams = new LinearLayout.LayoutParams(
                    680, 500);
            NewsContentLayout.addView(NewsVideos, webViewParams);

            //WebView's setting
            NewsVideos.getSettings().setJavaScriptEnabled(true);
            NewsVideos.setWebChromeClient(new WebChromeClient() {
                public void onProgressChanged(WebView view, int progress) {
                    // Activities and WebViews measure progress with different scales.
                    // The progress meter will automatically disappear when we reach 100%
                    activity.setProgress(progress * 1000);
                }
            });
            NewsVideos.setWebViewClient(new WebViewClient() {
                public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
                    Toast.makeText(activity, "Oh no! " + description, Toast.LENGTH_SHORT).show();
                }
            });

            //Start Loading Video into the WebView
            NewsVideos.loadUrl("https://www.youtube.com/embed/" + videosPath.get(c) + "?autohide=1&fs=0&playsinline=0&loop=1&playlist=" + videosPath.get(c) );

            Log.e("NewsListFragmentAdapter", "videos path for each row" + videosPath.get(c));
        }


    }

    private void setTextViewListener(final int position) {
        report.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                report(position);
            }
        });
    }

    private ImageView prepareImageView() {
        ImageView imgForMedia = new ImageView(activity);
        imgForMedia.setMinimumHeight(500);
        imgForMedia.setMinimumWidth(680);
        return imgForMedia;
    }

    public void loadProfilePic(ImageView imageView, String url) {
        Ion.with(imageView)
                .error(R.drawable.crazy)
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/profile_photos/" + url);
//                .load("http://14.199.123.48:689/fyp/laravel/public/profile_photos/" + url);
    }

    public void loadMediaPhotos(ImageView imageView, String url) {
        Ion.with(imageView)
                .centerCrop()
                .fitXY()
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/newspost_photos/" + url);
//                .load("http://14.199.123.48:689/fyp/laravel/public/newspost_photos/" + url);
    }

    public void loadErrorPhoto(ImageView imageView) {
        Ion.with(imageView)
                .smartSize(true)
                .load("http://img2.wikia.nocookie.net/__cb20140118173446/wiisportsresortwalkthrough/images/6/60/No_Image_Available.png");
    }

    private void report(int position) {
        row_pos = rowItem.get(position);
        Ion.with(context)
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cnewsposts/report/" + row_pos.mk)
                        // embedding twitter api key and secret is a bad idea, but this isn't a real twitter app :)
                .setBodyParameter("registereduser_mk", "37")
                .setBodyParameter("explanation", "")
                .asJsonObject()
                .setCallback(new FutureCallback<JsonObject>() {
                    @Override
                    public void onCompleted(Exception e, JsonObject result) {
                        if (e != null) {
                            Toast.makeText(context, "Error loading tweets", Toast.LENGTH_LONG).show();
                            return;
                        } else {
                            final Dialog reportDialog = new Dialog(activity);
                            reportDialog.setContentView(R.layout.dialog_report);
                            reportDialog.setTitle("Report News: ");

                            Button ok = (Button) reportDialog.findViewById(R.id.ok);
                            ok.setOnClickListener(new View.OnClickListener() {
                                @Override
                                public void onClick(View v) {
                                    reportDialog.dismiss();
                                }
                            });

                            reportDialog.show();
                        }
                    }
                });
    }
}
