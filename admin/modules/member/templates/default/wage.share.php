<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="index.php?act=member&op=member" title="返回列表"><i
                    class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>分润工资</h3>
                <h5>分润工资</h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li>选择星级</li>
            <li>输入金额</li>
        </ul>
    </div>
    <form id="user_form" enctype="multipart/form-data" method="post">
        <input type="hidden" name="form_submit" value="ok"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="member_passwd">选择星级</label>
                </dt>
                <dd class="opt">
                    <select class="form-control edited" id="star" name="star">
<!--                        --><?php //foreach ($output['starSetting'] as $key => $value) { ?>
<!--                            <option value="--><?php //echo $value ?><!--">-->
<!--                                --><?php //echo $value ?><!--星-->
<!--                            </option>-->
<!--                        --><?php //} ?>

                        <?php foreach ($output['starSetting'] as $key => $value) { ?>
                            <option value="<?php echo $key ?>">
                                <?php

                                echo "各".$value  ?><?php
                                if($key==1)
                                    echo "个普通会员";
                                elseif($key==2)
                                    echo "个1星会员";
                                elseif($key==3)
                                    echo "个2星会员";
                                elseif($key==4)
                                    echo "个3星会员";
                                elseif($key==5)
                                    echo "个4星会员";
                                echo $key."星";
                                ?>
                            </option>
                        <?php } ?>




                    </select>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="member_name">发放金额</label>
                </dt>
                <dd class="opt">
                    <input type="text" name="money" id="money" value="">
                    <span class="err"></span>
                </dd>
            </dl>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green"
                                id="submitBtn"><?php echo $lang['nc_submit']; ?></a></div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
    $(function () {
        //按钮先执行验证再提交表单
        $("#submitBtn").click(function () {
            if ($("#user_form").valid()) {
                $("#user_form").submit();
            }
        });
        $('#user_form').validate({
            errorPlacement: function (error, element) {
                var error_td = element.parent('dd').children('span.err');
                error_td.append(error);
            },
            rules: {
                money: {
                    required: true,
                    digits: true
                }
            },
            messages: {
                money: {
                    digits: '<i class="fa fa-exclamation-circle"></i>必须为整数'
                }
            }
        });
    });
</script> 
