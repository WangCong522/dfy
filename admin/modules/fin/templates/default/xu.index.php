<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3>奖金拔比</h3>
                <h5>奖金除于收入比例</h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li>不包含母账号admin全部相关金额</li>
            <li>收入总额：所有会员套餐总额</li>
            <!-- <li>奖金：直推奖+量碰奖+见点奖+层碰奖+服务站补贴+分润工资+重消</li> -->
            <li>奖金：直推奖+量碰奖+层碰奖+服务站补贴+分润工资+重消</li>
            <li>当前收入:<span style="color:red;"> <?php echo $output['sum_lsk']; ?></span></li>
            <li>当前奖金总额:<span style="color:red;"> <?php echo $output['sum_award']; ?></span></li>
            <li>奖金拔比:<span style="color:red;"> <?php echo $output['bili']; ?>%</span></li>
        </ul>
    </div>
</div>