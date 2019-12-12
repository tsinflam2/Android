<head>
<style>
#menu_list {
overflow-y: scroll; /* has to be scroll, not auto */
-webkit-overflow-scrolling: touch;
overflow: hidden;
//float:left;
}
</style>

<script>
$(document).ready(function(){
    
    $(window).resize(function(){
        $('aside').eq(0).height($(window).height() - $('header').height());
        //alert('hi');  
    });
    $('aside.right-side').css('padding-top', $('header').height() +'px');
    
	// Wrap datatable height when scrolled
	setTimeout(function() {
		$('div.wrapper.row-offcanvas.row-offcanvas-left').attr('style', 'min-height:auto');
		$(window).scroll(function() {
			$('div.wrapper.row-offcanvas.row-offcanvas-left').attr('style', 'min-height:auto');
			//alert('scroll');
			
				
			
		});
		$('ul.pagination').children().children().on('click', function() { $('html,body').animate({ scrollTop: 0 }, 'slow');  } );
	},100);
});
</script>
</head>

<aside class="left-side sidebar-offcanvas" style="position:fixed;min-height:0px; max-height:100%; height:300px">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <script>/*
        <div class="user-panel">
            <div class="pull-left image">
                <img src="img/avatar3.png" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>Hello, Jane</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
                <span class="input-group-btn">
                    <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        */
        </script>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <div id="menu_list">
            <ul class="sidebar-menu">
                <li class="active">
                    <a href="#">
                        <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="treeview">
					<a href="">
						<i class="fa fa-file-text "></i>
						<span onclick="javascript:window.location.href = './dtnewsposts';">News Posts</span>
						<i id="arrownewspost" class="fa pull-right fa-angle-left"></i>
					</a>
					<ul class="treeview-menu" style="display: none;">
						<li><a href="./dtnewsposts" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Published</span></a></li>
						<li><a href="./dtnewsposts?onhold=true" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>On Hold</span></a></li>
						<li><a href="./dtnewsposts?archived=true" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Archived</span></a></li>
					</ul>
				</li>
               
              <!--
                 <li class="treeview">
					<a href="">
						<i class="fa fa-comments-o "></i>
						<span onclick="javascript:window.location.href = './users';">PollChats</span>
						<i class="fa pull-right fa-angle-left"></i>
					</a>
					<ul class="treeview-menu" style="display: none;">
						<li><a href="pages/charts/morris.html" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Published</span></a></li>
						<li><a href="pages/charts/flot.html" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Reported Inappropriate</span></a></li>
						<li><a href="pages/charts/inline.html" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Archived</span></a></li>
					</ul>
				</li>
                -->

                <li class="treeview">
					<a href="">
						<i class="fa fa-user "></i>
						<span onclick="javascript:window.location.href = './dtusers';">Users</span>
						<i id="arrowuser" class="fa pull-right fa-angle-left"></i>
					</a>
					<ul class="treeview-menu" style="display: none;">
						<li><a href="./dtusers" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Active</span></a></li>
						<li><a href="./dtusers?suspended=true" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Suspended</span></a></li>
						<li><a href="./dtusers?archived=true" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Archived</span></a></li>
					</ul>
				</li>
				
            </ul>
            <!--
			<ul class="sidebar-menu" id="adminItems">
				<li class="treeview">
					<a href="">
						<i class="fa fa-key "></i>
						<span onclick="javascript:window.location.href = './dtprivilegedusers?type=admin';">Privileged Users</span>
						<i id="arrowpuser" class="fa pull-right fa-angle-left"></i>
					</a>
					<ul class="treeview-menu" style="display: none;">
						<li><a href="./dtprivilegedusers?type=admin" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Administrators</span></a></li>
						<li><a href="./dtprivilegedusers?type=mod" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Content Moderators</span></a></li>
						<li><a href="./dtprivilegedusers?archived=true" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> <span>Archived</span></a></li>
					</ul>
				</li>
				
				<li class="active">
                    <a href="./wizard">
                        <i class="fa fa-magic"></i> <span id="wizarditem">Section Creation Wizard<sup>BETA</sup></span>
                    </a>
                </li>
			</ul>
			-->
        </div>
    </section>
    <!-- /.sidebar -->
</aside>