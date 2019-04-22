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
        <div>
        <?php
			   if(is_array($output['artList'])){
				   foreach($output['artList'] as $val){
		   ?>
            <div style="border-bottom:1px dashed #ccc;padding-bottom:5px;margin-bottom:10px;">
                <div class="pull-left">
                    <a href="index.php?act=article&op=show&id=<?php echo $val['aid']?>"><?php echo $val['title'];?></a>
                </div>
                <div class="pull-right" style="text-align: right;color:#808080;"><?php echo date("Y-m-d",$val['addtime']);?></div>
                <div class="clearfix"></div>
            </div>
        <?php
				}
			}
		?>
        </div>
    </div>