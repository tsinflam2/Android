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
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <?php require 'ending_scripts.php'; ?>

    </body>
</html>