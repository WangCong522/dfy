<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?php echo $lang['reg_cs_set']; ?></h3>
                <h5><?php echo $lang['reg_cs_set_subhead']; ?></h5>
            </div>
            <?php echo $output['top_link']; ?> </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li>打开时，前台注册时会出现</li>
        </ul>
    </div>
    <form id="form" method="post" name="settingForm">
        <input type="hidden" name="form_submit" value="ok"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label>自动生成用户名</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="reg_auto_username_1"
                               class="cb-enable <?php if ($output['setting']['reg_auto_username'] == '1') { ?>selected<?php } ?>"
                               title="<?php echo $lang['nc_open']; ?>"><?php echo $lang['nc_open']; ?></label>
                        <label for="reg_auto_username_0"
                               class="cb-disable <?php if ($output['setting']['reg_auto_username'] == '0') { ?>selected<?php } ?>"
                               title="<?php echo $lang['nc_close']; ?>"><?php echo $lang['nc_close']; ?></label>
                        <input type="radio" id="reg_auto_username_1" name="reg_auto_username"
                               value="1" <?php echo $output['setting']['reg_auto_username'] == 1 ? 'checked=checked' : ''; ?>>
                        <input type="radio" id="reg_auto_username_0" name="reg_auto_username"
                               value="0" <?php echo $output['setting']['reg_auto_username'] == 0 ? 'checked=checked' : ''; ?>>
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>自动生成用户名前缀</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <input type="text" name="reg_auto_username_prefix"
                               value="<?php echo $output['setting']['reg_auto_username_prefix']; ?>">
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label>身份证</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="reg_idcard_key_1"
                               class="cb-enable <?php if ($output['setting']['reg_idcard_key'] == '1') { ?>selected<?php } ?>"
                               title="<?php echo $lang['nc_open']; ?>"><?php echo $lang['nc_open']; ?></label>
                        <label for="reg_idcard_key_0"
                               class="cb-disable <?php if ($output['setting']['reg_idcard_key'] == '0') { ?>selected<?php } ?>"
                               title="<?php echo $lang['nc_close']; ?>"><?php echo $lang['nc_close']; ?></label>
                        <input type="radio" id="reg_idcard_key_1" name="reg_idcard_key"
                               value="1" <?php echo $output['setting']['reg_idcard_key'] == 1 ? 'checked=checked' : ''; ?>>
                        <input type="radio" id="reg_idcard_key_0" name="reg_idcard_key"
                               value="0" <?php echo $output['setting']['reg_idcard_key'] == 0 ? 'checked=checked' : ''; ?>>
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">地址</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="reg_addr_1"
                               class="cb-enable <?php if ($output['setting']['reg_addr_key'] == '1') { ?>selected<?php } ?>"
                               title="<?php echo $lang['nc_open']; ?>"><?php echo $lang['nc_open']; ?></label>
                        <label for="reg_addr_0"
                               class="cb-disable <?php if ($output['setting']['reg_addr_key'] == '0') { ?>selected<?php } ?>"
                               title="<?php echo $lang['nc_close']; ?>"><?php echo $lang['nc_close']; ?></label>
                        <input type="radio" id="reg_addr_1" name="reg_addr_key"
                               value="1" <?php echo $output['setting']['reg_addr_key'] == 1 ? 'checked=checked' : ''; ?>>
                        <input type="radio" id="reg_addr_0" name="reg_addr_key"
                               value="0" <?php echo $output['setting']['reg_addr_key'] == 0 ? 'checked=checked' : ''; ?>>
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">电话</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="reg_tel_1"
                               class="cb-enable <?php if ($output['setting']['reg_tel_key'] == '1') { ?>selected<?php } ?>"
                               title="<?php echo $lang['nc_open']; ?>"><?php echo $lang['nc_open']; ?></label>
                        <label for="reg_tel_0"
                               class="cb-disable <?php if ($output['setting']['reg_tel_key'] == '0') { ?>selected<?php } ?>"
                               title="<?php echo $lang['nc_close']; ?>"><?php echo $lang['nc_close']; ?></label>
                        <input type="radio" id="reg_tel_1" name="reg_tel_key"
                               value="1" <?php echo $output['setting']['reg_tel_key'] == 1 ? 'checked=checked' : ''; ?>>
                        <input type="radio" id="reg_tel_0" name="reg_tel_key"
                               value="0" <?php echo $output['setting']['reg_tel_key'] == 0 ? 'checked=checked' : ''; ?>>
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">QQ</label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="reg_qq_1"
                               class="cb-enable <?php if ($output['setting']['reg_qq_key'] == '1') { ?>selected<?php } ?>"
                               title="<?php echo $lang['nc_open']; ?>"><?php echo $lang['nc_open']; ?></label>
                        <label for="reg_qq_0"
                               class="cb-disable <?php if ($output['setting']['reg_qq_key'] == '0') { ?>selected<?php } ?>"
                               title="<?php echo $lang['nc_close']; ?>"><?php echo $lang['nc_close']; ?></label>
                        <input type="radio" id="reg_qq_1" name="reg_qq_key"
                               value="1" <?php echo $output['setting']['reg_qq_key'] == 1 ? 'checked=checked' : ''; ?>>
                        <input type="radio" id="reg_qq_0" name="reg_qq_key"
                               value="0" <?php echo $output['setting']['reg_qq_key'] == 0 ? 'checked=checked' : ''; ?>>
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>

            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green"
                                onclick="document.settingForm.submit()"><?php echo $lang['nc_submit']; ?></a></div>
        </div>
    </form>
</div>
<script type="text/javascript">
    //<!CDATA[
    $(function () {

    });
    //]]>
</script>