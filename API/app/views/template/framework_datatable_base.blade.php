<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        @include('template.framework_datatable_head')
		
		<style>
			.row-edit-locked {
				background-color:#888; color:#fff;
			}
			
			.dataTables_empty {
				text-align:center;
			}
		</style>
		
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="./js/cookie_helper.js"></script>
        <script>
		function getParameterByName(name) {
			name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
				results = regex.exec(location.search);
			return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}
		
		
		function documentLoaded() {
			// trigger row click to view data if needed(e.g. to-one relationships)
			//$('section.content').hide();
			setTimeout(function(){
				
				var mk_to_view = '<?php if (isset($_GET['id'])) echo $_GET['id']; ?>';
				
				if (mk_to_view != '') {
					
					var originalLength = $('select[name=example1_length]').val();
					
					$('select[name=example1_length]').val('-1').trigger('change');
				
					var mk_row_index = $('#example1 th#mk').index('#example1 > thead > tr > th');
					
					// trigger click
					$.each($('#example1 > tbody > tr'), function() { 
						//alert('ok' + mk_row_index);
						if (mk_to_view == $(this).children().eq(mk_row_index).html()) {
							$(this).children().eq(mk_row_index).trigger('click');
							//alert('cliked');
							return false;
						}
					});
					
					$('select[name=example1_length]').val(originalLength).trigger('change');
				}
				
				setTimeout(function(){
					$('section.content').css('visibility','');
				}, 50);
				
			}, 1);
			// end view data if needed
		}
		
        $(document).ready(function(){
			
		
			setTimeout(function(){
				$('.right-side').css('padding-top', '0px');
			}, 100);
				
        	$('th').on('click', function(e) { 
        		setTimeout(function(){
        			$('.sort-disabled').attr('class', 'sort-disabled gridedit-disabled');
        			$(".sort-disabled").off('click');
        		}, 0);
        	});


        	
        	setTimeout(function(){
        		$('.sort-disabled').attr('class', 'sort-disabled gridedit-disabled');
        		$(".sort-disabled").off('click');
        		//$('select').parent().parent().parent().parent().hide();
        		$('.sort-disabled').css('width', $('input[type=checkbox]').eq(0).width());
        		$('.flag').css('width', $('input[type=checkbox]').eq(1).width() * 2);
        		$('#main_datatable_nav>tbody>tr').children().each(function() { $(this).css('padding-right', '20px'); });

        		var before_toggle_checked;
        		$('ins').eq(0).mouseover(function(event){
        			before_toggle_checked = ($('div.icheckbox_minimal').eq(0).attr('aria-checked') == 'true') ? true : false;
        		});

        		var master_toggle_checked = false;
				
				// Clicked on a row to edit, only if not in archived data table view
				if (getParameterByName('archived') == "") {
					$('tbody').eq(1).on('click', "td:not('.disabled, [id*=group]')", function() { 
						//alert('clicked');
						//alert($(this).parent().children().eq(get_mk_header_position()).html()); // was 2
						
						
						var query_mk = $(this).parent().children().eq(get_mk_header_position()).html();
					
						
						$('input[name=query_mk]').val(query_mk);
						
						setTimeout(function(){
							$('#hidden_edit').submit();
						}, 1);
						
						
						modify(query_mk);
					} );
				}
				
        		// Check/uncheck all
				$('ins').eq(0).click(function(event){
					
					

					var check_all = ($('div.icheckbox_minimal').eq(0).attr('aria-checked') == 'true') ? true : false;
					
					if (check_all == false && master_toggle_checked == false) {
						$('ins').eq(0).trigger('click');
						//alert('hihi');
						return;
					}
					

					//alert('check all ? ' + check_all);
					var checked_arr = new Array();
					var unchecked_arr = new Array();
					//alert('aria check first is ' + check_all);
					$('div.icheckbox_minimal').each(function(idx) {
						if (idx == 0) return true;
						if ($(this).attr('aria-checked') == 'true') {
							// this particular checkbox was checked
							checked_arr.push(idx);
						} else {
							// this particular checkbox was NOT checked
							unchecked_arr.push(idx);
						}
					});

					if(check_all) {
						// need to check all
						$('div.icheckbox_minimal>ins').each(function(idx){
							if ($.inArray(idx, unchecked_arr) > -1) {
								$(this).trigger('click');
							}
						});
					} else {
						// need to uncheck all
						$('div.icheckbox_minimal>ins').each(function(idx){
							if ($.inArray(idx, checked_arr) > -1) {
								$(this).trigger('click');
							}
						});
					}

					if (check_all) {
						$('div.icheckbox_minimal').eq(0).addClass('checked');
						$('div.icheckbox_minimal').eq(0).attr('aria-checked', 'true');
						master_toggle_checked= true;
					}
					
        		});

				// onclick of any non toggle all checkbox
        		// Detect if all checkboxes are checked, ignoring the toggle_all
        		$('div.icheckbox_minimal>ins').slice(1).click(function(){
        			$('div.icheckbox_minimal').eq(0).removeClass('checked');
					$('div.icheckbox_minimal').eq(0).attr('aria-checked', 'false');
					master_toggle_checked = false;


					// tick the master toggle all checkbox if all checkboxes are checked
					setTimeout(function() {
						var un_checked_count = 0;
	        			$('div.icheckbox_minimal').each(function(idx) {
							if (idx == 0) return true;
							

							if ($(this).attr('aria-checked') == 'false') {
								// this particular checkbox was NOT checked
								un_checked_count++;
							}
							
						});
						if (un_checked_count == 0) {
							$('div.icheckbox_minimal').eq(0).addClass('checked');
						$('div.icheckbox_minimal').eq(0).attr('aria-checked', 'true');
						//master_toggle_checked= true;
						}
        			}, 1);

        			/*
        			console.log('slice');
        			setTimeout(function() {
	        			$('div.icheckbox_minimal').each(function(idx) {
							if (idx == 0) return true;
							console.log('thru' + idx);

							if ($(this).attr('aria-checked') == 'false') {
								// this particular checkbox was NOT checked
								
								$('div.icheckbox_minimal').eq(0).removeClass('checked');
								$('div.icheckbox_minimal').eq(0).attr('aria-checked', 'false');
								//$('ins').eq(0).trigger('click');
								console.log('removed it coz ' + idx);
							
								return false;
							} 
						
							
						});
        			}, 1);
					*/

        		});



        	}, 1);

		});

		function refresh_datatable() {
			$.ajax({
				url:populateURL,
				method:"GET",
				success:function(data){
					alert('SUCCESS ajax');
					alert(data);
				
					data = data.substring(0, data.lastIndexOf(';'));
					
					data = data.replace(/<script>/g, "");
					data = data.replace(/<\/script>/g, "");
					console.log('returned: ' + data);
					
					eval(data);
					
					populate_dt(data);
					
					//window.location.href = window.location.pathname;
					
					
				},
				error:function() {
					alert('error');
				}
			});
		
        	//$('#main_datatable').slideUp(250, function(){$('#main_datatable_modify').slideDown(250);} );
		}
		
        function add() {
        	$('#main_datatable').slideUp(250, function(){$('#main_datatable_add').slideDown(250);} );
        }
        function hide_add() {
        	$('#main_datatable_add').slideUp(250, function(){$('#main_datatable').slideDown(250);} );
        }
		
		function modify(q_mk) {
			
		
			
			$.ajax({
				type: "GET",
				url: modifyURL + q_mk
				//data: { updatemk: q_mk}
			}).done(function( msg ) {
				var json_result = JSON.parse(msg);
				for (var k in json_result[0]) {
					if ($('input[data-internal=' + k + ']').length)
						$('input[data-internal=' + k + ']').val(json_result[0][k]);
					else if ($('textarea[data-internal=' + k + ']').length)
						$('textarea[data-internal=' + k + ']').html(json_result[0][k]);
				}
				
			});
		
        	$('#main_datatable').slideUp(250, function(){$('#main_datatable_modify').slideDown(250);} );
        }
        function hide_modify() {
        	$('#main_datatable_modify').slideUp(250, function(){$('#main_datatable').slideDown(250);} );
			
			// hide relationship back button
			$('span#backButton').hide();
        }
		
		function get_girdlock_disabled_cols() {
			// find out which col cannot be grid-unlocked
			var col_idx_locked_arr = new Array();
			var looking_idx = 0;
			$('thead').children().children().each(function(){
				if ($(this).hasClass('gridedit-disabled'))
					col_idx_locked_arr.push(looking_idx);
				looking_idx++;
			});
			//console.log(col_idx_locked_arr);
			return col_idx_locked_arr;
		}
		
		function grid_edit() {
			var col_idx_locked_arr = get_girdlock_disabled_cols();
		
			// unlock all grid-unlockable cells
			$('tbody').eq(1).children().each(function(){ 
				var $row_cells = $(this).children();
				for (var cell_no = 0; cell_no < $row_cells.size(); cell_no++) {
					if ($.inArray(cell_no, col_idx_locked_arr) != -1) continue;
					var cell_content = $row_cells.eq(cell_no).html();
					$row_cells.eq(cell_no).html('<input type="text" style="width:100%; font-size:smaller;" value="'+ cell_content +'" data-original="'+ cell_content +'" />');
				}
			});
			
			$('div#example1_wrapper').children(':eq(0)').hide();
			$('td[id*=group]').hide();
			$('td[id=group4]').show();
			$('thead').children().children().eq(0).hide(1000);
			$('tbody').eq(1).children().each(function() { $(this).children().eq(0).hide(1000) });
			$('td').not('[id*=group]').addClass('disabled');
			
			// Hide bottom bar and disable sorting
			$('#example1').siblings().eq(1).hide(1000);
			
			$('ul.pagination>li:not(.disabled)>a').on('click', function() {
				setTimeout(function() {
					//grid_edit_discard(); //replace this func!! temply save edited details first!
					// experimental start
					grid_edit_save();
					
					$('thead').children().children().eq(0).show(1000);
					$('tbody').eq(1).children().each(function() { $(this).children().eq(0).show(1000) });
					$('td').not('[id*=group]').removeClass('disabled');
					
					$('div#example1_wrapper').children(':eq(0)').show();
					$('td[id*=group]').show();
					$('td[id=group4]').hide();
					// experimental end
					grid_edit();
					alert('grid edit OMG!');
				}, 1);
			});
		}
		
		function grid_edit_discard() {
			$('thead').children().children().eq(0).show(1000);
			$('tbody').eq(1).children().each(function() { $(this).children().eq(0).show(1000) });
			$('td').not('[id*=group]').removeClass('disabled');
			
			$('div#example1_wrapper').children(':eq(0)').show();
			$('td[id*=group]').show();
			$('td[id=group4]').hide();
			
			$('#example1').siblings().eq(1).show(1000);
			
			var col_idx_locked_arr = get_girdlock_disabled_cols();
			// lock up all grid-unlockable cells, revert to original content
			$('tbody').eq(1).children().each(function(){ 
				var $row_cells = $(this).children();
				for (var cell_no = 0; cell_no < $row_cells.size(); cell_no++) {
					if ($.inArray(cell_no, col_idx_locked_arr) != -1) continue;
					$row_cells.eq(cell_no).html($row_cells.eq(cell_no).children().eq(0).attr('data-original'));
					//$row_cells.eq(cell_no).html('<input type="text" style="width:100%" value="'+ cell_content +'" data-original="'+ cell_content +'" />');
				}
			});
			
			$('ul.pagination>li:not(.disabled)>a').off('click');
		}
		
		function grid_edit_save() {
			var changed = false;
			var changed_mk_data_obj = new Object();
			
			
			var col_idx_locked_arr = get_girdlock_disabled_cols();
			// lock up all grid-unlockable cells, revert to original content
			$('tbody').eq(1).children().each(function(){ 
				var $row_cells = $(this).children();
				for (var cell_no = 0; cell_no < $row_cells.size(); cell_no++) {
					if ($.inArray(cell_no, col_idx_locked_arr) != -1) continue;
					
					var $cell = $row_cells.eq(cell_no).children().eq(0);
					if ($cell.attr('data-original') !== $cell.val()) {
						// some value changed
						if (changed_mk_data_obj[$row_cells.eq(get_mk_header_position()).html()] == null)  
							changed_mk_data_obj[$row_cells.eq(get_mk_header_position()).html()] = new Object();
						
						//alert('different at mk ' + $row_cells.eq(get_mk_header_position()).html());
						changed_mk_data_obj[$row_cells.eq(get_mk_header_position()).html()][get_header_internal_name($cell.parent().index())] = $cell.val();
						console.log($row_cells);
						changed = true;
					//$row_cells.eq(cell_no).html('<input type="text" style="width:100%" value="'+ cell_content +'" data-original="'+ cell_content +'" />');
					}
				}
			});
			
			console.log(changed_mk_data_obj);
			//dd();
			
			if (!changed) {
				grid_edit_discard();
			} else {
				$('thead').children().children().eq(0).show(1000);
				$('tbody').eq(1).children().each(function() { $(this).children().eq(0).show(1000) });
				$('td').not('[id*=group]').removeClass('disabled');
				$('td[id*=group]').show();
				$('td[id=group4]').hide();
				
				// update process
				document.write('{{ Form::open(['data-remote' => 'data-remote', 'route' => 'dtusers.update', 'id' => '17', 'method' => 'put']) }}');
				document.write('<input type="text" name="serialized" value="' + ($.param(changed_mk_data_obj)) + '" />');
				
				// get data from each cell from the row
				
				document.write('{{ Form::close() }}');
				
				//console.log('FK' + $('form[id=17]').children().eq(2));
				
				$('#hidden_gridupdate').submit();
			}
			
		}
		
		// Commits grid edit saved changes(from cookie)
		function grid_edit_submit() {
			// save once more just in case
			grid_edit_save();		
		
			// submit
			if (cookieExists('gridchanges')) {
				var grid_changes = getCookie('gridchanges');
				console.log('submitting: ');
				console.log(grid_changes);
			}
			
			// Erase cookie
			eraseCookie('gridchanges');
			console.log('earsed grid cookie');
			
			// UI shifting
			$('thead').children().children().eq(0).show(1000);
			$('tbody').eq(1).children().each(function() { $(this).children().eq(0).show(1000) });
			$('td').not('[id*=group]').removeClass('disabled');
			$('div#example1_wrapper').children(':eq(0)').show();
			$('td[id*=group]').show();
			$('td[id=group4]').hide();
		}
		
		function hide_mk_column() {
			$('tbody').eq(1).children().each(function() {
				$(this).children().eq(get_mk_header_position()).hide();
				$('[role=row]').children().eq(get_mk_header_position()).hide();
			});
			
			
		
		}
		
		function refresh() {
			
			location.reload(true);
		
		}
		
		function get_selected_rows_idx() {
			var checked_arr = new Array();
			var unchecked_arr = new Array();
			//alert('aria check first is ' + check_all);
			$('div.icheckbox_minimal').each(function(idx) {
				if (idx == 0) return true;
				if ($(this).attr('aria-checked') == 'true') {
					// this particular checkbox was checked
					checked_arr.push(idx);
				}
			});
			console.log('check ' + checked_arr);
			return checked_arr;
		}
		
		function get_mk_header_position() {
			var pos = 0;
			$('[role=row]').children().each(function() {
				if ($(this).attr('id') == 'mk') return false;
				pos++;
			});
			return pos;
		}
		
		function get_header_internal_name(header_index) {
			return $('[role=row]').children().eq(header_index).attr('data-internal');
		}
		
		function get_selected_mk() {
			var selected_idx_arr = get_selected_rows_idx();
			if (selected_idx_arr.length < 1) return selected_idx_arr;
			
			// Get MK of selected rows
			var mk_arr = new Array();
			for (var idx = 0; idx < selected_idx_arr.length; idx++) {
				//console.log('looking at ' + selected_idx_arr[idx]);
				mk_arr.push($('tbody').eq(1).children().eq(selected_idx_arr[idx] - 1).children().eq(get_mk_header_position()).html());
			}
			//console.log('hi ' + mk_arr);
			return mk_arr;
		}
		
		function archive_selected() {
			
			$('#hidden_basic_actions>#action').val('archive');
			$('#hidden_basic_actions>#affectedmk').val(get_selected_mk());
			$('#hidden_basic_actions').submit();
			
			
		
			console.log($('#hidden_basic_actions>#action').val());
			console.log($('#hidden_basic_actions>#affectedmk').val());
			refresh();
		}
		
		function restore_selected() {
			
			$('#hidden_basic_actions>#action').val('restore');
			$('#hidden_basic_actions>#affectedmk').val(get_selected_mk());
			$('#hidden_basic_actions').submit();
			
			
		
			console.log($('#hidden_basic_actions>#action').val());
			console.log($('#hidden_basic_actions>#affectedmk').val());
			refresh();
		}
		
		function delete_selected() {
			
			$('#hidden_basic_actions>#action').val('delete');
			$('#hidden_basic_actions>#affectedmk').val(get_selected_mk());
			$('#hidden_basic_actions').submit();
			
			
		
			console.log($('#hidden_basic_actions>#action').val());
			console.log($('#hidden_basic_actions>#affectedmk').val());
			refresh();
		}
		
		function flag_selected() {
			
			$('#hidden_basic_actions>#action').val('flag');
			$('#hidden_basic_actions>#affectedmk').val(get_selected_mk());
			$('#hidden_basic_actions').submit();
			refresh();
		}
		
		function unflag_selected() {
			
			$('#hidden_basic_actions>#action').val('unflag');
			$('#hidden_basic_actions>#affectedmk').val(get_selected_mk());
			$('#hidden_basic_actions').submit();
			refresh();
			
		}
		

		
        </script>
    </head>
    <body onload="documentLoaded()" class="skin-blue">
		
        <!-- header logo: style can be found in header.less -->
        @include('template.header')
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            @include('template.menu')

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
               	@include('template.title')

                <!-- Main content -->
                <section class="content" style="visibility:hidden">
                    <div class="row" id="main_datatable">
                        <div class="col-xs-12">
                           

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">
                                    	<!--
	                                    <div class="btn-group">
	                                            <button type="button" class="btn btn-default" onclick="add()"><i class="fa fa-plus"></i></button>
	                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
	                                                <span class="caret"></span>
	                                                <span class="sr-only">Toggle Dropdown</span>
	                                            </button>
	                                            <ul class="dropdown-menu" role="menu">
	                                                <li><a href="#">Add</a></li>
	                                                <li><a href="#">Add in Bulk</a></li>
	                                            </ul>
	                                    </div>-->
	                                    <table id="main_datatable_nav"><tr>
	                                    <td id="group1">
	                                    <button type="button" id="bar_add" class="btn btn-default" onclick="add()">&nbsp;<i class="fa fa-plus"></i>&nbsp;</button>
	                                    <!--<button type="button" class="btn btn-default disabled"><i class="fa fa-pencil">&nbsp;</i>Edit</button>-->
	                                    <button type="button" id="bar_gridedit" class="btn btn-default " onclick="grid_edit()" style="display:none"><i class="fa fa-edit">&nbsp;</i>Grid Edit</button>
										
										
										<script>
											if (getParameterByName('archived') == 'true') {
												document.write('<button type="button" id="bar_restore" class="btn btn-success" onclick="restore_selected()"><i class="fa fa-undo">&nbsp;</i>Restore</button>');
												document.write('&nbsp;<button type="button" id="bar_delete" class="btn btn-danger" onclick="delete_selected()"><i class="fa fa-trash-o">&nbsp;</i>Trash Forever</button>');
												setTimeout(function(){
										            $('#bar_add').remove();
										            $('#group2').remove();

										        }, 100);
											} else {
												document.write('<button type="button" id="bar_archive" class="btn btn-danger" onclick="archive_selected()"><i class="fa fa-archive">&nbsp;</i>Archive</button>');
											
											}
										</script>
										
	                                    
	                                    </td>
	                                    
	                                    <td id="group2">
										<div class="btn-group">
	                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
													<i class="fa fa-flag"></i>
													<span class="caret"></span>
	                                                <span class="sr-only">Toggle Dropdown</span>

	                                            </button>
	                                            <ul class="dropdown-menu" role="menu">
	                                                <li><a onclick="flag_selected()"><i class="fa fa-flag"></i>Flag</a></li>
	                                                <li><a onclick="unflag_selected()"><i class="fa fa-flag-o"></i>Unflag</a></li>
	                                               
	                                            </ul>
	                                    </div>
	                                    
	                                    <div class="btn-group">
	                                            
	                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
	                                            	<i class="fa fa-lock">&nbsp;</i>
	                                                <span class="caret"></span>
	                                                <span class="sr-only">Toggle Dropdown</span>

	                                            </button>
	                                            <ul class="dropdown-menu" role="menu">
	                                                <li><a href="#">Lock Edit</a></li>
	                                                <li><a href="#">Lock Archive</a></li>
	                                                <li><a href="#">Lock Restore</a></li>
	                                            </ul>
	                                    </div>
										
										<div class="btn-group">
	                                            
	                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
	                                            	<i class="fa fa-unlock">&nbsp;</i>
	                                                <span class="caret"></span>
	                                                <span class="sr-only">Toggle Dropdown</span>

	                                            </button>
	                                            <ul class="dropdown-menu" role="menu">
	                                                <li><a href="#">Unlock Edit</a></li>
	                                                <li><a href="#">Unlock Archive</a></li>
	                                                <li><a href="#">Unlock Restore</a></li>
	                                            </u
	                                    </div>
	                                    </td>
	                                    
	                                    <td id="group3">
	                                    <button type="button" class="btn btn-default" onclick="refresh()">&nbsp;<i class="fa fa-refresh"></i>&nbsp;</button>
	                                    </td>
										
										<td id="group4" style="display:none">
											<button type="button" id="bar_gridedit_save" class="btn btn-success" onclick="grid_edit_save()"><i class="fa fa-save">&nbsp;</i>Save</button>
											<button type="button" id="bar_gridedit_discard" class="btn btn-default" onclick="grid_edit_discard()"><i class="fa fa-times">&nbsp;</i>Discard</button>
										</td>
	                                    </tr></table>
                                    </h3>
                                </div><!-- /.box-header -->
                                <div class="box-body table-responsive">
								 
                                    <table id="example1" class="table table-bordered table-hover" >
                                        <thead>
                                            <tr>
												@section('dt_header')
													<th class="sort-disabled gridedit-disabled"><input type="checkbox" id="toggle_all" /></th>
												@show
                                            </tr>
                                        </thead>
                                        <tbody>
											@section('dt_rows')
												<!--<tr><td id="empty_row">No data to display.</td></tr>
												<script>$('#empty_row').attr('colspan', $('th').size()).css('text-align', 'center');</script>-->
												
											@show
											
                                        </tbody>
                                       
                                    </table>
									<!--
									<span style="display:none">
									<table id="dtmain" class="table table-bordered table-hover"  >
                                        <thead>
                                            <tr>
												@section('dt_header')
													<th class="sort-disabled gridedit-disabled"><input type="checkbox" id="toggle_all" /></th>
												@show
                                            </tr>
                                        </thead>
                                        <tbody>
											
											
											
                                        </tbody>
                                       
                                    </table>
									</span>
									-->
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>
                    <div class="row" id="main_datatable_add" style="display:none">
                        <div class="col-xs-12">
                           <div class="box box-primary">
                                <div class="box-header">
									@section('box_add_header')
									@show
							   </div><!-- /.box-header -->
                                <!-- form start -->
                                <!--<form role="form"> -->
									@section('box_add_body')
									@show
									
									@section('box_add_footer')
									@show
                                <!-- </form> -->
                            </div>

                            
                        </div>
                    </div>
					
					<div class="row" id="main_datatable_modify" style="display:none">
                        <div class="col-xs-12">
                           <div class="box box-primary">
                                <div class="box-header">
									@section('box_modify_header')
										<span id="backButton"></span>
									@show
							    </div><!-- /.box-header -->
                                <!-- form start -->
                                <!--<form role="form"> -->
								@section('box_modify_body')
								@show
								
								@section('box_modify_footer')
								@show
								
                                <!-- </form> -->
                            </div>

                            
                        </div>
                    </div>
					
					
				
					
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        @include('template.framework_datatable_end')   
	
		<script>
		$(document).ready(function() {
			// Change modify form action to correct primary key before submitting update record
			$('#hidden_modify').on('submit', function(event) {				
				$('#hidden_modify').attr('action', $('#hidden_modify').attr('action').substring(0, $('#hidden_modify').attr('action').lastIndexOf("/") + 1) +  $('input[name=query_mk]').val());
				
			});
			
			$('ul.pagination').on('click', function() {
				$('html,body').animate({ scrollTop: 0 }, 'slow');
				
			});
			
			
			
			
			/*  AJAX serverside test */
			/*
			$('#dtmain').dataTable( {
				processing: true,
				serverSide: true,
				"bProcessing": true,
				"columnDefs": [ {
					"targets": 0,
					"data": null,
					"orderable": false,
					"defaultContent": '<input type="checkbox" class="toggle_one" />'
				}, {
					"targets": 1,
					"name": 'test'
				},
				
				
				],
				ajax: {
					url: './dtusers/query',
					type: 'GET'
				},
				 "drawCallback": function( settings ) {
					$('#dtmain').find('input').iCheck({checkboxClass: 'icheckbox_minimal'});
				}
				
				
			} );
			*/
			/*  END AJAX serverside test */
			
			/*
			// For AJAX populate datatable data
			$('#hidden_populate_datatable').on('submit', function(event) {
				$.get('http://localhost/fyp/laravel/public/users/populate', function (data) {
					console.log('getting');
					data = data.substring(data.lastIndexOf('/<\/script>'));
						
					
					console.log('returned: ' + data);
					var jsonObj = JSON.parse(data);
					var cols_friendly = jsonObj.cols_friendly;
					var cols_internal = jsonObj.cols_internal;
					var cell_data = JSON.parse(jsonObj.data);
					
					console.log(cell_data);
					
					// print data table header
					var appendHeaderNode;
					for (var c = 0; c < cols_friendly.length; c++) {
						var col = cols_internal[c];
						if (col != 'F_FLAGGED') {
							// is not flag column
							if (col.substring(0, 2) == 'F_')
								appendHeaderNode = '<th class="gridedit-disabled">' + cols_friendly[c] + '</th>';
							else if (col == 'MK')
								appendHeaderNode = '<th id="mk" class="gridedit-disabled">' + cols_friendly[c] + '</th>';
							else
								appendHeaderNode = '<th data-internal="' + cols_internal[c] + '">' + cols_friendly[c] + '</th>';
						} else {
							// is flag column
							appendHeaderNode = '<th class="flag gridedit-disabled"><i class="fa fa-flag"></i></th>';
							
						}
						
						$('#dtheader').append(appendHeaderNode);
					}
					
					
					// convert cell_data to array with values only, removing the keys
					var final_cell_data_arr = new Array();
					for (var row = 0; row < cell_data.length; row++) {
						var one_row_arr = new Array();
						for (var pos = 0 ; pos < cols_internal.length; pos++) {
							
							console.log( cell_data[row][cols_internal[pos]]);
							one_row_arr.push(cell_data[row][cols_internal[pos]]);
						}
						final_cell_data_arr.push(one_row_arr);
					}
					console.log(final_cell_data_arr);
					
					/*
					// print data table body
					if (cell_data.length > 0) {
						console.log(cell_data);
						for (var row = 0; row < cell_data.length; row++) {
							$('#dtmain > tbody').append('<tr><td><input type="checkbox" class="toggle_one" /></td>');
							for (var pos = 0 ; pos < cols_internal.length; pos++) {
								//console.log(cell_data[row][cols_internal[pos]]);
								if (cols_internal[pos] != 'F_FLAGGED')
									$('#dtmain > tbody').append('<td>' + cell_data[row][cols_internal[pos]] +'</td>');
								else {
									if (cell_data[row][cols_internal[pos]] == 0)
										$('#dtmain > tbody').append('<td>&nbsp;</td>');
									else
										$('#dtmain > tbody').append('<td><i class="fa fa-flag"></i></td>');
								}
							}
							//console.log(cell_data[row]['MK']);
							$('#dtmain > tbody').append('</tr>');
						}
					} else {
						$('#dtmain > tbody').append('<tr><td id="empty_row">No data to display.</td></tr>');
						$('#empty_row').attr('colspan', $('th').size()).css('text-align', 'center');				
					}
					*/
					
					/*
					$('#dtajax').dataTable( {
						"data": final_cell_data_arr,
						"columns": [
            { "title": "Engine" },
            { "title": "Browser" },
            { "title": "Platform" },
            { "title": "Version", "class": "center" },
            { "title": "Grade", "class": "center" },
			 { "title": "Grade", "class": "center" }
        ]
					} );
					
				});
				event.preventDefault();
			});
			*/
		
			$('form[id*=hidden]:not(#hidden_populate_datatable)').on('submit', function(event) {
				
				//function manualSubmit() {
					var form = $(this);
					var method = form.find('input[name=_method]').val() || 'POST';
					var url = $(this).attr('action');
					
					
					console.log(method);
					console.log(url);
					
					if (form.attr('method') == 'GET') {
						$.get(url, function (data) {
							console.log('getting');
							data = data.substring(data.lastIndexOf('/<\/script>'));
								
							
							console.log('returned: ' + data);
						});
					} else {
					
						$.ajax({
							url:url,
							data: form.serialize(),
							method:method,
							success:function(data){
								//alert('SUCCESS ajax!!');
								//alert(data);
							
								data = data.substring(0, data.lastIndexOf(';'));
								
								data = data.replace(/<script>/g, "");
								data = data.replace(/<\/script>/g, "");
								console.log('returned: ' + data);
								
								eval(data);
								
								populate_dt(data);
								
								//window.location.href = window.location.pathname;
								
								
							},
							error:function() {
								alert('error');
							}
						});
					
					}
				//}
				//manualSubmit();
				
				event.preventDefault();
			});
			
			//$('#hidden_populate_datatable').submit();
		});
		</script>
		<script>
		function toOne(baseURI, btnInstance) {
			// make sure browser backstack is also in sync
			var current_id = $('input[name=query_mk]').val();
			var destination_id = $(btnInstance).siblings('input').val();
			history.pushState(null, '', window.location.href + '?id=' + current_id);
			window.location.href = baseURI + '?id=' + destination_id + '&origin=' + btoa(window.location.href.toString().split(window.location.host)[1]) + '';
		}
		
		// Load previous page in the relationship
		function backRelationship() {
			var backUrl = atob(getParameterByName('origin'));
			window.location.href = backUrl;
		}

		$(document).ready(function() {
			if (window.location.href.indexOf('origin=') > -1) {
				// has origin on another side of relation, so provide back button
				//alert('showback');
				$('span#backButton').html('');
				$('span#backButton').html('<button class="btn btn-success"  data-toggle="tooltip" title="" data-original-title="Return" onclick="backRelationship();">&nbsp;<i class="fa fa-mail-reply"></i>&nbsp;</button>');
			}
			
			// Store returned row count
			if ($('table#example1 tbody tr td.dataTables_empty').size() == 0)			
				$('#rowsReturned').val($('table#example1 tbody tr').size());
			
			// Remove useless controls if no rows returned
			//alert($('#rowsReturned').val());
			if ($('#rowsReturned').val() == '0') {
				$('#main_datatable_nav td').not('#group3').remove();
			}
			
		});
		</script>
		<input type="hidden" id="rowsReturned" value="0" />
    </body>
</html>
