<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>提现</h3>
                <h5>会员的提现管理与查看</h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li>可以查看并管理提现</li>
        </ul>
    </div>
    <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function () {
        $("#flexigrid").flexigrid({
            url: 'index.php?act=remit_tx&op=get_xml',
            colModel: [
                {display: '用户名', name: 'username', width: 100, sortable: true, align: 'left'},
                {display: '方式', name: 'czfs', width: 100, sortable: true, align: 'left'},
                {display: '账号', name: 'zhanghao', width: 150, sortable: true, align: 'left'},
                {display: '户名', name: 'kaihuiming', width: 120, sortable: true, align: 'left'},
                {display: '金额', name: 'jine', width: 50, sortable: true, align: 'left'},
                {display: '手续费', name: 'con_tx_shouxu', width: 50, sortable: true, align: 'left'},
                {display: '实发', name: 'sjjine', width: 50, sortable: true, align: 'left'},
                {display: '时间', name: 'date', width: 100, sortable: true, align: 'left'},
                {display: '电话', name: 'tel', width: 100, sortable: true, align: 'left'},
                {display: '是否通过', name: 'is_actived', width: 100, sortable: true, align: 'left'},
                {display: '操作', name: 'intro', width: 300, sortable: true, align: 'left'},
                {display: '备注', name: 'intro', width: 300, sortable: true, align: 'left'}
            ],
            buttons: [
                {
                    display: '<i class="fa fa-file-excel-o"></i>导出数据',
                    name: 'csv',
                    bclass: 'csv',
                    title: '将选定行数据导出excel文件,如果不选中行，将导出列表所有数据',
                    onpress: fg_operate
                }
            ],
            searchitems: [
                {display: '标题', name: 'title'},
                {display: '收件人ID', name: 'toid'}
            ],
            sortname: "id",
            sortorder: "desc",
            title: '提现列表'
        });

    });
    function fg_operate(name, grid) {
        if (name == 'csv') {
            var itemlist = new Array();
            if ($('.trSelected', grid).length > 0) {
                $('.trSelected', grid).each(function () {
                    itemlist.push($(this).attr('data-id'));
                });
            }
            fg_csv(itemlist);
        }
    }
    function fg_csv(ids) {
        id = ids.join(',');
        window.location.href = $("#flexigrid").flexSimpleSearchQueryString() + '&op=export_step1&id=' + id;
    }
    function fg_operation(name, bDiv) {
        if (name == 'add') {
            window.location.href = 'index.php?act=inbox&op=message_add';
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

