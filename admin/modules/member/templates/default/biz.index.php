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
            url: 'index.php?act=biz&op=get_xml',
            colModel: [
                {display: '操作', name: 'operation', width: 100, sortable: false, align: 'center', className: 'handle'},
                {display: '服务站编号', name: 'id', width: 100, sortable: false, align: 'center'},
                {display: '会员ID', name: 'uid', width: 50, sortable: false, align: 'center'},
                {display: '会员名称', name: 'username', width: 100, sortable: false, align: 'center'},
                {display: '服务站人数', name: 'count', width: 80, sortable: false, align: 'center'},
                {display: '服务站总业绩', name: 'total', width: 80, sortable: false, align: 'center'},
                {display: '申请时间', name: 'addtime', width: 150, sortable: false, align: 'center'},
                {display: '启用', name: 'trun_off', width: 150, sortable: false, align: 'center'},
                {display: '激活', name: 'is_actived', width: 60, sortable: false, align: 'center'}
            ],
            buttons: [
                {
                    display: '<i class="fa fa-plus"></i>新增数据',
                    name: 'biz_add',
                    bclass: 'add',
                    title: '新增数据',
                    onpress: fg_operation
                }
            ],
            sortname: "id",
            sortorder: "desc",
            title: '服务站列表'
        });

    });

    function fg_operation(name, bDiv) {
        if (name == 'biz_add') {
            window.location.href = 'index.php?act=biz&op=biz_add';
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

