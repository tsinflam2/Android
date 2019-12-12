<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AdminLTE | Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <?php require 'head_scripts.php'; ?>
    </head>
    <body class="skin-black">
        <!-- header logo: style can be found in header.less -->
        <?php require 'header.php'; ?>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <?php require 'menu.php'; ?>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <?php require 'title.php'; ?>
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Hover Data Table</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body table-responsive">
                                    <div id="example2_wrapper" class="dataTables_wrapper form-inline" role="grid"><div class="row"><div class="col-xs-6"></div><div class="col-xs-6"></div></div><table id="example2" class="table table-bordered table-hover dataTable" aria-describedby="example2_info">
                                        <thead>
                                            <tr role="row"><th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Rendering engine</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Browser</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">Platform(s)</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending">Engine version</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">CSS grade</th></tr>
                                        </thead>
                                        
                                        <tfoot>
                                            <tr><th rowspan="1" colspan="1">Rendering engine</th><th rowspan="1" colspan="1">Browser</th><th rowspan="1" colspan="1">Platform(s)</th><th rowspan="1" colspan="1">Engine version</th><th rowspan="1" colspan="1">CSS grade</th></tr>
                                        </tfoot>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all"><tr class="">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Firefox 1.0</td>
                                                <td class=" ">Win 98+ / OSX.2+</td>
                                                <td class=" ">1.7</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="even">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Firefox 1.5</td>
                                                <td class=" ">Win 98+ / OSX.2+</td>
                                                <td class=" ">1.8</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="odd">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Firefox 2.0</td>
                                                <td class=" ">Win 98+ / OSX.2+</td>
                                                <td class=" ">1.8</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="even">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Firefox 3.0</td>
                                                <td class=" ">Win 2k+ / OSX.3+</td>
                                                <td class=" ">1.9</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="odd">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Camino 1.0</td>
                                                <td class=" ">OSX.2+</td>
                                                <td class=" ">1.8</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="even">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Camino 1.5</td>
                                                <td class=" ">OSX.3+</td>
                                                <td class=" ">1.8</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="odd">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Netscape 7.2</td>
                                                <td class=" ">Win 95+ / Mac OS 8.6-9.2</td>
                                                <td class=" ">1.7</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="even">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Netscape Browser 8</td>
                                                <td class=" ">Win 98SE+</td>
                                                <td class=" ">1.7</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="odd">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Netscape Navigator 9</td>
                                                <td class=" ">Win 98+ / OSX.2+</td>
                                                <td class=" ">1.8</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="even">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Mozilla 1.0</td>
                                                <td class=" ">Win 95+ / OSX.1+</td>
                                                <td class=" ">1</td>
                                                <td class=" ">A</td>
                                            </tr></tbody></table><div class="row"><div class="col-xs-6"><div class="dataTables_info" id="example2_info">Showing 1 to 10 of 57 entries</div></div><div class="col-xs-6"><div class="dataTables_paginate paging_bootstrap"><ul class="pagination"><li class="prev disabled"><a href="#">← Previous</a></li><li class="active"><a href="#">1</a></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li class="next"><a href="#">Next → </a></li></ul></div></div></div></div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Data Table With Full Features</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body table-responsive">
                                    <div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid"><div class="row"><div class="col-xs-6"><div id="example1_length" class="dataTables_length"><label><select size="1" name="example1_length" aria-controls="example1"><option value="10" selected="selected">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> records per page</label></div></div><div class="col-xs-6"><div class="dataTables_filter" id="example1_filter"><label>Search: <input type="text" aria-controls="example1"></label></div></div></div><table id="example1" class="table table-bordered table-hover dataTable" aria-describedby="example1_info">
                                        <thead>
                                            <tr role="row"><th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending" style="width: 296px;">Rendering engine</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" style="width: 434px;">Browser</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" style="width: 377px;">Platform(s)</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" style="width: 254px;">Engine version</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending" style="width: 181px;">CSS grade</th></tr>
                                        </thead>
                                        
                                        <tfoot>
                                            <tr><th rowspan="1" colspan="1">Rendering engine</th><th rowspan="1" colspan="1">Browser</th><th rowspan="1" colspan="1">Platform(s)</th><th rowspan="1" colspan="1">Engine version</th><th rowspan="1" colspan="1">CSS grade</th></tr>
                                        </tfoot>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all"><tr class="odd">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Firefox 1.0</td>
                                                <td class=" ">Win 98+ / OSX.2+</td>
                                                <td class=" ">1.7</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="even">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Firefox 1.5</td>
                                                <td class=" ">Win 98+ / OSX.2+</td>
                                                <td class=" ">1.8</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="odd">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Firefox 2.0</td>
                                                <td class=" ">Win 98+ / OSX.2+</td>
                                                <td class=" ">1.8</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="even">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Firefox 3.0</td>
                                                <td class=" ">Win 2k+ / OSX.3+</td>
                                                <td class=" ">1.9</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="odd">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Camino 1.0</td>
                                                <td class=" ">OSX.2+</td>
                                                <td class=" ">1.8</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="even">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Camino 1.5</td>
                                                <td class=" ">OSX.3+</td>
                                                <td class=" ">1.8</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="odd">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Netscape 7.2</td>
                                                <td class=" ">Win 95+ / Mac OS 8.6-9.2</td>
                                                <td class=" ">1.7</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="even">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Netscape Browser 8</td>
                                                <td class=" ">Win 98SE+</td>
                                                <td class=" ">1.7</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="odd">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Netscape Navigator 9</td>
                                                <td class=" ">Win 98+ / OSX.2+</td>
                                                <td class=" ">1.8</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="even">
                                                <td class=" sorting_1">Gecko</td>
                                                <td class=" ">Mozilla 1.0</td>
                                                <td class=" ">Win 95+ / OSX.1+</td>
                                                <td class=" ">1</td>
                                                <td class=" ">A</td>
                                            </tr></tbody></table><div class="row"><div class="col-xs-6"><div class="dataTables_info" id="example1_info">Showing 1 to 10 of 57 entries</div></div><div class="col-xs-6"><div class="dataTables_paginate paging_bootstrap"><ul class="pagination"><li class="prev disabled"><a href="#">← Previous</a></li><li class="active"><a href="#">1</a></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li class="next"><a href="#">Next → </a></li></ul></div></div></div></div>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div>
                    </div>

                </section>
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <?php require 'ending_scripts.php'; ?>

    </body>
</html>