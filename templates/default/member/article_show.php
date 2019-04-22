<?php defined('InShopBN') or exit('Access Invalid!');?>
<div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">首页</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span><?php echo $output['article_class']['typename']?></span>
            </li>
        </ul>
    </div>
    <div class="row ">
        <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
        </div>
        <div style="text-align: center;">
            <h2><?php echo $output['article']['title'];?></h2>
            <h4 style="color: #aaa; border-bottom: 1px solid #ccc; padding: 10px 0;"><?php echo date("Y-m-d H:i",$output['article']['addtime']);?></h4>
            <?php echo $output['article']['content'];?>
        </div>
    </div>