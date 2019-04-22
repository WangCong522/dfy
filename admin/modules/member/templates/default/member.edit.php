<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="index.php?act=member&op=member" title="返回列表"><i
                    class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3><?php echo $lang['member_index_manage'] ?> - <?php echo $lang['nc_edit'] ?>
                    会员“<?php echo $output['member_array']['username']; ?>”</h3>
                <h5>会员信息</h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li>1、密码如果不修改，请留空</li>
            <li>2、如果不加减资金，请留空，增加时填正数，减少时填负数即可</li>
        </ul>
    </div>
    <form id="user_form" enctype="multipart/form-data" method="post">
        <input type="hidden" name="form_submit" value="ok"/>
        <input type="hidden" name="id" value="<?php echo $output['member_array']['id']; ?>"/>
        <input type="hidden" name="username" value="<?php echo $output['member_array']['username']; ?>"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label><?php echo $lang['member_index_name'] ?></label>
                </dt>
                <dd class="opt">
                    <?php echo $output['member_array']['username']; ?>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>会员ID</label>
                </dt>
                <dd class="opt">
                    <?php echo $output['member_array']['id']; ?>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="member_passwd"><?php echo $lang['member_edit_password'] ?></label>
                </dt>
                <dd class="opt">
                    <input type="text" id="password" name="password" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"><?php echo $lang['member_edit_password_keep'] ?></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="member_passwd">二级密码</label>
                </dt>
                <dd class="opt">
                    <input type="text" id="jy_pwd" name="jy_pwd" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"><?php echo $lang['member_edit_password_keep'] ?></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="member_email">奖金加减</label>
                </dt>
                <dd class="opt">
                    <select name="type">
                        <option value="bao">报单币</option>
                        <option value="ji">积分</option>
                        <option value="zhang">购物币</option>
                    </select>
                    <input type="text" value="" id="amount" name="amount" class="input-txt" style="width:10px;!import">
                    <span class="err"></span>
                    <p class="notic">如不操作，请留空或为0</p>
                </dd>
            </dl>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green"
                                id="submitBtn"><?php echo $lang['nc_submit']; ?></a></div>
            <dl class="row">
                <dt class="tit">
                    <label>报单币余额：</label>
                </dt>
                <dd class="opt"><strong
                        class="red"><?php echo $output['member_array']['bao_balance']; ?></strong>&nbsp;<?php echo $lang['currency_zh']; ?>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>积分余额：</label>
                </dt>
                <dd class="opt"><strong
                        class="red"><?php echo $output['member_array']['ji_balance']; ?></strong>&nbsp;<?php echo $lang['currency_zh']; ?>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>购物币余额：</label>
                </dt>
                <dd class="opt"><strong
                        class="red"><?php echo $output['member_array']['zhang_balance']; ?></strong>&nbsp;<?php echo $lang['currency_zh']; ?>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>左市场：</label>
                </dt>
                <dd class="opt"><strong><?php echo $output['net']['l_count']; ?></strong></dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>右市场：</label>
                </dt>
                <dd class="opt"><strong><?php echo $output['net']['r_count']; ?></strong></dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>姓名：</label>
                </dt>
                <dd class="opt"><strong><?php echo $output['member_array']['name']; ?></strong></dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>电话：</label>
                </dt>
                <dd class="opt"><strong><?php echo $output['member_array']['tel']; ?></strong></dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>QQ：</label>
                </dt>
                <dd class="opt"><strong><?php echo $output['member_array']['qq']; ?></strong></dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>Email：</label>
                </dt>
                <dd class="opt"><strong><?php echo $output['member_array']['email']; ?></strong></dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>身份证：</label>
                </dt>
                <dd class="opt"><strong><?php echo $output['member_array']['idcard']; ?></strong></dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>地址：</label>
                </dt>
                <dd class="opt"><strong><?php echo $output['member_array']['address']; ?></strong></dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>密保：</label>
                </dt>
                <dd class="opt"><strong><?php echo $output['member_array']['problem']; ?></strong></dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>答案：</label>
                </dt>
                <dd class="opt"><strong><?php echo $output['member_array']['answer']; ?></strong></dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>状态：</label>
                </dt>
                <dd class="opt">
                    <strong><?php echo ($output['member_array']['status'] == 1) ? "<font color=green>激活</font>" : "<font color=red>未激活</font>"; ?></strong>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>注册时间：</label>
                </dt>
                <dd class="opt"><strong><?php echo date("Y-m-d H:i:s", $output['member_array']['time']); ?></strong>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>到期时间：</label>
                </dt>
                <dd class="opt">
                    <strong><?php echo date("Y-m-d H:i:s", $output['member_array']['overdue_time']); ?></strong></dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label>级别：</label>
                </dt>
              
                <dd class="opt">				                        <?php foreach ($output['groupList'] as $item) { ?>                           <?php if($output['member_array']['group_id']==$item['group_id']){?>                                <?php echo $item['name'] ?>--(￥<?php echo $item['lsk'] ?>)                                                    <?php } }?>									</dd>
            </dl>


        </div>
    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL; ?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
    //裁剪图片后返回接收函数
    function call_back(picname) {
        $('#member_avatar').val(picname);
        $('#view_img').attr('src', '<?php echo UPLOAD_SITE_URL . '/' . ATTACH_AVATAR;?>/' + picname + '?' + Math.random())
            .attr('onmouseover', 'toolTip("<img src=<?php echo UPLOAD_SITE_URL . '/' . ATTACH_AVATAR;?>/' + picname + '?' + Math.random() + '>")');
    }
    $(function () {
        $('input[class="type-file-file"]').change(uploadChange);
        function uploadChange() {
            var filepath = $(this).val();
            var extStart = filepath.lastIndexOf(".");
            var ext = filepath.substring(extStart, filepath.length).toUpperCase();
            if (ext != ".PNG" && ext != ".GIF" && ext != ".JPG" && ext != ".JPEG") {
                alert("file type error");
                $(this).attr('value', '');
                return false;
            }
            if ($(this).val() == '') return false;
            ajaxFileUpload();
        }

        function ajaxFileUpload() {
            $.ajaxFileUpload
            (
                {
                    url: '<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=pic_upload&form_submit=ok&uploadpath=<?php echo ATTACH_AVATAR;?>',
                    secureuri: false,
                    fileElementId: '_pic',
                    dataType: 'json',
                    success: function (data, status) {
                        if (data.status == 1) {
                            ajax_form('cutpic', '<?php echo $lang['nc_cut'];?>', '<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=pic_cut&type=member&x=120&y=120&resize=1&ratio=1&filename=<?php echo UPLOAD_SITE_URL . '/' . ATTACH_AVATAR;?>/avatar_<?php echo $_GET['member_id'];?>.jpg&url=' + data.url, 690);
                        } else {
                            alert(data.msg);
                        }
                        $('input[class="type-file-file"]').bind('change', uploadChange);
                    },
                    error: function (data, status, e) {
                        alert('上传失败');
                        $('input[class="type-file-file"]').bind('change', uploadChange);
                    }
                }
            )
        };
// 点击查看图片
        $('.nyroModal').nyroModal();

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
                member_passwd: {
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
                }
            },
            messages: {
                member_passwd: {
                    maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>',
                    minlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_password_tip']?>'
                },
                member_email: {
                    required: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_email_null']?>',
                    email: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_valid_email']?>',
                    remote: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_edit_email_exists']?>'
                }
            }
        });
    });
</script> 
