<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>会员奖品列表</h3>
                <h5>会员奖品列表管理</h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li>会员奖品列表管理</li>
        </ul>
    </div>
    <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function () {
        $("#flexigrid").flexigrid({
            url: 'index.php?act=prize&op=get_xml',
            colModel: [
                {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center'},
                {display: '序列ID', name: 'id', width: 100, sortable: true, align: 'left'},
                {display: '会员等级', name: 'group_id', width: 100, sortable: true, align: 'left'},
                {display: '会员名称', name: 'group_name', width: 100, sortable: true, align: 'left'},
                {display: '奖品名称', name: 'prize_name', width: 250, sortable: true, align: 'left'},
                {display: '奖品数量', name: 'prize_num', width: 100, sortable: true, align: 'left'},
                {display: '零售价', name: 'prize_price', width: 200, sortable: true, align: 'left'},
                {display: '零售总价', name: 'prize_money', width: 200, sortable: true, align: 'left'},
                {display: '创建时间', name: 'createtime', width: 150, sortable: true, align: 'left'},
                {display: '状态', name: 'status', width: 200, sortable: true, align: 'left'},
            ],
            buttons: [
                {
                    display: '<i class="fa fa-plus"></i>添加奖品',
                    name: 'share',
                    bclass: 'add',
                    title: '新增数据',
                    onpress: fg_operation
                }
            ],
            // searchitems: [
            //     // {display: '用户名', name: 'group_name'}
            //     //{display: '奖金名称', name: 'prize_name'},
            //     //{display: '日期', name: 'createtime'}
            // ],
            sortname: "id",
            sortorder: "desc",
            title: '会员奖品详情'
        });

    });

    function fg_operation(name, bDiv) {
        if (name == 'share') {
            window.location.href = 'index.php?act=prize&op=share';
        }
        // if (name == 'csv') {
        //     if ($('.trSelected', bDiv).length == 0) {
        //         if (!confirm('您确定要下载全部数据吗？')) {
        //             return false;
        //         }
        //     }
        //     var itemids = new Array();
        //     $('.trSelected', bDiv).each(function (i) {
        //         itemids[i] = $(this).attr('data-id');
        //     });
        //     fg_csv(itemids);
        // }
    }
</script> 

