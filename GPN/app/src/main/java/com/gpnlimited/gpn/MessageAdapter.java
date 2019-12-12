package com.gpnlimited.gpn;

import android.app.Activity;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.AsyncTask;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;


import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.koushikdutta.async.future.FutureCallback;
import com.koushikdutta.ion.Ion;

import java.io.InputStream;
import java.net.URL;
import java.util.List;

public class MessageAdapter extends BaseAdapter implements Cloneable {
    Context context;
    List<MessageBean> rowItem;
    private int mymk;
    MessengerActivity activityInstance;
    ImageView imageView;

    public MessageAdapter(Context context, List<MessageBean> rowItem, MessengerActivity activityInstance) {
        this.context = context;
        this.rowItem = rowItem;
        this.activityInstance = activityInstance;
    }


    public int getMymk() {
        return mymk;
    }

    public void setMymk(int mymk) {
        this.mymk = mymk;
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
            MessageBean row_pos = rowItem.get(position);
            Log.d("LOLLLLLLLLLLLL", "MK " + row_pos.getNEWSPOST_MK());
            if (row_pos.getREGISTEREDUSER_MK_FROM() == mymk && row_pos.getNEWSPOST_MK() == 0) {
                // Sent
                Log.d("ROW", "USING sent");
                convertView = mInflater.inflate(R.layout.adapter_pm_sent_item, null);
                TextView sentMsg = (TextView) convertView.findViewById(R.id.SentMsg);
                sentMsg.setText(row_pos.getPMCONTENT());
            } else if (row_pos.getREGISTEREDUSER_MK_FROM() == mymk && row_pos.getNEWSPOST_MK() != 0) {
                // Sent share
                try {
                    convertView = mInflater.inflate(R.layout.adapter_pm_sharesent_item, null);
                    TextView shareSentTV = (TextView) convertView.findViewById(R.id.ShareSentMsg);
                    shareSentTV.setText(row_pos.getPMCONTENT());
                    imageView = (ImageView) convertView.findViewById(R.id.ShareSentMap);
                    Bitmap bitmap = null;
                    String i = "http://maps.googleapis.com/maps/api/staticmap?zoom=13&size=" + 300 + "x" + 100 + "&maptype=roadmap&markers=color:blue%7Clabel:%7C" + row_pos.getLATITUDE() + "," + row_pos.getLONGITUDE();
                    MapAsyncTask mTask = new MapAsyncTask();
                    mTask.inst = (MessageAdapter) (this.clone());
                    mTask.execute(i);
                } catch (Exception e) {
                    Log.d("URL", "error ");
                    e.printStackTrace();
                }
            } else if (row_pos.getNEWSPOST_MK() != 0) {
                // Received share
                try {
                    convertView = mInflater.inflate(R.layout.adapter_pm_sharereceived_item, null);
                    TextView shareSentTV = (TextView) convertView.findViewById(R.id.ShareReceivedMsg);
                    shareSentTV.setText(row_pos.getPMCONTENT());
                    imageView = (ImageView) convertView.findViewById(R.id.ShareReceivedMap);
                    Bitmap bitmap = null;
                    String i = "http://maps.googleapis.com/maps/api/staticmap?zoom=13&size=" + 300 + "x" + 100 + "&maptype=roadmap&markers=color:blue%7Clabel:%7C" + row_pos.getLATITUDE() + "," + row_pos.getLONGITUDE();
                    MapAsyncTask mTask = new MapAsyncTask();
                    mTask.inst = (MessageAdapter) (this.clone());
                    mTask.execute(i);
                } catch (Exception e) {
                    Log.d("URL", "error ");
                    e.printStackTrace();
                }
            } else {
                // Received
                Log.d("ROW", "USING received");
                convertView = mInflater.inflate(R.layout.adapter_pm_receive_item, null);
                ImageView imgIcon = (ImageView) convertView.findViewById(R.id.ReceivedUserIcon);
                TextView receivedMsg = (TextView) convertView.findViewById(R.id.ReceivedMsg);
                receivedMsg.setText(row_pos.getPMCONTENT());
            }
        }
        return convertView;
    }


}

class MapAsyncTask extends AsyncTask<String, Void, String> {

    protected MessageAdapter inst;
    private Bitmap bitmap;

    @Override
    protected void onPreExecute() {
        // TODO Auto-generated method stub
        super.onPreExecute();
    }

    @Override
    protected String doInBackground(String... params) {
        // TODO Auto-generated method stub
        /**
         * Do network related stuff
         * return string response.
         */
        try {
            bitmap = BitmapFactory.decodeStream((InputStream) new URL(params[0]).getContent());
            Log.d("MAP LOADING", "X");
        } catch (Exception e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    protected void onPostExecute(String result) {
        // TODO Auto-generated method stub
        /**
         * update ui thread and remove dialog
         */
        super.onPostExecute(result);
        inst.imageView.setImageBitmap(bitmap);
        //inst.notifyDataSetChanged();
    }
}