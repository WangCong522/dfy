<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>财务明细</h3>
                <h5>会员财务明细记录</h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li>可以按用户名、种类、交易方式查询</li>
        </ul>
    </div>
    <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function () {
        $("#flexigrid").flexigrid({
            url: 'index.php?act=<?php echo $output['act'];?>&op=get_xml',
            colModel: [
                /*{display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},*/
                {display: '时间', name: 'date', width: 150, sortable: true, align: 'left'},
                {display: '用户名', name: 'username', width: 100, sortable: true, align: 'left'},
                {display: '种类', name: 'money_type', width: 100, sortable: true, align: 'left'},
                {display: '金额', name: 'money', width: 100, sortable: true, align: 'left'},
                {display: '交易方式', name: 'type', width: 150, sortable: true, align: 'left'},
                {display: '备注', name: 'intro', width: 200, sortable: true, align: 'left'},
            ],
            buttons: [],
            searchitems: [
                {display: '用户名', name: 'username'},
                {display: '种类', name: 'money_type'},
                {display: '交易方式', name: 'type'}
            ],
            sortname: "id",
            sortorder: "desc",
            title: '财务明细'
        });

    });

    function fg_operation(name, bDiv) {
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

