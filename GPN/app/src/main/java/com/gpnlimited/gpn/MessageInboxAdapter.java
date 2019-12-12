package com.gpnlimited.gpn;

import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;
import android.preference.PreferenceManager;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.koushikdutta.ion.Ion;

import java.util.List;

public class MessageInboxAdapter extends BaseAdapter {
    Context context;
    List<MessageInboxBean> rowItem;
    SharedPreferences sharedpreferences;
    public static final String serIP = "";

    public MessageInboxAdapter(Context context, List<MessageInboxBean> rowItem) {
        this.context = context;
        this.rowItem = rowItem;
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
            convertView = mInflater.inflate(R.layout.adapter_pm_inbox_item, null);
        }
        sharedpreferences = PreferenceManager.getDefaultSharedPreferences(context);

        ImageView imgIcon = (ImageView) convertView.findViewById(R.id.InboxUserIcon);
        TextView userName = (TextView) convertView.findViewById(R.id.InboxUsername);
        TextView newestmsg = (TextView) convertView.findViewById(R.id.InboxMsg);

        MessageInboxBean row_pos = rowItem.get(position);
       // imgIcon.setImageResource(row_pos.getIcon());

        loadProfilePic(imgIcon, row_pos.getPROFILEPIC());
        userName.setText(row_pos.getUSERNAME());
        newestmsg.setText(row_pos.getLastmsg());
        return convertView;
    }

    public void loadProfilePic(ImageView imageView, String url) {
        Ion.with(imageView)
                .error(R.drawable.crazy)
                .load(sharedpreferences.getString(serIP, "NO IP") + "fyp/laravel/public/profile_photos/" + url);
//                .load("http://14.199.123.48:689/fyp/laravel/public/profile_photos/" + url);
    }
}
