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
        <div class="item-title"></a>
            <div class="subject">
                <h3>提现设置</h3>
            </div>
        </div>
    </div>
    <form id="article_form" method="post">
        <input type="hidden" name="form_submit" value="ok"/>
        <input type="hidden" name="id" value="<?php echo $output['info']['id']; ?>"/>
        <input type="hidden" name="ref_url" value="<?php echo getReferer(); ?>"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="article_title">管理费比例</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['info']['fee_rate']; ?>" name="fee_rate" id="fee_rate"
                           class="input-txt">%
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="article_title">手续费比例</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['info']['handling_fee_rate']; ?>" name="handling_fee_rate" id="handling_fee_rate"
                           class="input-txt">%
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="article_title">更新时间</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo
                    $output['info']['updated_at']; ?>" readonly
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <div class="bot">
                <a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green"
                   id="submitBtn"><?php echo $lang['nc_submit']; ?></a>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/fileupload/jquery.iframe-transport.js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/fileupload/jquery.ui.widget.js"
        charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/fileupload/jquery.fileupload.js"
        charset="utf-8"></script>
<script>
    //按钮先执行验证再提交表单
    $(function () {
        $("#submitBtn").click(function () {
            if ($("#article_form").valid()) {
                $("#article_form").submit();
            }
        });
    });
    //
    $(document).ready(function () {
        $('#article_form').validate({
            errorPlacement: function (error, element) {
                var error_td = element.parent('dd').children('span.err');
                error_td.append(error);
            },
            rules: {
                lsk: {
                    required: true
                },
                tj: {
                    required: true
                },
                dpj: {
                    required: true
                },
                dpj_top: {
                    required: true
                }
            },
            messages: {
                lsk: {
                    required: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['group_add_lsk_null'];?>'
                },
                tj: {
                    required: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['group_add_tj_null'];?>'
                },
                dpj: {
                    required: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['group_add_dpj_null'];?>'
                },
                dpj_top: {
                    required: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['group_add_dpj_top_null'];?>'
                }
            }
        });
    });
</script>