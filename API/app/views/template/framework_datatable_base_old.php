<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AdminLTE | Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <?php require 'head_scripts.php'; ?>
        <link href="css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
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
                                    <h3 class="box-title">Data Table With Full Features</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body table-responsive">
                                    <div id="example1_wrapper" class="dataTables_wrapper form-inline" role="grid"><div class="row"><div class="col-xs-6"><div id="example1_length" class="dataTables_length"><label><select size="1" name="example1_length" aria-controls="example1"><option value="10" selected="selected">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> records per page</label></div></div><div class="col-xs-6"><div class="dataTables_filter" id="example1_filter"><label>Search: <input type="text" aria-controls="example1"></label></div></div></div><table id="example1" class="table table-bordered table-striped dataTable" aria-describedby="example1_info">
                                        <thead>
                                            <tr role="row"><th class="sorting" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 296px;" aria-label="Rendering engine: activate to sort column ascending">Rendering engine</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 434px;" aria-label="Browser: activate to sort column ascending">Browser</th><th class="sorting_asc" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 377px;" aria-sort="ascending" aria-label="Platform(s): activate to sort column descending">Platform(s)</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 254px;" aria-label="Engine version: activate to sort column ascending">Engine version</th><th class="sorting" role="columnheader" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" style="width: 181px;" aria-label="CSS grade: activate to sort column ascending">CSS grade</th></tr>
                                        </thead>
                                        
                                        <tfoot>
                                            <tr><th rowspan="1" colspan="1">Rendering engine</th><th rowspan="1" colspan="1">Browser</th><th rowspan="1" colspan="1">Platform(s)</th><th rowspan="1" colspan="1">Engine version</th><th rowspan="1" colspan="1">CSS grade</th></tr>
                                        </tfoot>
                                    <tbody role="alert" aria-live="polite" aria-relevant="all"><tr class="odd">
                                                <td class="">Other browsers</td>
                                                <td class="">All others</td>
                                                <td class=" sorting_1">-</td>
                                                <td class=" ">-</td>
                                                <td class=" ">U</td>
                                            </tr><tr class="even">
                                                <td class="">Misc</td>
                                                <td class="">NetFront 3.4</td>
                                                <td class=" sorting_1">Embedded devices</td>
                                                <td class=" ">-</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="odd">
                                                <td class="">Misc</td>
                                                <td class="">NetFront 3.1</td>
                                                <td class=" sorting_1">Embedded devices</td>
                                                <td class=" ">-</td>
                                                <td class=" ">C</td>
                                            </tr><tr class="even">
                                                <td class="">Misc</td>
                                                <td class="">Dillo 0.8</td>
                                                <td class=" sorting_1">Embedded devices</td>
                                                <td class=" ">-</td>
                                                <td class=" ">X</td>
                                            </tr><tr class="odd">
                                                <td class="">Gecko</td>
                                                <td class="">Epiphany 2.20</td>
                                                <td class=" sorting_1">Gnome</td>
                                                <td class=" ">1.8</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="even">
                                                <td class="">Webkit</td>
                                                <td class="">iPod Touch / iPhone</td>
                                                <td class=" sorting_1">iPod</td>
                                                <td class=" ">420.1</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="odd">
                                                <td class="">KHTML</td>
                                                <td class="">Konqureror 3.1</td>
                                                <td class=" sorting_1">KDE 3.1</td>
                                                <td class=" ">3.1</td>
                                                <td class=" ">C</td>
                                            </tr><tr class="even">
                                                <td class="">KHTML</td>
                                                <td class="">Konqureror 3.3</td>
                                                <td class=" sorting_1">KDE 3.3</td>
                                                <td class=" ">3.3</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="odd">
                                                <td class="">KHTML</td>
                                                <td class="">Konqureror 3.5</td>
                                                <td class=" sorting_1">KDE 3.5</td>
                                                <td class=" ">3.5</td>
                                                <td class=" ">A</td>
                                            </tr><tr class="even">
                                                <td class="">Tasman</td>
                                                <td class="">Internet Explorer 5.1</td>
                                                <td class=" sorting_1">Mac OS 7.6-9</td>
                                                <td class=" ">1</td>
                                                <td class=" ">C</td>
                                            </tr></tbody></table><div class="row"><div class="col-xs-6"><div class="dataTables_info" id="example1_info">Showing 1 to 10 of 57 entries</div></div><div class="col-xs-6"><div class="dataTables_paginate paging_bootstrap"><ul class="pagination"><li class="prev disabled"><a href="#">← Previous</a></li><li class="active"><a href="#">1</a></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li class="next"><a href="#">Next → </a></li></ul></div></div></div></div>
                                </div><!-- /.box-body -->
                            </div>
                        </div>
                    </div>

                </section>
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <?php require 'ending_scripts_datatable.php'; ?>

    </body>
</html>