<script>
var populateURL = "./populate"; // API URL for getting table data
var modifyURL = "./users/"; // API URL for updating data
</script>


{{ Form::open(['data-remote' => 'data-remote', 'route' => 'users.bulk', 'id' => 'hidden_basic_actions', 'method' => 'patch']) }}
{{ Form::hidden('action', null, ['id' => 'action']) }}
{{ Form::hidden('affectedmk', null, ['id' => 'affectedmk']) }}
{{ Form::close() }}

{{ Form::open(['data-remote' => 'data-remote', 'route' => ['users.edit', 4], 'id' => 'hidden_edit', 'method' => 'get']) }}
<input type="text" name="query_mk" value="x" />
{{ Form::close() }}

@extends('template.framework_datatable_base')
<?php 
   
    $json = json_decode($json, true);
   
    $cols_friendly = $json['cols_friendly'];
    $cols_internal = $json['cols_internal'];
    $data = json_decode($json['data'], true);
  

?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
	$('#pageTitle').html('Active Users');
	$('#pageDescription').html('User accounts that are active.');
	setTimeout(function() {
		$('#arrowuser').trigger('click');
		$('#arrowuser').parent().siblings().children().eq(0).children().css({'color':'#3c8dbc', 'font-weight':'bold'})
		
	}, 100);
});


function populate_dt(data) {
 //ajax to populate dt...todo
 //alert(data);
    //$('#dt_header');
  
/*
  var parsed = JSON.parse(data);
  
      alert('do3ne');
    for (var idx = 0; idx < parsed['cols_friendly'].length; idx++) {
        var col = parsed['cols_friendly'][idx];
        if (col === 'F_FLAGGED') {
            $('#dt_header').append('<th class="flag gridedit-disabled"><i class="fa fa-flag"></i></th>');
            $('#bar_flag').show();
        } else {
            if (col.substring(0, 2) == 'F_') {
                $('#dt_header').append('<th class="gridedit-disabled">' + parsed['cols_friendly'][idx] + '</th>');
            } else if (col === 'MK') {
                $('#dt_header').append('<th id="mk" class="gridedit-disabled">' + parsed['cols_friendly'][idx] + '</th>');
            } else {
                $('#dt_header').append('<th>' + parsed['cols_friendly'][idx] + '</th>');
            }
        }
    }
    
    alert('done');
    
  */  
    
}

</script>


@section('dt_header')
    @parent
    @for ($idx = 0; $idx < count($cols_friendly); $idx++)
        <?php $col = $cols_internal[$idx] ?>
        @unless ($col === 'F_FLAGGED')
            @if (substr($col, 0, 2) == 'F_')
                <th class="gridedit-disabled">{{ $cols_friendly[$idx] }}</th>
            @elseif ($col === 'MK')
                <th id="mk" class="gridedit-disabled">{{ $cols_friendly[$idx] }}</th>
            @else
                <th data-internal="{{ $cols_internal[$idx] }}">{{ $cols_friendly[$idx] }}</th>
            @endif
        @else
            <th class="flag gridedit-disabled"><i class="fa fa-flag"></i></th>
            <script>$('#bar_flag').show()</script>
        @endunless
    @endfor
@stop


@section('dt_rows')
    @unless (count($data))
        @parent
    @else
        @for ($row = 0; $row < count($data); $row++)
            <tr>
                <td><input type="checkbox" class="toggle_one" /></td>
                
                @foreach ($data[$row] as $k => $v)
                    @unless ($k === 'F_FLAGGED')
                        <td>{{{ $v }}}</td> 
                    @else 
                        @if ($v == 0)
                            <td>&nbsp;</td>
                        @else
                            <td><i class="fa fa-flag"></i></td>
                        @endif
                    @endunless
                @endforeach
            </tr>
        @endfor
    @endunless
@stop 

<!-- The box for adding record -->
@section('box_add_header')
    @parent
    <h3 class="box-title" id="add-title"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="" data-original-title="Discard" onclick="hide_add();"><i class="fa fa-times"></i></button>&nbsp;&nbsp;
    Add a New User
    </h3>
@stop

@section('box_add_body')
    @parent
    {{ Form::open(['data-remote' => 'data-remote', 'route' => 'users.store', 'id' => 'hidden_store', 'method' => 'post']) }}
	<div class="box-body">
	
    <div class="row">
		
        <div class="col-xs-6">
			<label for="inFirstName">Name</label>
            <input type="text" class="form-control" id="inFirstName" name="inFirstName" placeholder="First Name">
        </div>
        <div class="col-xs-6">
			<label for="inLastName">&nbsp;</label>
            <input type="text" class="form-control" id="inLastName" name="inLastName" placeholder="Last Name">
        </div>
		<br />&nbsp;
    </div>
    
    
    <div class="form-group">
        <label for="inEmail">Email</label>
        <input type="email" class="form-control" id="inEmail" name="inEmail" placeholder="">
    </div>
    <div class="form-group">
        <label for="inUsername">Username</label>
        <input type="text" class="form-control" id="inUsername" name="inUsername" placeholder="">
    </div>
    <div class="form-group">
        <label for="inPassword">Password</label>
        <input type="password" class="form-control" id="secretPassword" name="secretPassword" placeholder="Password">
        <input type="password" class="form-control" id="secretConfirmPassword" name="secretConfirmPassword" placeholder="Password again">
    </div>
	</div><!-- /.box-body -->
@stop

@section('box_add_footer')
	<div class="box-footer">
    <button type="submit" class="btn btn-primary">Add User</button>
	<button type="button" class="btn btn-default" onclick="hide_add();">Discard</button>
	</div>
	{{ Form::close() }}
    @parent
@stop

<!-- The box for modifying record -->
@section('box_modify_header')
    @parent
    <h3 class="box-title" id="add-title"><button class="btn btn-default btn-sm"  data-toggle="tooltip" title="" data-original-title="Discard" onclick="hide_modify();"><i class="fa fa-times"></i></button>&nbsp;&nbsp;
    Edit User
    </h3>
@stop

@section('box_modify_body')
    @parent
	{{ Form::open(['data-remote' => 'data-remote', 'route' => ['users.update', 0], 'id' => 'hidden_modify', 'method' => 'put']) }} 
	<div class="box-body">
    <label for="inFirstName">Name</label>
    <div class="row">
        <div class="col-xs-6">
            <input type="text" class="form-control" id="inFirstName" name="inFirstName" data-internal="FIRSTNAME" placeholder="First Name">
        </div>
        <div class="col-xs-6">
            <input type="text" class="form-control" id="inLastName" name="inLastName" data-internal="LASTNAME" placeholder="Last Name">
        </div>
    </div><br />
    
    
    <div class="form-group">
        <label for="inEmail">Email</label>
        <input type="email" class="form-control" id="inEmail" name="inEmail" data-internal="EMAIL" placeholder="">
    </div>
    <div class="form-group">
        <label for="inUsername">Username</label>
        <input type="text" class="form-control" id="inUsername" name="inUsername" data-internal="USERNAME" placeholder="">
    </div>
    <div class="form-group">
        <label for="inPassword">Password</label>
        <input type="password" class="form-control" id="secretPassword" name="secretPassword" placeholder="Password">
        <input type="password" class="form-control" id="secretConfirmPassword" name="secretConfirmPassword" placeholder="Password again">
    </div>
	<div class="form-group">
        <label for="inTRUSTCREDITS">Trust Credits</label>
        <input type="text" class="form-control" id="inTRUSTCREDITS" name="inTRUSTCREDITS" data-internal="TRUSTCREDITS" placeholder="">
    </div>
	<input type="hidden" name="updatemk" />
	<script>
		$('input[name=updatemk]').val($('input[name=query_mk]').val());
	</script>
	</div><!-- /.box-body -->
@stop

@section('box_modify_footer')
	<div class="box-footer">
    <button type="submit" class="btn btn-primary">Update User</button>
	<button type="button" class="btn btn-default" onclick="hide_modify();">Discard</button>
	</div>
	{{ Form::close() }}
    @parent
@stop

