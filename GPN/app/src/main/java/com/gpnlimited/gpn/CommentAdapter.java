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

import java.util.ArrayList;
import java.util.List;

/**
 * Created by Johnny on 4/9/2015.
 */
public class CommentAdapter extends BaseAdapter {
    Context context;
    ArrayList<CommentBean> commentRowItems;
    public static final String serIP = "";

    SharedPreferences sharedpreferences;

    public CommentAdapter(Context context, ArrayList<CommentBean> commentRowItems) {
        this.context = context;
        this.commentRowItems = commentRowItems;
    }

    @Override
    public int getCount() {
        return commentRowItems.size();
    }

    @Override
    public Object getItem(int position) {
        return commentRowItems.get(position);
    }

    @Override
    public long getItemId(int position) {
        return commentRowItems.indexOf(getItem(position));
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        if (convertView == null) {
            LayoutInflater mInflater = (LayoutInflater) context
                    .getSystemService(Activity.LAYOUT_INFLATER_SERVICE);
            convertView = mInflater.inflate(R.layout.adapter_comment_list_item, null);
        }

        sharedpreferences = PreferenceManager.getDefaultSharedPreferences(context);

        ImageView userIcon = (ImageView) convertView.findViewById(R.id.UserIcon);
        TextView userName = (TextView) convertView.findViewById(R.id.UserName);
        TextView comment = (TextView) convertView.findViewById(R.id.Comment);
        TextView postedTime = (TextView) convertView.findViewById(R.id.PostedTime);

        CommentBean commentBean = commentRowItems.get(position);
        loadPic(userIcon, "profilePic", commentBean.getProfilepic());
        userName.setText(commentBean.getUsername());
        comment.setText(commentBean.getComment());
        postedTime.setText(commentBean.getPostedat());
        return convertView;
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
}
