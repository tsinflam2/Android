package com.gpnlimited.gpn;

import android.app.Activity;
import android.app.Dialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Message;
import android.preference.PreferenceManager;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.webkit.ValueCallback;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.Toast;
import java.net.URI;


public class UploadYoutubeActivity extends ActionBarActivity {
    WebView uploadYoutubeWebView;
    Button test;
    SharedPreferences sharedpreferences;
    public static final String serIP = "";
    Button upload_youtube, cancel;
    EditText video_title, video_desc;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_upload_youtube);
        findView();
        setResources();
        setTheWebView();
        showDialog();
    }

    private void findView() {
        uploadYoutubeWebView = (WebView) findViewById(R.id.uploadYoutubeWebView);
    }

    private void setResources() {
        sharedpreferences = PreferenceManager.getDefaultSharedPreferences(this);
    }

    private void setTheWebView() {
        final Activity activity = this;

        uploadYoutubeWebView.getSettings().setJavaScriptEnabled(true);
        uploadYoutubeWebView.getSettings().setSupportMultipleWindows(true);


        uploadYoutubeWebView.loadUrl(sharedpreferences.getString(serIP, "NO IP") + "fyp/Youtube/upload_video.html");

        uploadYoutubeWebView.setWebViewClient(new WebViewClient() {
            public void onReceivedError(WebView view, int errorCode, String description, String failingUrl) {
                Toast.makeText(activity, "Oh No! Connection Error! " + description, Toast.LENGTH_SHORT).show();
            }

            @Override
            public boolean shouldOverrideUrlLoading(WebView view, String url) {
                view.loadUrl(url);
                return false;
            }
        });

        uploadYoutubeWebView.setWebChromeClient(new WebChromeClient() {

            public void openFileChooser( ValueCallback<Uri> uploadMsg, String acceptType ) {
                ValueCallback<Uri> mUploadMessage = uploadMsg;
                Intent i = new Intent(Intent.ACTION_GET_CONTENT);
                i.addCategory(Intent.CATEGORY_OPENABLE);
                i.setType("image/*");
                UploadYoutubeActivity.this.startActivityForResult( Intent.createChooser( i, "Choose a Video" ), RESULT_OK );
            }

            public void openFileChooser( ValueCallback<Uri> uploadMsg ) {
                openFileChooser( uploadMsg, "" );
            }


            public void openFileChooser(ValueCallback<Uri> uploadMsg, String acceptType, String capture){
                openFileChooser( uploadMsg, "" );
            }


            public void onProgressChanged(WebView view, int progress) {
                // Activities and WebViews measure progress with different scales.
                // The progress meter will automatically disappear when we reach 100%
                activity.setProgress(progress * 1000);
            }

            @Override
            public boolean onCreateWindow(WebView view, boolean isDialog, boolean isUserGesture, Message resultMsg) {
                WebView childView = new WebView(UploadYoutubeActivity.this);
                childView.getSettings().setJavaScriptEnabled(true);
                childView.setWebChromeClient(this);
                childView.setWebViewClient(new WebViewClient());
                childView.setLayoutParams(new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.MATCH_PARENT));
                UploadYoutubeActivity.this.addContentView(childView, new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.MATCH_PARENT));
                      //  .addView(childView);
                WebView.WebViewTransport transport = (WebView.WebViewTransport) resultMsg.obj;
                transport.setWebView(childView);
                resultMsg.sendToTarget();

                Log.d("CREATED NEW WINDOW", "onCreateWindow");   // never log

                return true;
                //return super.onCreateWindow(view, isDialog, isUserGesture, resultMsg);
            }

            @Override
            public void onCloseWindow(WebView window) {
                super.onCloseWindow(window);

                window.setVisibility(View.GONE);

                //((WebView) uploadYoutubeWebView.getParent()).removeView(uploadYoutubeWebView);
            }


        });






    }

    private void showDialog() {
        Dialog uploadYoutubeDialog = new Dialog(this);
        uploadYoutubeDialog.setContentView(R.layout.dialog_upload_youtube);
        uploadYoutubeDialog.setTitle("Please Input Video's Info: ");

        upload_youtube = (Button) uploadYoutubeDialog.findViewById(R.id.upload_youtube);
        cancel = (Button) uploadYoutubeDialog.findViewById(R.id.cancel);
        video_title = (EditText) uploadYoutubeDialog.findViewById(R.id.video_title);
        video_desc = (EditText) uploadYoutubeDialog.findViewById(R.id.video_desc);

        uploadYoutubeDialog.show();
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_upload_youtube, menu);
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
