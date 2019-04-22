<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>资料查看</span>
        </li>
    </ul>
</div>
<div class="row">
    <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
    </div>
    <div class="form-horizontal">
        <div class="ui-page-register-step new_style_margin_b10">基本信息</div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">会员编号：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8 col-xs-8"><p class="form-control-static"
                                      id="__UserCode"><?php echo $output['info']['id']; ?></p></div>
        </div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">注册时间：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static"
                                      id="__UserCode"><?php echo date("Y-m-d H:i:s", $output['info']['time']); ?></p>
            </div>
        </div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">昵称：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static"
                                      id="__NickName"><?php echo $output['info']['username']; ?></p></div>
        </div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">级别：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static"
                                      id="__LevelDisplayName"><?php echo $output['group_name']; ?></p></div>
        </div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">星级：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static"
                                      id="__StarDisplayName"><?php echo $output['info']['star']; ?>星</p></div>
        </div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">报单中心：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8">
                <p class="form-control-static" id="__BizDisplayName">
                    <?php if ($output['info']['is_biz']) { ?>
                        是
                    <?php } else { ?>
                        否
                    <?php } ?>
                </p>
            </div>
        </div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">推荐人：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static"
                                      id="__TJUserCode"><?php echo $output['info']['tjr']; ?></p></div>
        </div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">接点人：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static"
                                      id="__JDUserCode"><?php echo $output['info']['jdr']; ?></p></div>
        </div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">市场：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static"
                                      id="__JDPositionName"><?php echo $output['info']['wz']; ?></p></div>
        </div>
    <span style="display:none;">
    <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">报单中心：</label>
        <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static" id="__ReportCenterUserCode"></p></div>
    </div>
    </span>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">姓名：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static"
                                      id="__RealName"><?php echo $output['info']['name']; ?></p></div>
        </div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">邮箱：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static"
                                      id="__RealName"><?php echo $output['info']['email']; ?></p></div>
        </div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">身份证号码：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static"
                                      id="__ID_Number"><?php echo $output['info']['idcard']; ?></p></div>
        </div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">地址：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static"
                                      id="__Address"><?php echo $output['info']['address']; ?></p></div>
        </div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">手机号：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static"
                                      id="__Mobile"><?php echo $output['info']['tel']; ?></p></div>
        </div>
        <div class="form-group"><label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">QQ：</label>
            <div class="col-sm-10 new_style_padding_none col-xs-8"><p class="form-control-static" id="__QQ"><?php echo $output['info']['qq']; ?></p>
            </div>
        </div>
        <div class="ui-page-register-step new_style_margin_b10">网络关系:</div>
        <div class="form-group">
            <label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">直推人数：
            </label>
            <div class="col-sm-10 new_style_padding_none col-xs-8">
                <p class="form-control-static"><?php echo $output['info']['recount']; ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">团队人数：
            </label>
            <div class="col-sm-10 new_style_padding_none col-xs-8">
                <p class="form-control-static"><?php echo $output['info']['myTeam']; ?></p>
            </div>
        </div>
        <div class="ui-page-register-step new_style_margin_b10">账户余额</div>
        <div class="form-group">
            <label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">积分：
            </label>
            <div class="col-sm-10 new_style_padding_none col-xs-8">
                <p class="form-control-static"><?php echo $output['info']['ji_balance']; ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">报单币：
            </label>
            <div class="col-sm-10 new_style_padding_none col-xs-8">
                <p class="form-control-static"><?php echo $output['info']['bao_balance']; ?></p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">购物币：
            </label>
            <div class="col-sm-10 new_style_padding_none col-xs-8">
                <p class="form-control-static"><?php echo $output['info']['zhang_balance']; ?></p>
            </div>
        </div>
        <!--      <div class="form-group">
       <label class="col-sm-2 new_style_padding_none col-xs-4 text_new_style_r control-label">注册币：
       </label>
       <div class="col-sm-10 new_style_padding_none col-xs-8">
           <p class="form-control-static"><?php echo $output['info']['zhu']; ?></p>
       </div>
   </div> -->
    </div>


</div>