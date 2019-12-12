package com.gpnlimited.gpn;

import android.graphics.Bitmap;
import android.graphics.drawable.Drawable;
import android.widget.ImageView;

/**
 * Created by Johnny on 4/7/2015.
 */
public class ThumbnailsBean {
    Bitmap thumb;

    public ThumbnailsBean(Bitmap thumb) {
        this.thumb = thumb;
    }

    public void setThumb(Bitmap thumb) {
        this.thumb = thumb;
    }

    public Bitmap getThumb() {
        return thumb;
    }
}
