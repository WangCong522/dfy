<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?php echo $lang['member_index_manage'] ?></h3>
                <h5><?php echo $lang['member_shop_manage_subhead'] ?></h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li><?php echo $lang['member_index_help1']; ?></li>
            <li><?php echo $lang['member_index_help2']; ?></li>
        </ul>
    </div>
    <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function () {
        $("#flexigrid").flexigrid({
            url: 'index.php?act=member&op=get_xml',
            colModel: [
                {display: '操作', name: 'operation', width: 240, sortable: false, align: 'center'},
                {display: '会员ID', name: 'num', width: 50, sortable: true, align: 'center'},
                {display: '会员名称', name: 'username', width: 100, sortable: true, align: 'center'},
                {display: '会员等级', name: 'group_name', width: 100, sortable: true, align: 'center'},
                {display: '会员星级', name: 'star', width: 100, sortable: true, align: 'center'},
                {display: '姓名', name: 'name', width: 80, sortable: true, align: 'center'},
                {display: '电话', name: 'tel', width: 80, sortable: true, align: 'center'},
                {display: '激活', name: 'is_actived', width: 60, sortable: true, align: 'center'},
                {display: '报单币', name: 'bao_balance', width: 80, sortable: true, align: 'center'},
                {display: '积分', name: 'dian_balance', width: 80, sortable: true, align: 'center'},
                {display: '购物币', name: 'xu_balance', width: 80, sortable: true, align: 'center'},
                {display: '注册时间', name: 'time', width: 120, sortable: true, align: 'center'}
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
            searchitems: [
                {display: '会员名称', name: 'username'},
                {display: '编号', name: 'num'}
            ],
            sortname: "id",
            sortorder: "desc",
            title: '会员列表'
        });

    });

    function fg_operation(name, bDiv) {
        if (name == 'add') {
            window.location.href = 'index.php?act=member&op=member_add';
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

