<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
var populateURL = "./populate"; // API URI for getting table data
var modifyURL = "./dtnewsposts/"; // API URI for updating data

function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
</script>

{{ Form::open(['data-remote' => 'data-remote', 'route' => 'dtnewsposts.bulk', 'id' => 'hidden_basic_actions', 'method' => 'patch']) }}
{{ Form::hidden('action', null, ['id' => 'action']) }}
{{ Form::hidden('affectedmk', null, ['id' => 'affectedmk']) }}
{{ Form::close() }}

{{ Form::open(['data-remote' => 'data-remote', 'route' => ['dtnewsposts.edit', 4], 'id' => 'hidden_edit', 'method' => 'get']) }}
<input type="text" name="query_mk" value="x" />
{{ Form::close() }}

@extends('template.framework_datatable_base')
<?php 
   
    $json = json_decode($json, true);
   
    $cols_friendly = $json['cols_friendly'];
    $cols_internal = $json['cols_internal'];
    $data = json_decode($json['data'], true);
  
  

?>

<script>
function isspam() {
    $('#hidden_basic_actions>#action').val('isspam');
    $('#hidden_basic_actions>#affectedmk').val(get_selected_mk());
    $('#hidden_basic_actions').submit();
    location.reload(true);
}

function notspam() {
    $('#hidden_basic_actions>#action').val('notspam');
    $('#hidden_basic_actions>#affectedmk').val(get_selected_mk());
    $('#hidden_basic_actions').submit();
    location.reload(true);
}

$(document).ready(function() {
	
	setTimeout(function() {
		$('#arrownewspost').trigger('click');
		<?php 
			$current_item_index = 0;
			if (isset($_GET['onhold']) && $_GET['onhold'] == 'true') $current_item_index = 1;
			else if (isset($_GET['archived']) && $_GET['archived'] == 'true') $current_item_index = 2;
		?>
		$('#arrownewspost').parent().siblings().children().eq(<?php echo $current_item_index ?>).children().css({'color':'#3c8dbc', 'font-weight':'bold'})
		$('#pageTitle').html('News Posts ' + $('#arrownewspost').parent().siblings().children().eq(<?php echo $current_item_index ?>).children().html());
	}, 100);
});

    if (getParameterByName('onhold') == 'true') {
        setTimeout(function(){
            $('#group1').empty();
            $('#group1').append('<button type="button" id="bar_delete" class="btn btn-danger" onclick="isspam()"><i class="fa fa-thumbs-o-down">&nbsp;</i>Spam</button> &nbsp;');
            $('#group1').append('<button type="button" id="bar_delete" class="btn btn-success" onclick="notspam()"><i class="fa fa-thumbs-o-up">&nbsp;</i>Not Spam</button>');
            $('#group2').remove();

        }, 100);
        
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
    <h3 class="box-title" id="add-title"><button class="btn btn-default "  data-toggle="tooltip" title="" data-original-title="Discard" onclick="hide_add();">&nbsp;<i class="fa fa-times"></i>&nbsp;</button>&nbsp;&nbsp;
    Add News Post
    </h3>
@stop

@section('box_add_body')
    @parent
    {{ Form::open(['data-remote' => 'data-remote', 'route' => 'dtnewsposts.store', 'id' => 'hidden_store', 'method' => 'post']) }}
	<div class="box-body">
    
													
													<div id="adderRowa1" class="row rowPreview"><div class="col-xs-12"><label for="inNEWSTITLE">Title</label><input type="text" class="form-control" id="inNEWSTITLE" name="inNEWSTITLE" placeholder=""></div><br>&nbsp; </div><div id="adderRowa2" class="row rowPreview"><div class="col-xs-12"><label for="inNEWSDESCRIPTION">Description</label><textarea class="form-control" id="inNEWSDESCRIPTION" name="inNEWSDESCRIPTION" placeholder=""></textarea></div><br>&nbsp; </div><div id="adderRowa3" class="row rowPreview"><div class="col-xs-6"><label for="inLATITUDE">Latitude</label><input type="text" class="form-control" id="inLATITUDE" name="inLATITUDE" placeholder=""></div><div class="col-xs-6"><label for="inLONGITUDE">Longitude</label><input type="text" class="form-control" id="inLONGITUDE" name="inLONGITUDE" placeholder=""></div><br>&nbsp; </div><div id="adderRowa4" class="row rowPreview"><div class="col-xs-6"><label for="inREGISTEREDUSER_MK">ID of Author</label><input type="text" class="form-control" id="inREGISTEREDUSER_MK" name="inREGISTEREDUSER_MK" placeholder="" ></div><div class="col-xs-6"><label for="inNEWSCATEGORY_MK">ID of News Category</label><input type="text" class="form-control" id="inNEWSCATEGORY_MK" name="inNEWSCATEGORY_MK" placeholder="" ></div><br>&nbsp; </div>
	</div><!-- /.box-body -->
@stop

@section('box_add_footer')
	<div class="box-footer">
    <button type="submit" class="btn btn-primary">Add</button>
	<button type="button" class="btn btn-default" onclick="hide_add();">Discard</button>
	</div>
	{{ Form::close() }}
    @parent
@stop

<!-- The box for modifying record -->
@section('box_modify_header')
    
	
    <h3 class="box-title" id="add-title">@parent<button class="btn btn-default "  data-toggle="tooltip" title="" data-original-title="Discard" onclick="hide_modify();">&nbsp;<i class="fa fa-times"></i>&nbsp;</button> &nbsp;&nbsp;
    
	Edit News Post
    </h3>
@stop

@section('box_modify_body')
    @parent
	{{ Form::open(['data-remote' => 'data-remote', 'route' => ['dtnewsposts.update', 0], 'id' => 'hidden_modify', 'method' => 'put']) }} 
	<div class="box-body">
	
    
													
													<div id="adderRowa1" class="row rowPreview"><div class="col-xs-12"><label for="inNEWSTITLE">Title</label><input type="text" class="form-control" id="inNEWSTITLE" name="inNEWSTITLE" placeholder="" data-internal="NEWSTITLE"></div><br>&nbsp; </div><div id="adderRowa2" class="row rowPreview"><div class="col-xs-12"><label for="inNEWSDESCRIPTION">Description</label><textarea class="form-control" id="inNEWSDESCRIPTION" name="inNEWSDESCRIPTION" placeholder="" data-internal="NEWSDESCRIPTION"></textarea></div><br>&nbsp; </div><div id="adderRowa3" class="row rowPreview"><div class="col-xs-6"><label for="inLATITUDE">Latitude</label><input type="text" class="form-control" id="inLATITUDE" name="inLATITUDE" placeholder="" data-internal="LATITUDE"></div><div class="col-xs-6"><label for="inLONGITUDE">Longitude</label><input type="text" class="form-control" id="inLONGITUDE" name="inLONGITUDE" placeholder="" data-internal="LONGITUDE"></div><br>&nbsp; </div><div id="adderRowa4" class="row rowPreview"><div class="col-xs-4"><label for="inPOSTEDAT">Posted At</label><input type="text" class="form-control" id="inPOSTEDAT" name="inPOSTEDAT" placeholder="" data-internal="POSTEDAT" disabled="disabled"></div><div class="col-xs-4"><label for="inREGISTEREDUSER_MK">ID of Author</label> <button type="button" class="btn btn-success btn-sm" onclick="toOne('./dtusers', this)"><i class="fa fa-mail-forward"></i></button><input type="text" class="form-control" id="inREGISTEREDUSER_MK" name="inREGISTEREDUSER_MK" placeholder="" data-internal="REGISTEREDUSER_MK" disabled="disabled"></div><div class="col-xs-4"><label for="inREGISTEREDUSER_MK">ID of News Category</label> <button type="button" class="btn btn-success btn-sm" onclick="toOne('./dtusers', this)"><i class="fa fa-mail-forward"></i></button><input type="text" class="form-control" id="inNEWSCATEGORY_MK" name="inNEWSCATEGORY_MK" placeholder="" data-internal="NEWSCATEGORY_MK" disabled="disabled"></div><br>&nbsp; </div>
    
    <input type="hidden" name="updatemk" />
	<script>
		$('input[name=updatemk]').val($('input[name=query_mk]').val());
	</script>
	</div><!-- /.box-body -->
@stop

@section('box_modify_footer')
	<div class="box-footer">
    <button type="submit" class="btn btn-primary">Update</button>
	<button type="button" class="btn btn-default" onclick="hide_modify();">Discard</button>
	</div>
	{{ Form::close() }}
    @parent
@stop

