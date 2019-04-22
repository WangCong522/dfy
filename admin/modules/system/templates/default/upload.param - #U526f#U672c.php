<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<style type="text/css">
    table.gridtable {
        font-family: verdana, arial, sans-serif;
        font-size: 11px;
        color: #333333;
        border-width: 1px;
        border-color: #666666;
        border-collapse: collapse;
    }

    table.gridtable th {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
    }

    table.gridtable td {
        border-width: 1px;
        padding: 8px;
        border-style: solid;
        border-color: #666666;
        background-color: #ffffff;
    }
</style>
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
        <ul>
            <li>网站全局参数配置。</li>
        </ul>
    </div>
    <form id="form" method="post" name="settingForm" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">网络费(整数)</label>
                </dt>
                <dd class="opt">
                    <input id="con_net_fee" name="con_net_fee" type="text" class="input-txt"
                           style="width:50px !important;"
                           value="<?php echo $output['list_setting']['con_net_fee']; ?>"/>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">最低提现金额(整数)</label>
                </dt>
                <dd class="opt">
                    <input id="con_tx_jine" name="con_tx_jine" type="text" class="input-txt"
                           style="width:50px !important;"
                           value="<?php echo $output['list_setting']['con_tx_jine']; ?>"/>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">提现手续费(%)</label>
                </dt>
                <dd class="opt">
                    <input id="con_tx_shouxu" name="con_tx_shouxu" type="text" class="input-txt"
                           style="width:50px !important;"
                           value="<?php echo $output['list_setting']['con_tx_shouxu']; ?>"/>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">成为服务站左右各单数</label>
                </dt>
                <dd class="opt">
                    <input id="con_ss_reach" name="con_ss_reach" type="text" class="input-txt"
                           style="width:50px !important;"
                           value="<?php echo $output['list_setting']['con_ss_reach']; ?>"/>
                    <p class="notic"></p>
                </dd>
            </dl>
            <!--<dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">服务站推荐奖(%)</label>
                </dt>
                <dd class="opt">
                    <input id="con_ss_tuijian_rate" name="con_ss_tuijian_rate" type="text" class="input-txt"
                           style="width:50px !important;"
                           value="<?php echo $output['list_setting']['con_ss_tuijian_rate']; ?>"/>
                    <p class="notic"></p>
                </dd>
            </dl>-->
            <dl class="row">
                <dt class="tit">
                    <label for="article_title">星级升级</label>
                </dt>
                <dd class="opt">
                    <table class="gridtable">
                        <tr>
                            <th>星级</th>
                            <th>左右各单数</th>
                        </tr>
                        <?php foreach ($output['list_setting']['con_star'] as $key => $value) { ?>
                            <tr>
                                <td><?php echo $key ?>星</td>
                                <td><input type="text" value="<?php echo $value ?>"
                                           name="con_star[<?php echo $key ?>]"
                                           class="input-txt-table">
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">建设银行行收款账号</label>
                </dt>
                <dd class="opt">
                    <input id="con_ccb_account" name="con_ccb_account" type="text" class="input-txt"
                           value="<?php echo $output['list_setting']['con_ccb_account']; ?>"/>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">工商银行收款账号</label>
                </dt>
                <dd class="opt">
                    <input id="con_icbc_account" name="con_icbc_account" type="text" class="input-txt"
                           value="<?php echo $output['list_setting']['con_icbc_account']; ?>"/>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">农业银行收款账号</label>
                </dt>
                <dd class="opt">
                    <input id="con_abc_account" name="con_abc_account" type="text" class="input-txt"
                           value="<?php echo $output['list_setting']['con_abc_account']; ?>"/>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">中国银行收款账号</label>
                </dt>
                <dd class="opt">
                    <input id="con_boc_account" name="con_boc_account" type="text" class="input-txt"
                           value="<?php echo $output['list_setting']['con_boc_account']; ?>"/>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">支付宝帐号</label>
                </dt>
                <dd class="opt">
                    <input id="con_alipay_account" name="con_alipay_account" type="text" class="input-txt"
                           value="<?php echo $output['list_setting']['con_alipay_account']; ?>"/>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">微信帐号</label>
                </dt>
                <dd class="opt">
                    <input id="con_wx_account" name="con_wx_account" type="text" class="input-txt"
                           value="<?php echo $output['list_setting']['con_wx_account']; ?>"/>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">支付宝二维码</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
                        <span class="show">
                            <a class="nyroModal" rel="gal"
                               href="<?php echo UPLOAD_SITE_URL . '/' . (ATTACH_PATH . '/payimg/' . $output['list_setting']['con_aliimg_account']); ?>">
                                <i class="fa fa-picture-o"
                                   onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL . '/' . (ATTACH_PATH . '/payimg/' . $output['list_setting']['con_aliimg_account']); ?>>')"
                                   onMouseOut="toolTip()">
                                </i>
                            </a>
                        </span>
                        <span class="type-file-box">
                    <input name="con_aliimg_account" type="file" class="type-file-file" id="con_aliimg_account"
                           size="30"
                           hidefocus="true"
                           title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
                    </span>
                    </div>
                    <p class="notic">150*150像素jpg/gif/png格式的图片。</p>
                </dd>
                <!--<dd class="opt">
                    <input id="con_aliimg_account" name="con_aliimg_account" type="text" class="input-txt"
                           value="<?php echo $output['list_setting']['con_aliimg_account']; ?>"/>
                    <p class="notic"></p>
                </dd>-->
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="image_allow_ext">微信二维码</label>
                </dt>
                <dd class="opt">
                    <div class="input-file-show">
                        <span class="show">
                            <a class="nyroModal" rel="gal"
                               href="<?php echo UPLOAD_SITE_URL . '/' . (ATTACH_PATH . '/payimg/' . $output['list_setting']['con_wximg_account']); ?>">
                                <i class="fa fa-picture-o"
                                   onMouseOver="toolTip('<img src=<?php echo UPLOAD_SITE_URL . '/' . (ATTACH_PATH . '/payimg/' . $output['list_setting']['con_wximg_account']); ?>>')"
                                   onMouseOut="toolTip()">
                                </i>
                            </a>
                        </span>
                        <span class="type-file-box">
                    <input name="con_wximg_account" type="file" class="type-file-file" id="con_wximg_account" size="30"
                           hidefocus="true"
                           title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效"/>
                    </span>
                    </div>
                    <p class="notic">150*150像素jpg/gif/png格式的图片。</p>
                </dd>
                <!--<dd class="opt">
                    <input id="con_wximg_account" name="con_wximg_account" type="text" class="input-txt"
                           value="<?php echo $output['list_setting']['con_wximg_account']; ?>"/>
                    <p class="notic"></p>
                </dd>-->
            </dl>
            <input type="hidden" name="old_con_aliimg_account"
                   value="<?php echo $output['list_setting']['con_aliimg_account']; ?>"/>
            <input type="hidden" name="old_con_wximg_account"
                   value="<?php echo $output['list_setting']['con_wximg_account']; ?>"/>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green"
                                onclick="document.settingForm.submit()"><?php echo $lang['nc_submit']; ?></a></div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL; ?>/js/jquery.nyroModal.js"></script>
<script type="text/javascript">
    $(function () {
        var textButton1 = "<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />";
        var textButton2 = "<input type='text' name='textfield' id='textfield2' class='type-file-text' /><input type='button' name='button' id='button2' value='选择上传...' class='type-file-button' />"
        $(textButton1).insertBefore("#con_aliimg_account");
        $(textButton2).insertBefore("#con_wximg_account");
        $("#con_aliimg_account").change(function () {
            $("#textfield1").val($("#con_aliimg_account").val());
        });
        $("#con_wximg_account").change(function () {
            $("#textfield2").val($("#con_wximg_account").val());
        });
// 上传图片类型
        $('input[class="type-file-file"]').change(function () {
            var filepath = $(this).val();
            var extStart = filepath.lastIndexOf(".");
            var ext = filepath.substring(extStart, filepath.length).toUpperCase();
            if (ext != ".PNG" && ext != ".GIF" && ext != ".JPG" && ext != ".JPEG") {
                alert("<?php echo $lang['default_img_wrong'];?>");
                $(this).attr('value', '');
                return false;
            }
        });

        $('#time_zone').attr('value', '<?php echo $output['list_setting']['time_zone'];?>');
        $('.nyroModal').nyroModal();
    });
</script>