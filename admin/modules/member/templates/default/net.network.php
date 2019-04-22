<?php defined('InShopBN') or exit('Access Invalid!');?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="/Public/Home/assets/global/css/css@family=open+sans_3a400,300,600,700&subset=all.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Home/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Home/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Home/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Home/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/Public/Home/assets/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="/Public/Home/assets/global/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="/Public/Home/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Home/assets/layouts/layout/css/themes/darkblue.min.css" rel="stylesheet" type="text/css" id="style_color" />
    <link href="/Public/Home/assets/layouts/layout/css/custom.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="/Public/Home/css/sweetalert.css" />

	
	<link rel="stylesheet" href="/Public/Home/css/jquery.jOrgChart.css" />
    <link rel="stylesheet" href="/Public/Home/css/custom.css" />
    <link rel="stylesheet" href="/Public/Home/css/main.css" />
    <link href="/Public/Home/css/prettify.css" type="text/css" rel="stylesheet" />
<style type="text/css">
    table{
        margin: auto;
    }
	body{ background:#FFFFFF;}
</style>
    <script src="/Public/Home/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="/Public/Home/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/Public/Home/assets/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="/Public/Home/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
    <script src="/Public/Home/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="/Public/Home/assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="/Public/Home/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <script src="/Public/Home/assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
<script src="/Public/Home/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="/Public/Home/assets/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="/Public/Home/assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
    <script src="/Public/Home/assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
    <script src="/Public/Home/assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
    <script src="/Public/Home/js/sweetalert.min.js"></script>

    <!-- END THEME LAYOUT SCRIPTS -->
	<script src="/Public/Home/assets/global/plugins/counterup/jquery.waypoints.min.js" type="text/javascript"></script>
	<script src="/Public/Home/assets/global/plugins/counterup/jquery.counterup.min.js" type="text/javascript"></script>
	<script src="/Public/Home/assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
	<script src="/Public/Home/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
	<script src="/Public/Home/assets/pages/scripts/dashboard.min.js" type="text/javascript"></script>

	
	<script type="text/javascript" src="/Public/Home/js/prettify.js"></script>
    <script type="text/javascript" src="/Public/Home/js/jquery-ui1.8.js"></script>
    <script src="/Public/Home/js/jquery.jOrgChart.js"></script>

    <style type="text/css">
    a {
        cursor: pointer;
        color: yellow;
    }
    
    .red a{
        color: red;
    }
    </style>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <span>组织结构图</span>
            </li>
        </ul>
    </div>
    <div class="row">
         <?php echo $output['html'];?>
        <div id="chart" class="orgChart center-block" style="overflow-x:auto;width: 100%"></div>
    </div>
    <script>
    $('.left').click(function(event) {
        $.post("index.php?act=user&op=MaxCeng", {
            wz: 1
        }, function(data) {
            if (data.status) {
                location.href = "index.php?act=net&op=network&id=" + data.info
            } else {
                alert(data.info);
            }
        },'json');
    });
    $('.right').click(function(event) {
        $.post("index.php?act=user&op=MaxCeng", {
            wz: 2
        }, function(data) {
            if (data.status) {
                location.href = "index.php?act=net&op=network&id=" + data.info
            } else {
                alert(data.info);
            }
        },'json');
    });
    $('.tj').click(function() {
        var num = $('.num').val();
        if (num == '') {
            location.href = "index.php?act=net&op=network";
        }
        $.post("index.php?act=user&op=CheckNum", {
            num: num
        }, function(data) {
            if (data.status) {
                location.href = "index.php?act=net&op=network&id=" + data.info;
            } else {
                alert(data.info);
            }
        },'json');
    })
    $(document).ready(function() {
        $("#org").jOrgChart({
            chartElement: '#chart',
            dragAndDrop: false
        });
    });
    $(document).ready(function() {
        $('.member-form').attr({
            width: '100%',
            border: '1',
            cellpadding: '0',
            cellspacing: '0'
        });
        $("#show-list").click(function(e) {
            e.preventDefault();
            $('#list-html').toggle('fast', function() {
                if ($(this).is(':visible')) {
                    $('#show-list').text('Hide underlying list.');
                    $(".topbar").fadeTo('fast', 0.9);
                } else {
                    $('#show-list').text('Show underlying list.');
                    $(".topbar").fadeTo('fast', 1);
                }
            });
        });
        $('#list-html').text($('#org').html());

        $("#org").bind("DOMSubtreeModified", function() {
            $('#list-html').text('');
            $('#list-html').text($('#org').html());
        });
    });
    </script>
	
	<script>
    jQuery(document).ready(function($) {
        $('.page-content > .row').css({
            marginLeft: '0px',
            marginRight: '0px'
        });
    });
    
    </script>
</body>

</html>