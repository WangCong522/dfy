<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>积分折扣</h3>
                <h5>积分折扣的编辑与查看</h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li>可以查看并管理积分折扣</li>
        </ul>
    </div>
    <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function () {
        $("#flexigrid").flexigrid({
            url: 'index.php?act=jfzk&op=get_xml',
            colModel: [
                {display: '操作', name: 'operation', width: 200, sortable: false, align: 'center'},
                {display: '序列ID', name: 'id', width: 100, sortable: true, align: 'center'},
                {display: '会员等级', name: 'groud_id', width: 150, sortable: false, align: 'left'},
                {display: '会员', name: 'group_name', width: 150, sortable: false, align: 'left'},
                {display: '折扣率', name: 'rate', width: 150, sortable: false, align: 'left'},
                {display: '时间', name: 'createtime', width: 150, sortable: true, align: 'left'},
                {display: '是否启用', name: 'status', width: 150, sortable: false, align: 'left'},
            ],
            buttons: [
                {
                    display: '<i class="fa fa-plus"></i>新增数据',
                    name: 'add',
                    bclass: 'add',
                    title: '新增数据',
                    onpress: fg_operation
                }
            ],
    
            // searchitems: [
            //     {display: '名称', name: 'title'},
            // ],
            sortname: "id",
            sortorder: "desc",
            title: '积分折扣'
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
            window.location.href = 'index.php?act=jfzk&op=message_add';
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

