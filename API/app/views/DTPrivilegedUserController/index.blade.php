<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
var populateURL = "./populate"; // API URI for getting table data
var modifyURL = "./DTPrivilegedUserController/"; // API URI for updating data

var menuItemElementId = '#'; // ID of main menu item element
</script>

{{ Form::open(['data-remote' => 'data-remote', 'route' => 'DTPrivilegedUserController.bulk', 'id' => 'hidden_basic_actions', 'method' => 'patch']) }}
{{ Form::hidden('action', null, ['id' => 'action']) }}
{{ Form::hidden('affectedmk', null, ['id' => 'affectedmk']) }}
{{ Form::close() }}

{{ Form::open(['data-remote' => 'data-remote', 'route' => ['DTPrivilegedUserController.edit', 4], 'id' => 'hidden_edit', 'method' => 'get']) }}
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
$(document).ready(function() {
	setTimeout(function() {
		$(menuItemElementId).trigger('click');
		<?php 
			$current_item_index = 0;
			
		?>
		$(menuItemElementId).parent().siblings().children().eq(<?php echo $current_item_index ?>).children().css({'color':'#3c8dbc', 'font-weight':'bold'})
		$('#pageTitle').html('Privileged User ' + $(menuItemElementId).parent().siblings().children().eq(<?php echo $current_item_index ?>).children().html());
	}, 100);
});

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
    Add Privileged User
    </h3>
@stop

@section('box_add_body')
    @parent
    {{ Form::open(['data-remote' => 'data-remote', 'route' => 'DTPrivilegedUserController.store', 'id' => 'hidden_store', 'method' => 'post']) }}
	<div class="box-body">
    
													
													<div id="adderRowa1" class="row rowPreview"><div class="col-xs-6"><label for="inFIRSTNAME">First Name</label><input type="text" class="form-control" id="inFIRSTNAME" name="inFIRSTNAME" placeholder=""></div><div class="col-xs-6"><label for="inLASTNAME">Last Name</label><input type="text" class="form-control" id="inLASTNAME" name="inLASTNAME" placeholder=""></div><br>&nbsp; </div><div id="adderRowa2" class="row rowPreview"><div class="col-xs-6"><label for="inEMAIL">Email</label><input type="email" class="form-control" id="inEMAIL" name="inEMAIL" placeholder=""></div><div class="col-xs-6"><label for="inHASH">Password</label><input type="password" class="form-control" id="inHASH" name="inHASH" placeholder=""></div><br>&nbsp; </div><div id="adderRowa3" class="row rowPreview"><div class="col-xs-12"><label for="inPRIVILEGETYPE">Privilege Type</label><input type="text" class="form-control" id="inPRIVILEGETYPE" name="inPRIVILEGETYPE" placeholder=""></div><br>&nbsp; </div>
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
    
    <h3 class="box-title" id="add-title">@parent<button class="btn btn-default "  data-toggle="tooltip" title="" data-original-title="Discard" onclick="hide_modify();">&nbsp;<i class="fa fa-times"></i>&nbsp;</button>&nbsp;&nbsp;
    Edit Privileged User
    </h3>
@stop

@section('box_modify_body')
    @parent
	{{ Form::open(['data-remote' => 'data-remote', 'route' => ['DTPrivilegedUserController.update', 0], 'id' => 'hidden_modify', 'method' => 'put']) }} 
	<div class="box-body">
	
    
													
													<div id="adderRowa1" class="row rowPreview"><div class="col-xs-6"><label for="inFIRSTNAME">First Name</label><input type="text" class="form-control" id="inFIRSTNAME" name="inFIRSTNAME" placeholder="" data-internal="FIRSTNAME"></div><div class="col-xs-6"><label for="inLASTNAME">Last Name</label><input type="text" class="form-control" id="inLASTNAME" name="inLASTNAME" placeholder="" data-internal="LASTNAME"></div><br>&nbsp; </div><div id="adderRowa2" class="row rowPreview"><div class="col-xs-6"><label for="inEMAIL">Email</label><input type="email" class="form-control" id="inEMAIL" name="inEMAIL" placeholder="" data-internal="EMAIL"></div><div class="col-xs-6"><label for="inHASH">Password</label><input type="password" class="form-control" id="inHASH" name="inHASH" placeholder="" data-internal="HASH"></div><br>&nbsp; </div><div id="adderRowa3" class="row rowPreview"><div class="col-xs-12"><label for="inPRIVILEGETYPE">Privilege Type</label><input type="text" class="form-control" id="inPRIVILEGETYPE" name="inPRIVILEGETYPE" placeholder="" data-internal="PRIVILEGETYPE"></div><br>&nbsp; </div>
    
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

