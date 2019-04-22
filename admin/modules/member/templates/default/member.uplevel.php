<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="index.php?act=member&op=member" title="返回列表"><i
                    class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3><?php echo $lang['member_index_manage'] ?> - <?php echo $lang['nc_new'] ?>会员</h3>
                <h5><?php echo $lang['member_shop_manage_subhead'] ?></h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li>后台升级会员</li>
        </ul>
    </div>
    <form id="user_form" enctype="multipart/form-data" method="post">
        <input type="hidden" name="form_submit" value="ok"/>
        <input type="hidden" name="uid" value="<?php echo $output['userinfo']['id'] ?>"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="member_name">会员等级</label>
                </dt>
                <dd class="opt">
                    <?php echo $output['userinfo']['group_name'] ?>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="member_passwd">升级为</label>
                </dt>
                <dd class="opt">
                    <select class="form-control edited" id="group_id" name="group_id">
                        <?php foreach ($output['groupList'] as $item) { ?>
                            <option value="<?php echo $item['group_id'] ?>" date-rel="<?php echo $item['lsk'] ?>">
                                <?php echo $item['name'] ?>--(￥<?php echo $item['lsk'] ?>)
                            </option>
                        <?php } ?>
                    </select>
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
                username: {
                    required: true,
                    minlength: 3,
                    maxlength: 20,
                    remote: {
                        url: 'index.php?act=member&op=ajax&branch=check_user_name',
                        type: 'get',
                        data: {
                            username: function () {
                                return $('#username').val();
                            }
                        }
                    }
                },
                password: {
                    required: true,
                    maxlength: 20,
                    minlength: 6
                },
                member_email: {
                    required: true,
                    email: true,
                    remote: {
                        url: 'index.php?act=member&op=ajax&branch=check_email',
                        type: 'get',
                        data: {
                            user_name: function () {
                                return $('#member_email').val();
                            },
                            member_id: '<?php echo $output['member_array']['member_id'];?>'
                        }
                    }
                },
                member_qq: {
                    digits: true,
                    minlength: 5,
                    maxlength: 11
                }
            },
            messages: {
                member_name: {
                    required: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_add_name_null']?>',
                    maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_add_name_length']?>',
                    minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_add_name_length']?>',
                    remote: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_add_name_exists']?>'
                },
                member_passwd: {
                    required: '<i class="fa fa-exclamation-circle"></i><?php echo '密码不能为空'; ?>',
                    maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>',
                    minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>'
                },
                member_email: {
                    required: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_email_null']?>',
                    email: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_valid_email']?>',
                    remote: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_email_exists']?>'
                },
                member_qq: {
                    digits: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_qq_wrong']?>',
                    minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_qq_wrong']?>',
                    maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_qq_wrong']?>'
                }
            }
        });
    });
</script> 
