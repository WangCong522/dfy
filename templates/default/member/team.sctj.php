<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>市场统计 团队人数:<b><?php echo $output['myTeamNum'] ?></b></span>
        </li>
    </ul>
</div>
<div class="row">
    <div class="form-horizontal">
        <div id="container" style="height:400px; width:98%;"></div>
    </div>
</div>
<script type="text/javascript" src="/resource/js/highcharts.js"></script>
<script>
    $(function () {
        $('#container').highcharts(<?php echo $output['sctj_stat_json'];?>);
    });
</script>