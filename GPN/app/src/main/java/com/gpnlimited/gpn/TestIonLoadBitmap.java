package com.gpnlimited.gpn;

import android.graphics.Bitmap;
import android.support.v7.app.ActionBarActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import com.koushikdutta.async.future.Future;
import com.koushikdutta.async.stream.InputStreamDataEmitter;
import com.koushikdutta.ion.Ion;
import com.koushikdutta.ion.builder.Builders;
import com.koushikdutta.ion.future.ResponseFuture;

import java.io.InputStream;
import java.util.concurrent.ExecutionException;


public class TestIonLoadBitmap extends ActionBarActivity {
    Bitmap bitmap;
    ImageView iv;
    Button btn;
    TextView tv;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_test_ion_load_bitmap);

        btn = (Button) findViewById(R.id.button);
        iv = (ImageView) findViewById(R.id.image);
        tv = (TextView) findViewById(R.id.textView3);

        btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    String abitmap = loadNewsIcons("http://14.199.123.48:689/fyp/laravel/public/cnewsposts");
                    tv.setText(abitmap);
                } catch (ExecutionException e) {
                    Log.e("debugTestIonLoad", "exception: " + e.toString());
                } catch (InterruptedException e) {
                    Log.e("debugTestIonLoad", "exception: " + e.toString());
                }
            }
        });

    }

    public String loadNewsIcons(String url) throws ExecutionException, InterruptedException {
        Class<? extends Builders.Any.M> abitjmap;

        abitjmap = Ion.with(this)
                .load(url)
                .setMultipartParameter("hh", "h")
                .setMultipartParameter("newstitle", "as")
                .setMultipartParameter("newsdescription", "")
                .getClass();
        abitjmap.getDeclaredMethods();

        return null;
    }
}