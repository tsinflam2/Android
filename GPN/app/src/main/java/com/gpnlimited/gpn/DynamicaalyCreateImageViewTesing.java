package com.gpnlimited.gpn;

import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RadioGroup;
import android.widget.RelativeLayout;

import com.koushikdutta.ion.Ion;


public class DynamicaalyCreateImageViewTesing extends ActionBarActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_dynamicaaly_create_image_view_tesing);

        RelativeLayout rl = (RelativeLayout) findViewById(R.id.RelativeLayout);
        ImageView image = new ImageView(DynamicaalyCreateImageViewTesing.this);
        loadPhoto(image);
//        image.setBackgroundResource(R.drawable.crazy);
        rl.addView(image);
//        ImageView myImageView = new ImageView(this);
//        RelativeLayout rl = new RelativeLayout(this);
//        rl.setLayoutParams(new RadioGroup.LayoutParams(
//                ViewGroup.LayoutParams.MATCH_PARENT,
//                ViewGroup.LayoutParams.MATCH_PARENT
//        ));
//        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(300, 300);
//        params.topMargin = 30;
//        params.leftMargin = 30;
//        myImageView.setLayoutParams(params);
//        myImageView.setImageResource(R.drawable.crazy);
////        loadPhoto(myImageView);
//        rl.addView(myImageView);

    }

    public void loadPhoto(ImageView imageView) {
        Ion.with(imageView)
                .load("http://img2.wikia.nocookie.net/__cb20140118173446/wiisportsresortwalkthrough/images/6/60/No_Image_Available.png");
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_dynamicaaly_create_image_view_tesing, menu);
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
