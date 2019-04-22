<?php defined('InShopBN') or exit('Access Invalid!');?>
    <link rel="stylesheet" href="/Public/Home/css/jquery.jOrgChart.css" />
    <link rel="stylesheet" href="/Public/Home/css/custom.css" />
    <link rel="stylesheet" href="/Public/Home/css/main.css" />
    <link href="/Public/Home/css/prettify.css" type="text/css" rel="stylesheet" />
<style type="text/css">
    table{
        margin: auto;
    }
</style>

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
                <a href="/">首页</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>组织结构图</span>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label>*友情提示：输入框中，不输入即为抵达网络图顶端</label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control num" placeholder="输入编号可到达指定用户">
                <span class="input-group-btn">
                                                        <button class="btn green tj" type="button">提交</button>
                                                    </span>
            </div>
            <!-- /input-group -->
        </div>
        <div class="form-group col-md-4">
            <label>&nbsp;</label>
            <div class="input-group input-group-sm">
                <span class="input-group-btn">
                                                        <button type="button" class="btn btn-success mt-ladda-btn ladda-button left" data-style="expand-up">
                                                    <span class="ladda-label">直达左区最底层</span>
                <span class="ladda-spinner"></span></button>
                </span>
                <span class="input-group-btn">
                                                        <button type="button" class="btn btn-success mt-ladda-btn ladda-button right" data-style="expand-up">
                                                    <span class="ladda-label">直达右区最底层</span>
                <span class="ladda-spinner"></span></button>
                </span>
            </div>
        </div>
        <br/>
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
        $(document).on('click', '.add', function() {
            var id = $(this).attr('data-id');
            swal({
                    title: "您确定吗?",
                    text: "风险提示：公司强烈要求在自己承受的范围内合理消费。坚决反对投机行为。拒绝一人多单。[投资有风险，消费需谨慎]",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "确定",
                    cancelButtonText: "取消",
                    closeOnConfirm: false,
                },
                function() {
                    $.post("index.php?act=user&op=UserStatus", {
                        id: id
                    }, function(data) {
                        if (data.status) {
                            location.href = "index.php?act=team&op=register&id=" + id + "";
                        } else {
                            alert('该用户尚未激活,不能添加下级');
                            return false;
                        }
                    },'json');

                });
        });
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