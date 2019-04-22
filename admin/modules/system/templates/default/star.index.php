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
<!--        <button onclick="location.href='index.php?act=cs&op=starAdd'">添加星级</button>-->
        <ul>
            <li><?php echo $lang['article_index_help1']; ?></li>
        </ul>
    </div>
    <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function () {
        $("#flexigrid").flexigrid({
            url: 'index.php?act=cs&op=starGetXml',
            colModel: [
                {display: '操作', name: 'operation', width: 140, sortable:
                        false, align: 'center'},
                {display: '星级名称', name: 'name', width: 80, sortable: false, align: 'center'},
                {display: '星级等级', name: 'level', width: 80, sortable: 'level', align: 'center'},
                {display: '升级条件(人数)', name: 'con_star', width: 740, sortable:
                        false, align: 'center'},
                {display: '升级条件(PV)', name: 'con_pv', width: 80, sortable:
                        false, align: 'center'},
                {display: '极差比率', name: 'jicha_rate', width: 60, sortable:
                        false, align: 'center'},
                {display: '全球分红比率', name: 'bonus_rate', width: 75, sortable:
                        false, align: 'center'},
                {display: '下一星级名称', name: 'next_star', width: 80, sortable:
                        false, align: 'center'},
                {display: '更新时间', name: 'updated_at', width: 180, sortable:
                        false, align: 'center'},
            ],
            sortname: "id",
            sortorder: "asc",
            title: '星级'
        });
    });
</script>
