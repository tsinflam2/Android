package com.gpnlimited.gpn;

import android.app.AlertDialog;
import android.app.Dialog;
import android.content.Context;
import android.content.CursorLoader;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.media.ThumbnailUtils;
import android.net.Uri;
import android.os.Environment;
import android.preference.PreferenceManager;
import android.provider.MediaStore;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.Gravity;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import java.io.File;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;

import com.devsmart.android.ui.HorizontalListView;
import com.koushikdutta.async.future.Future;
import com.koushikdutta.async.future.FutureCallback;
import com.koushikdutta.async.http.body.FilePart;
import com.koushikdutta.async.http.body.Part;
import com.koushikdutta.ion.Ion;
import com.koushikdutta.ion.ProgressCallback;


public class CameraActivityListview extends ActionBarActivity {
    CameraActivityListviewAdapter CALA;
    private List<ThumbnailsBean> thumbnailsBeansRowItems;
    private List<FullSizePhotosBean> FSPhotosRowItems;
    private List<VideosBean> videosBeansRowItems;
    ListView listView;
    Button addMore, post;
    String[] actions;
    static final int REQUEST_TAKE_PHOTO = 1;
    static final int REQUEST_VIDEO_CAPTURE = 2;
    static final int REQUEST_VIDEO_UPLOAD= 3;
    PackageManager pm;
    ImageView retrievedPhoto;
    HorizontalListView horizontalListView;
    String mCurrentPhotoPath;
    EditText etNewsTitle, etLatitude, etLongitude;
    String etInputtedNewsTitle, etInputtedLat, etInputtedLong;
    AlertDialog alertDialog;
    String currentLat, currentLong;
    Button upload, closeDialog;
    TextView uploadCount;
    ProgressBar progressBar;
    Future<File> uploading;
    ArrayList<Part> allPhotos;
    FilePart photoFilePart;
    int haveVideoForUpload = 0;
    HashMap<String, String> youtubeIndexHM;
    SharedPreferences sharedpreferences;
    public static final String serIP = "";
    private Button confirm_youtubeid;
    private EditText youtubeIDET;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_camera_activity_listview);


        findView();
        setResources();
        setButtons();
        setHorizontalListView();
    }

    private void findView() {
        horizontalListView = (HorizontalListView) findViewById(R.id.listview);
//         listView = (ListView) findViewById(R.id.ThumbnailsListView);
        addMore = (Button) findViewById(R.id.AddMore);
        post = (Button) findViewById(R.id.Post);
        retrievedPhoto = (ImageView) findViewById(R.id.RetrievedPhoto);
    }

    private void setResources() {
        actions = getResources().getStringArray(R.array.action_array_for_capturing);
        thumbnailsBeansRowItems = new ArrayList<ThumbnailsBean>();
        videosBeansRowItems = new ArrayList<VideosBean>();
        FSPhotosRowItems = new ArrayList<FullSizePhotosBean>();
        currentLat = NewsActivity.currentLatitude;
        currentLong = NewsActivity.currentLongitude;
        allPhotos = new ArrayList();
        youtubeIndexHM = new HashMap<String, String>();
        sharedpreferences = PreferenceManager.getDefaultSharedPreferences(this);
//        sharedpreferences = getSharedPreferences(MyPREFERENCES, Context.MODE_PRIVATE);
    }

    private void setHorizontalListView() {
        CALA = new CameraActivityListviewAdapter(getApplicationContext(), thumbnailsBeansRowItems);
        horizontalListView.setAdapter(CALA);
        horizontalListView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                retrievedPhoto.setImageBitmap(FSPhotosRowItems.get(position).getFSPhoto());
            }
        });
    }

    private void setButtons() {
        //For "Post(Submit)" Button
        post.setEnabled(false);

        post.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                AlertDialog.Builder alertDialogBuilder = new AlertDialog.Builder(CameraActivityListview.this);

                LinearLayout layout = new LinearLayout(CameraActivityListview.this);
                LinearLayout.LayoutParams parms = new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT);
                layout.setOrientation(LinearLayout.VERTICAL);
                layout.setLayoutParams(parms);

                layout.setGravity(Gravity.CLIP_VERTICAL);
                layout.setPadding(2, 2, 2, 2);

                //Alert Dialog Title
                TextView tv = new TextView(CameraActivityListview.this);
                tv.setText("Create a News Post: ");
                tv.setPadding(40, 40, 40, 40);
                tv.setGravity(Gravity.CENTER);
                tv.setTextSize(20);

                /******************Begin of News Title(TextView and EditText)*********************/
                //Text View of News Title
                TextView tvNewsTitle = new TextView(CameraActivityListview.this);
                tvNewsTitle.setText("News Title: ");

                //Edit Text of News Title
                etNewsTitle = new EditText(CameraActivityListview.this);
                //When the user input something, the "Post!" button will be enabled
                etNewsTitle.addTextChangedListener(new TextWatcher() {
                    @Override
                    public void beforeTextChanged(CharSequence s, int start, int count, int after) {

                    }

                    @Override
                    public void onTextChanged(CharSequence s, int start, int before, int count) {

                    }

                    @Override
                    public void afterTextChanged(Editable s) {
                        //when text changed, the text will be automatically copy to a string variable
                        etInputtedNewsTitle = etNewsTitle.getText().toString();

                        if (!(etNewsTitle.getText().toString().isEmpty()) && !(etLatitude.getText().toString().isEmpty())
                                && !(etLongitude.getText().toString().isEmpty())) {
                            alertDialog.getButton(AlertDialog.BUTTON_POSITIVE).setEnabled(true);
                        } else {
                            alertDialog.getButton(AlertDialog.BUTTON_POSITIVE).setEnabled(false);
                        }
                    }
                });

                /******************End of News Title(TextView and EditText)*********************/

                /******************Begin of Latitude(TextView and EditText)*********************/
                //Text View of Latitude
                TextView tvLatitude = new TextView(CameraActivityListview.this);
                tvLatitude.setText("Latitude: ");
                //Edit Text of Latitude
                etLatitude = new EditText(CameraActivityListview.this);
                //Take the current latitude by the device, into edit text
                etLatitude.setText(currentLat);
                //When the user input something, the "Post!" button will be enabled
                etLatitude.addTextChangedListener(new TextWatcher() {
                    @Override
                    public void beforeTextChanged(CharSequence s, int start, int count, int after) {

                    }

                    @Override
                    public void onTextChanged(CharSequence s, int start, int before, int count) {

                    }

                    @Override
                    public void afterTextChanged(Editable s) {
                        //when text changed, the text will be automatically copy to a string variable
                        etInputtedLat = etLatitude.getText().toString();
                        Toast.makeText(getApplicationContext(), "etInputtedLat: " + etInputtedLat, Toast.LENGTH_LONG).show();
                        if (!(etLatitude.getText().toString().isEmpty()) && !(etNewsTitle.getText().toString().isEmpty())
                                && !(etLongitude.getText().toString().isEmpty())) {
                            alertDialog.getButton(AlertDialog.BUTTON_POSITIVE).setEnabled(true);
                        } else {
                            alertDialog.getButton(AlertDialog.BUTTON_POSITIVE).setEnabled(false);
                        }
                    }
                });
                /******************End of Latitude(TextView and EditText)*********************/

                /******************Begin of Longitude(TextView and EditText)*********************/
                //Text View of Latitude
                TextView tvLongitude = new TextView(CameraActivityListview.this);
                tvLongitude.setText("Longitude: ");
                //Edit Text of Latitude
                etLongitude = new EditText(CameraActivityListview.this);
                //Take the current latitude by the device, into edit text
                etLongitude.setText(currentLong);
                //When the user input something, the "Post!" button will be enabled
                etLongitude.addTextChangedListener(new TextWatcher() {
                    @Override
                    public void beforeTextChanged(CharSequence s, int start, int count, int after) {

                    }

                    @Override
                    public void onTextChanged(CharSequence s, int start, int before, int count) {

                    }

                    @Override
                    public void afterTextChanged(Editable s) {
                        //when text changed, the text will be automatically copy to a string variable
                        etInputtedLong = etLatitude.getText().toString();
                        Toast.makeText(getApplicationContext(), "etInputtedLat: " + etInputtedLong, Toast.LENGTH_LONG).show();
                        if (!(etLatitude.getText().toString().isEmpty()) && !(etNewsTitle.getText().toString().isEmpty())
                                && !(etLongitude.getText().toString().isEmpty())) {
                            alertDialog.getButton(AlertDialog.BUTTON_POSITIVE).setEnabled(true);
                        } else {
                            alertDialog.getButton(AlertDialog.BUTTON_POSITIVE).setEnabled(false);
                        }
                    }
                });
                /******************End of Longitude(TextView and EditText)*********************/

                //Populate the dialog's content and layout
                LinearLayout.LayoutParams tv1Params = new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT);
                tv1Params.bottomMargin = 5;
                //For News Title (Text View and Edit Text)
                layout.addView(tvNewsTitle, tv1Params);
                layout.addView(etNewsTitle, new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT));
                //For Latitude (Text View and Edit Text)
                layout.addView(tvLatitude, tv1Params);
                layout.addView(etLatitude, new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT));
                //For Longitude (Text View and Edit Text)
                layout.addView(tvLongitude, tv1Params);
                layout.addView(etLongitude, new LinearLayout.LayoutParams(LinearLayout.LayoutParams.MATCH_PARENT, LinearLayout.LayoutParams.WRAP_CONTENT));

                //Dialog's setting
                alertDialogBuilder.setView(layout);
                alertDialogBuilder.setCustomTitle(tv);
                alertDialogBuilder.setIcon(R.drawable.abc_edit_text_material);

                //Save the initialized value of current latitude and longitude which are from location detection
                etInputtedLat = etLatitude.getText().toString();
                etInputtedLong = etLongitude.getText().toString();

                // Setting Negative "Cancel" Button
                alertDialogBuilder.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int whichButton) {
                        dialog.cancel();
                    }
                });

                /***************************** Setting Positive "Post!" Button****************************/
                alertDialogBuilder.setPositiveButton("Post!", new DialogInterface.OnClickListener() {
                    /********************Create a Media Upload Progress Bar Dialog*********************/
                    public void onClick(DialogInterface dialog, int which) {
//                        Toast.makeText(getApplicationContext(), "CurrentLat: "+ etInputtedLat, Toast.LENGTH_LONG).show();
//                        Toast.makeText(getApplicationContext(), "currentLong: "+ etInputtedLong, Toast.LENGTH_LONG).show();
//                        Toast.makeText(getApplicationContext(), "newsTitle: "+ etInputtedNewsTitle, Toast.LENGTH_LONG).show();

                        // custom dialog
                        final Dialog uploadMediasDialog = new Dialog(CameraActivityListview.this);
                        uploadMediasDialog.setContentView(R.layout.dialog_upload_medias);
                        uploadMediasDialog.setTitle("Uploading the Media(s) and Creating News Post");

                        upload = (Button) uploadMediasDialog.findViewById(R.id.upload);
                        closeDialog = (Button) uploadMediasDialog.findViewById(R.id.Close);
                        uploadCount = (TextView) uploadMediasDialog.findViewById(R.id.upload_count);
                        progressBar = (ProgressBar) uploadMediasDialog.findViewById(R.id.progress);

                        /***************************** Setting "Close Dialog" Button****************************/
                        closeDialog.setOnClickListener(new View.OnClickListener() {
                            @Override
                            public void onClick(View v) {
                                //Stop upload the files
                                resetUpload();
                                //Because the user close the dialog, all data(Photo or Video will be cleared)
                                thumbnailsBeansRowItems.clear();
                                FSPhotosRowItems.clear();
                                videosBeansRowItems.clear();
                                allPhotos.clear();
                                clearPreview();
                                updateHorizontalListViewAdapter();
                                uploadMediasDialog.dismiss();
                            }
                        });

                        /***************************** Setting "Upload" Button****************************/
                        upload.setOnClickListener(new View.OnClickListener() {
                            @Override
                            public void onClick(View v) {
                                if (uploading != null && !uploading.isCancelled()) {
                                    resetUpload();
                                    return;
                                }

                                //Put multi photos retrieved by user's device into an Array List for the purpose of "multi upload"
                                for (int s = 0; s < FSPhotosRowItems.size(); s++) {
                                    int sToBeConverted = s;
                                    sToBeConverted++;

                                    //做返個連續index比photo BY 減去有幾多個video(用冇PATH黎當係video)
                                    if (haveVideoForUpload != 0) {
                                        sToBeConverted  -= haveVideoForUpload;
                                    }

                                    String index = String.valueOf(sToBeConverted);
                                    Log.e("", "CameraActivityListView - The INDEX of imagex: " + index);
                                    //因為Video 所create既FullSizePhotosBean係冇Path的..要check有冇PATH,如果有先確定係相黎
                                    //然後比佢UPLOAD上server
                                    if ((FSPhotosRowItems.get(s).getFSPhotoPath()!=null) && !(FSPhotosRowItems.get(s).getFSPhotoPath().isEmpty())) {
                                        photoFilePart = new FilePart("image" + index, new File(FSPhotosRowItems.get(s).getFSPhotoPath()));
                                        allPhotos.add(photoFilePart);
//                                        Toast.makeText(getApplicationContext(), "All photos path: " + allPhotos.get(s).getFilename(), Toast.LENGTH_LONG).show();
                                    } else {
                                        //如果唔係photo, 就skip左哩一次looping唔做,唔加個FilePart入去allPhotos
                                        //如果係影片而又入到allPhotos會死,因為冇Photo Path
                                        haveVideoForUpload++;
                                        continue;
                                    }
                                }

                                //Loop at the videosBeansRowItems to take the path if the bean has and create corespondent key-value
                                for (int g=0; g<videosBeansRowItems.size(); g++) {
                                    int gToBeConverted = g;
                                    gToBeConverted++;
                                    String strG = String.valueOf(gToBeConverted++);
                                    if (!(videosBeansRowItems.get(g).getYoutubeID().isEmpty())) {

                                        youtubeIndexHM.put("youtube" + strG, videosBeansRowItems.get(g).getYoutubeID());
                                    }
                                }

                                //If the  hash table has not been filled full yet, then fill in the null key-value
                                if (youtubeIndexHM.size()!=5) {
                                    for (int h=videosBeansRowItems.size(); h<5; h++) {
                                        int hToBeConverted = h;
                                        hToBeConverted++;
                                        String strH = String.valueOf(hToBeConverted);
                                        youtubeIndexHM.put("youtube" + strH, null);
                                    }
                                }

                                //因為HashMap冇得就咁拎個KEY出黎, 要用個ArrayList存起個key即係youtubeX
                                ArrayList<String> youtubeIndexAL = new ArrayList<String>();
                                for (String youtubeIndex:youtubeIndexHM.keySet()) {
                                    youtubeIndexAL.add(youtubeIndex);
                                }

                                File echoedFile = getFileStreamPath("echo");

                                upload.setText("Cancel Upload");
                                // this is a 180MB zip file to test with

                                if (!(etInputtedLat.isEmpty()) && !(etInputtedLong.isEmpty()) && !(etInputtedNewsTitle.isEmpty())) {

                                    uploading = Ion.with(CameraActivityListview.this)
                                            .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/cnewsposts")
//                                            .load("http://14.199.123.48:689/fyp/laravel/public/cnewsposts")
                                                    // attach the percentage report to a progress bar.
                                                    // can also attach to a ProgressDialog with progressDialog.
                                            .uploadProgressBar(progressBar)
                                                    // callbacks on progress can happen on the UI thread
                                                    // via progressHandler. This is useful if you need to update a TextView.
                                                    // Updates to TextViews MUST happen on the UI thread.
                                            .uploadProgressHandler(new ProgressCallback() {
                                                @Override
                                                public void onProgress(long downloaded, long total) {
                                                    uploadCount.setText("" + downloaded + " / " + total);
                                                }
                                            })
                                                    // write to a file
                                            .setMultipartParameter("newstitle", etInputtedNewsTitle)
                                            .setMultipartParameter("newsdescription", "")
                                            .setMultipartParameter("latitude", etInputtedLat)
                                            .setMultipartParameter("longitude", etInputtedLong)
                                                    //This comment is for changing the user MK after implementing Login activity
                                            .setMultipartParameter("registereduser_mk", "37")
                                            .setMultipartParameter("newscategory_mk", "1")
                                            .setMultipartParameter(youtubeIndexAL.get(0), youtubeIndexHM.get(youtubeIndexAL.get(0)))
                                            .setMultipartParameter(youtubeIndexAL.get(1), youtubeIndexHM.get(youtubeIndexAL.get(1)))
                                            .setMultipartParameter(youtubeIndexAL.get(2), youtubeIndexHM.get(youtubeIndexAL.get(2)))
                                            .setMultipartParameter(youtubeIndexAL.get(3), youtubeIndexHM.get(youtubeIndexAL.get(3)))
                                            .setMultipartParameter(youtubeIndexAL.get(4), youtubeIndexHM.get(youtubeIndexAL.get(4)))
                                            .addMultipartParts(allPhotos)
                                            .write(echoedFile)
                                                    // run a callback on completion
                                            .setCallback(new FutureCallback<File>() {
                                                @Override
                                                public void onCompleted(Exception e, File result) {
                                                    resetUpload();
                                                    if (e != null) {
                                                        Toast.makeText(CameraActivityListview.this, "Error uploading file", Toast.LENGTH_LONG).show();
                                                        return;
                                                    }
                                                    Toast.makeText(CameraActivityListview.this, "File upload complete", Toast.LENGTH_LONG).show();

                                                    //After Upload the photos and create post, all data(Video+Photo) will be cleared
                                                    thumbnailsBeansRowItems.clear();
                                                    FSPhotosRowItems.clear();
                                                    videosBeansRowItems.clear();
                                                    allPhotos.clear();
                                                    clearPreview();
                                                    updateHorizontalListViewAdapter();
                                                    uploadMediasDialog.dismiss();

                                                }
                                            });
                                } else {
                                    Toast.makeText(CameraActivityListview.this, "File upload Error: Either One of News Info is Empty", Toast.LENGTH_LONG).show();
                                }

                            }
                        });
                        uploadMediasDialog.show();
                    }
                    /********************End of Create a Media Upload Progress Bar Dialog*********************/
                });

                //Create the dialog
                alertDialog = alertDialogBuilder.create();

                try {
                    alertDialog.show();
                } catch (Exception e) {
                    // WindowManager$BadTokenException will be caught and the app would
                    // not display the 'Force Close' message
                    e.printStackTrace();
                }

                //To disable the "Post" button when the user didn't input anything(The first time)
                alertDialog.getButton(AlertDialog.BUTTON_POSITIVE).setEnabled(false);
            }
        });


        //For "Add Media" Button
        addMore.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                AlertDialog.Builder dlg = new AlertDialog.Builder(CameraActivityListview.this);
                dlg.setTitle(R.string.choose_action);
                dlg.setIcon(android.R.drawable.ic_menu_camera);
                dlg.setItems(actions, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        switch (which) {
                            case 0:
                                dispatchTakePictureIntent();
                                break;

                            case 1:
                                break;

                            case 2:
                                dispatchTakeVideoIntent();
                                break;

                            case 3:
                                showInputYoutubeIDDlg();
                        }

                        Toast.makeText(getApplicationContext(), actions[which], Toast.LENGTH_LONG).show();
                    }
                });
                dlg.setNeutralButton("Cancel", null);
                dlg.show();
            }
        });
    }

    private void showInputYoutubeIDDlg() {
        final Dialog showInputYoutubeIDDlg = new Dialog(CameraActivityListview.this);
        showInputYoutubeIDDlg.setContentView(R.layout.dialog_insert_youtubeid);
        showInputYoutubeIDDlg.setTitle("Insert Video by YoutubeID: ");

        youtubeIDET = (EditText) showInputYoutubeIDDlg.findViewById(R.id.youtubeIDET);
        confirm_youtubeid = (Button) showInputYoutubeIDDlg.findViewById(R.id.confirm_youtubeid);

        confirm_youtubeid.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                VideosBean vb = new VideosBean(null, youtubeIDET.getText().toString());
                videosBeansRowItems.add(vb);
                //因為youtubeID冇樣, 用內置"影片"圖頂住先
                thumbnailsBeansRowItems.add(new ThumbnailsBean(BitmapFactory.decodeResource(getApplicationContext().getResources(),R.drawable.videoicon)));
                //因為影片是沒有FullSize Photo, 以預設的影片ICON作為Preview
                FullSizePhotosBean fspb = new FullSizePhotosBean(BitmapFactory.decodeResource(getApplicationContext().getResources(),R.drawable.videoicon));
                FSPhotosRowItems.add(fspb);
                updateHorizontalListViewAdapter();
                //Enable the "Post" button as the photo is retrieved
                post.setEnabled(true);
                showInputYoutubeIDDlg.dismiss();
                Toast.makeText(getApplicationContext(), youtubeIDET.getText().toString(), Toast.LENGTH_LONG).show();
            }
        });
        showInputYoutubeIDDlg.show();
    }

    //For sending an intent in order to take photo
    private void dispatchTakePictureIntent() {
        pm = this.getPackageManager();
        if (pm.hasSystemFeature(PackageManager.FEATURE_CAMERA_FRONT)) {
            Intent takePictureIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
            if (takePictureIntent.resolveActivity(getPackageManager()) != null) {
                // Create the File where the photo should go
                File photoFile = null;
                try {
                    photoFile = createImageFile();
                } catch (IOException ex) {
                    // Error occurred while creating the File
                    Log.e("", "CameraActivityListViewAdapter - Exception of dispatchTakePictureIntent: " + ex.toString());
                }
                // Continue only if the File was successfully created
                if (photoFile != null) {
                    takePictureIntent.putExtra(MediaStore.EXTRA_OUTPUT, Uri.fromFile(photoFile));
                    startActivityForResult(takePictureIntent, REQUEST_TAKE_PHOTO);
                }
            }
        }
    }

    //For sending an intent in order to take video
    private void dispatchTakeVideoIntent() {
        Intent takeVideoIntent = new Intent(MediaStore.ACTION_VIDEO_CAPTURE);
        pm = this.getPackageManager();
        if (pm.hasSystemFeature(PackageManager.FEATURE_CAMERA_FRONT)) {
            if (takeVideoIntent.resolveActivity(getPackageManager()) != null) {
                startActivityForResult(takeVideoIntent, REQUEST_VIDEO_CAPTURE);
            }
        }
    }

    private File createImageFile() throws IOException {
        // Create an image file name
        String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(new Date());
        String imageFileName = "ANDROID_JPEG_" + timeStamp + "_";
        File storageDir = Environment.getExternalStoragePublicDirectory(
                Environment.DIRECTORY_PICTURES);
        File image = File.createTempFile(
                imageFileName,  /* prefix */
                ".jpg",         /* suffix */
                storageDir      /* directory */
        );

        // Save a file: path for use with ACTION_VIEW intents
        mCurrentPhotoPath = image.getAbsolutePath();
        Log.e("", "CameraActivityListView FilePath:" + mCurrentPhotoPath);
        return image;
    }

    private void setPic() {
        // Get the dimensions of the View
        int targetW = retrievedPhoto.getWidth();
        int targetH = retrievedPhoto.getHeight();

        // Get the dimensions of the bitmap
        BitmapFactory.Options bmOptions = new BitmapFactory.Options();
        bmOptions.inJustDecodeBounds = true;
        BitmapFactory.decodeFile(mCurrentPhotoPath, bmOptions);
        int photoW = bmOptions.outWidth;
        int photoH = bmOptions.outHeight;

        // Determine how much to scale down the image
        int scaleFactor = Math.min(photoW / targetW, photoH / targetH);

        // Decode the image file into a Bitmap sized to fill the View
        bmOptions.inJustDecodeBounds = false;
        bmOptions.inSampleSize = scaleFactor;
        bmOptions.inPurgeable = true;

        Bitmap bitmap = BitmapFactory.decodeFile(mCurrentPhotoPath, bmOptions);
        FullSizePhotosBean FSPB = new FullSizePhotosBean(bitmap, mCurrentPhotoPath);
        FSPhotosRowItems.add(FSPB);

        /*Take the thumbnail and populate into row Items so that the thumbnails
         can be populated in to horizontal list view as well
         */
        ThumbnailsBean tb = new ThumbnailsBean(ThumbnailUtils.extractThumbnail(bitmap, 245, 245));
        thumbnailsBeansRowItems.add(tb);

        /*Set the full size photo into the image view
        which show the larger version of photo for the user to see
         */
        retrievedPhoto.setImageBitmap(bitmap);
    }

    private void resetUpload() {
        // cancel any pending upload
        uploading.cancel();
        uploading = null;

        // reset the ui
        upload.setText("Upload");
        uploadCount.setText(null);
        progressBar.setProgress(0);
    }

    /**
     * ***************************For Option Setting***********************************
     */
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_camera_activity_listview, menu);
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

    /**
     * ***************************End of Option Setting***********************************
     */

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        //For retrieved photo
        if (requestCode == REQUEST_TAKE_PHOTO && resultCode == RESULT_OK) {
            setPic();
            updateHorizontalListViewAdapter();
            //Enable the "Post" button as the photo is retrieved
            post.setEnabled(true);
        }

        //For retrieved video
        if (requestCode == REQUEST_VIDEO_CAPTURE && resultCode == RESULT_OK) {
            Uri videoUri = data.getData();
            String videoPath = getVideoRealPathFromURI(videoUri);
            Log.e("", "CameraActivityListView Debug - Retrieved video's path: " + videoPath);
            Intent intent = new Intent(this, UploadYoutubeActivity.class);
            startActivityForResult(intent, REQUEST_VIDEO_UPLOAD);

            VideosBean vb = new VideosBean(videoPath, "ulOb9gIGGd0");
            videosBeansRowItems.add(vb);
            //製作一個影片的Thumbnail並放入ThumbnailsBean，最後加入到thumbnailsBeansRowItems
            thumbnailsBeansRowItems.add(new ThumbnailsBean(getVideoThumbnail(videoPath, 245, 245, MediaStore.Images.Thumbnails.MINI_KIND)));
            //因為影片是沒有FullSize Photo, 以預設的影片ICON作為Preview
            FullSizePhotosBean fspb = new FullSizePhotosBean(BitmapFactory.decodeResource(getApplicationContext().getResources(),R.drawable.videoicon));
            FSPhotosRowItems.add(fspb);
            updateHorizontalListViewAdapter();
            //Enable the "Post" button as the photo is retrieved
            post.setEnabled(true);
        }
    }

    private void updateHorizontalListViewAdapter() {
        BaseAdapter adapter = (BaseAdapter) horizontalListView.getAdapter();
        adapter.notifyDataSetChanged();
    }

    //Used to clear the preview after closing dialog or after the post was sent
    private void clearPreview() {
        retrievedPhoto.setImageResource(android.R.color.transparent);
    }

    private String getVideoRealPathFromURI(Uri contentUri) {
        Cursor cursor = null;
        try {
            String[] proj = {MediaStore.Images.Media.DATA};
            CursorLoader loader = new CursorLoader(getApplicationContext(), contentUri, proj, null, null, null);
            cursor = loader.loadInBackground();
            int column_index = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
            cursor.moveToFirst();
            return cursor.getString(column_index);
        } finally {
            if (cursor != null) {
                cursor.close();
            }
        }
    }

    private Bitmap getVideoThumbnail(String videoPath, int width, int height, int kind) {
        Bitmap bitmap = null;
        // 获取视频的缩略图
        bitmap = ThumbnailUtils.createVideoThumbnail(videoPath, kind);
//        System.out.println("w"+bitmap.getWidth());
//        System.out.println("h"+bitmap.getHeight());
        bitmap = ThumbnailUtils.extractThumbnail(bitmap, width, height,
                ThumbnailUtils.OPTIONS_RECYCLE_INPUT);
        return bitmap;
    }

}