<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?php echo $lang['upload_set']; ?></h3>
                <h5><?php echo $lang['upload_set_subhead']; ?></h5>
            </div>
            <?php echo $output['top_link']; ?> </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li><?php echo $lang['article_index_help1']; ?></li>
        </ul>
    </div>
    <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function () {
        $("#flexigrid").flexigrid({
            url: 'index.php?act=cs&op=groupGetXml',
            colModel: [
                {display: '操作', name: 'operation', width: 160, sortable: false, align: 'center'},
                {display: '等级', name: 'name', width: 80, sortable: false, align: 'center'},
                {display: '报单费', name: 'lsk', width: 80, sortable: false, align: 'center'},
                //{display: '报单配送', name: 'bdps', width: 80, sortable: false, align: 'center'},
                {display: '直推奖', name: 'tj', width: 80, sortable: false, align: 'center'},
                {display: '量碰奖', name: 'dpj', width: 80, sortable: false, align: 'center'},
                {display: '量碰奖日封顶', name: 'dpj_top', width: 80, sortable: false, align: 'center'},
                {display: '层碰奖', name: 'cpj', width: 80, sortable: false, align: 'center'},
                // {display: '见点奖', name: 'jiandian', width: 80, sortable: false, align: 'center'},
                //{display: '培育奖(%)', name: 'lead', width: 80, sortable: false, align: 'center'},
                //{display: '感恩奖(%)', name: 'gej', width: 80, sortable: false, align: 'center'},
                {display: '服务站补贴', name: 'subsidy', width: 90, sortable: false, align: 'center'},
                {display: '购物币兑换(%)', name: 'cfxf', width: 80, sortable: false, align: 'center'},
                {display: '积分消费折扣(%)', name: 'jfzk', width: 80, sortable: false, align: 'center'},
                {display: '重消比例(%)', name: 're_consum_reward', width: 80, sortable: false, align: 'center'},
                //{display: '慈善基金(%)', name: 'fund', width: 80, sortable: false, align: 'center'}
            ],
            sortname: "group_id",
            sortorder: "asc",
            title: '会员等级'
        });
    });
</script>
