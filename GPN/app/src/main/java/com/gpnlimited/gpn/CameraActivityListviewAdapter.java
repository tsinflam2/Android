package com.gpnlimited.gpn;

import android.app.Activity;
import android.content.Context;
import android.graphics.BitmapFactory;
import android.graphics.drawable.Drawable;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.Toast;

import java.io.InputStream;
import java.net.URL;
import java.util.List;

/**
 * Created by Johnny on 4/7/2015.
 */
public class CameraActivityListviewAdapter extends BaseAdapter {
    Context context;
    List<ThumbnailsBean> rowItem;

    public CameraActivityListviewAdapter(Context context, List rowItem) {
        this.context = context;
        this.rowItem = rowItem;
    }

    public void resetRowItem(List rowItem) {
        this.rowItem = rowItem;
    }

    @Override
    public int getCount() {
        return  rowItem.size();
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
            LayoutInflater mInflater = (LayoutInflater) context.getSystemService(Activity.LAYOUT_INFLATER_SERVICE);
            convertView = mInflater.inflate(R.layout.adapter_camera_listview_adapter, null);
        }
        ImageView thumbnail = (ImageView) convertView.findViewById(R.id.thumbnail);

        //If the rowItem is not null
        //Then check if the photo in rowItem is the noimage photo
        //If so, set noimage into imageview of listview, otherwise set the image from rowItem
        if (rowItem.size() == 0) {
//            if (rowItem.get(position).getThumb().sameAs(BitmapFactory.decodeResource(context.getResources(), R.drawable.ic_launcher))) {
//                Toast.makeText(context, "No Image is detected"  + rowItem.size(), Toast.LENGTH_LONG).show();
                thumbnail.setImageResource(R.drawable.noimage);
//            }
        }
        if (rowItem.size() >= 1) {
            thumbnail.setImageBitmap(rowItem.get(position).getThumb());
        }

//        thumbnail.setImageDrawable(loadImageFromURL("http://ffaasstt.swide.com/wp-content/uploads/Bruce-lee-workout-diet-routine-cover.jpg"));

        return convertView;
    }

}
