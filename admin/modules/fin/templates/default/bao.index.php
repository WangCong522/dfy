<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>奖金明细</h3>
                <h5>会员奖金明细记录</h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li>可以按用户名、日期方式查询</li>
        </ul>
    </div>
    <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function () {
        $("#flexigrid").flexigrid({
            url: 'index.php?act=<?php echo $output['act'];?>&op=get_xml',
            colModel: [
                {display: '用户ID', name: 'uid', width: 50, sortable: true, align: 'center'},
                {display: '日期', name: 'bdate', width: 100, sortable: true, align: 'center'},
                {display: '用户名', name: 'username', width: 100, sortable: true, align: 'center'},
                {display: '直推奖', name: 'b1', width: 50, sortable: true, align: 'center'},
                // {display: '见点奖', name: 'b3', width: 50, sortable: true, align: 'center'},
                {display: '量碰奖', name: 'b2', width: 50, sortable: true, align: 'center'},
                {display: '层碰奖', name: 'b11', width: 50, sortable: true, align: 'center'},
                {display: '分润工资', name: 'b12', width: 50, sortable: true, align: 'center'},
                //{display: '培育奖', name: 'b4', width: 50, sortable: true, align: 'center'},
                //{display: '感恩奖', name: 'b10', width: 50, sortable: true, align: 'center'},
                {display: '购物币奖励', name: 'b7', width: 50, sortable: true, align: 'center'},
                {display: '报单中心补贴', name: 'b5', width: 80, sortable: true, align: 'center'},
                //{display: '服务站推荐', name: 'b6', width: 80, sortable: true, align: 'center'},
                //{display: '税费', name: 'b8', width: 50, sortable: true, align: 'center'},
                //{display: '慈善基金', name: 'b9', width: 50, sortable: true, align: 'center'},
                {display: '总计', name: 'b0', width: 50, sortable: true, align: 'center'}
            ],
            buttons: [],
            searchitems: [
                {display: '用户名', name: 'username'},
                {display: '日期', name: 'bdate'}
            ],
            sortname: "uid",
            sortorder: "asc",
            title: '奖金明细'
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

