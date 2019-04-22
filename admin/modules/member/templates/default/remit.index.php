<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>汇款</h3>
                <h5>会员的汇款管理与查看</h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li>可以查看并管理汇款</li>
        </ul>
    </div>
    <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function () {
        $("#flexigrid").flexigrid({
            url: 'index.php?act=remit&op=get_xml',
            colModel: [
                {display: '用户名', name: 'username', width: 100, sortable: true, align: 'left'},
                {display: '方式', name: 'czfs', width: 40, sortable: true, align: 'left'},
                {display: '账号', name: 'zhanghao', width: 150, sortable: true, align: 'left'},
                {display: '户名', name: 'kaihuiming', width: 120, sortable: true, align: 'left'},
                {display: '金额', name: 'jine', width: 50, sortable: true, align: 'left'},
                {display: '汇款账号', name: 'hkzh', width: 300, sortable: true, align: 'left'},
                {display: '时间', name: 'date', width: 100, sortable: true, align: 'left'},
                {display: '电话', name: 'tel', width: 100, sortable: true, align: 'left'},
                {display: '备注', name: 'intro', width: 300, sortable: true, align: 'left'}
            ],
            buttons: [],
            searchitems: [
                {display: '标题', name: 'title'},
                {display: '收件人ID', name: 'toid'}
            ],
            sortname: "id",
            sortorder: "desc",
            title: '消息列表'
        });

    });

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

    function fg_csv(ids) {
        id = ids.join(',');
        window.location.href = $("#flexigrid").flexSimpleSearchQueryString() + '&op=export_csv&id=' + id;
    }
</script> 

