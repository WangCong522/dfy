<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?php echo $lang['biz_index_manage'] ?></h3>
                <h5><?php echo $lang['biz_shop_manage_subhead'] ?></h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li><?php echo $lang['biz_index_help1']; ?></li>
        </ul>
    </div>
    <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function () {
        $("#flexigrid").flexigrid({
            url: 'index.php?act=wage&op=get_xml',
            colModel: [
                {display: '时间', name: 'time', width: 150, sortable: true, align: 'left'},
                {display: '用户名ID', name: 'uid', width: 100, sortable: true, align: 'left'},
                {display: '用户名', name: 'username', width: 100, sortable: true, align: 'left'},
                {display: '奖金名称', name: 'money_type', width: 100, sortable: true, align: 'left'},
                {display: '金额', name: 'money', width: 100, sortable: true, align: 'left'},
                {display: '描述', name: 'intro', width: 200, sortable: true, align: 'left'},
                {display: '相关会员', name: 'rel_username', width: 200, sortable: true, align: 'left'}
            ],
            buttons: [
                {
                    display: '<i class="fa fa-plus"></i>开始分红',
                    name: 'share',
                    bclass: 'add',
                    title: '新增数据',
                    onpress: fg_operation
                }
            ],
            searchitems: [
                {display: '用户名', name: 'username'}
                //{display: '奖金名称', name: 'money_type'},
                //{display: '日期', name: 'time'}
            ],
            sortname: "id",
            sortorder: "desc",
            title: '分润工资详情'
        });

    });

    function fg_operation(name, bDiv) {
        if (name == 'share') {
            window.location.href = 'index.php?act=wage&op=share';
        }
        if (name == 'csv') {
            if ($('.trSelected', bDiv).length == 0) {
                if (!confirm('您确定要下载全部数据吗？')) {
                    return false;
                }
            }
            var itemids = new Array();
            $('.trSelected', bDiv).each(function (i) {
                itemids[i] = $(this).attr('data-id');
            });
            fg_csv(itemids);
        }
    }
</script> 

