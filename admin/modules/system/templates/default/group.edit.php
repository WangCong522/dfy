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
        <div class="item-title"><a class="back" href="index.php?act=cs&op=group" title="返回列表"><i
                    class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3><?php echo $lang['group_index_manage']; ?> - <?php echo $lang['nc_edit']; ?>
                    会员等级“<?php echo $output['group_array']['name']; ?>”</h3>
            </div>
        </div>
    </div>
    <form id="article_form" method="post">
        <input type="hidden" name="form_submit" value="ok"/>
        <input type="hidden" name="group_id" value="<?php echo $output['group_array']['group_id']; ?>"/>
        <input type="hidden" name="ref_url" value="<?php echo getReferer(); ?>"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="article_title"><?php echo $lang['group_edit_title']; ?></label>
                </dt>
                <dd class="opt">
                    <?php echo $output['group_array']['name']; ?>
                    <input type="text" value="<?php echo $output['group_array']['name']; ?>" name="name" id="name"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="article_title"><em>*</em><?php echo $lang['group_edit_lsk']; ?></label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['group_array']['lsk']; ?>" name="lsk" id="lsk"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
           <!-- <dl class="row">
                <dt class="tit">
                    <label for="article_title"><em>*</em><?php echo $lang['group_edit_bdps']; ?></label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['group_array']['bdps']; ?>" name="bdps" id="bdps"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>-->
            <dl class="row">
                <dt class="tit">
                    <label for="article_title"><em>*</em><?php echo $lang['group_edit_tj']; ?>(整数)</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['group_array']['tj']; ?>" name="tj" id="tj"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
<!--            <dl class="row">-->
<!--                <dt class="tit">-->
<!--                    <label for="article_title"><em>*</em>--><?php //echo $lang['group_edit_jiandian']; ?><!--</label>-->
<!--                </dt>-->
<!--                <dd class="opt">-->
<!--                    <table class="gridtable">-->
<!--                        <tr>-->
<!--                            <th>积分(整数)</th>-->
<!--                            <th>代数</th>-->
<!--                            <th>上限(0为无限制)</th>-->
<!--                        </tr>-->
<!--                        <tr>-->
<!--                            <td><input type="text" value="--><?php //echo $output['group_array']['jiandian']; ?><!--"-->
<!--                                       name="jiandian"-->
<!--                                       id="jiandian"-->
<!--                                       class="input-txt-table"></td>-->
<!--                            <td><input type="text" value="--><?php //echo $output['group_array']['jiandianlimit']; ?><!--"-->
<!--                                       name="jiandianlimit"-->
<!--                                       id="jiandianlimit"-->
<!--                                       class="input-txt-table"></td>-->
<!--                            <td><input type="text" value="--><?php //echo $output['group_array']['jiandiantop']; ?><!--"-->
<!--                                       name="jiandiantop"-->
<!--                                       id="jiandiantop"-->
<!--                                       class="input-txt-table"></td>-->
<!--                        </tr>-->
<!--                    </table>-->
<!--                    <span class="err"></span>-->
<!--                    <p class="notic"></p>-->
<!--                </dd>-->
<!--            </dl>-->
            <dl class="row">
                <dt class="tit">
                    <label for="article_title"><em>*</em><?php echo $lang['group_edit_dpj']; ?></label>
                </dt>
                <dd class="opt">
                    <table class="gridtable">
                        <tr>
                            <th>积分(整数)</th>
                            <th>日封顶</th>
                            <!-- <th>开始层数</th> -->
                        </tr>
                        <tr>
                            <td><input type="text"
                                       value="<?php echo $output['group_array']['dpj']; ?>"
                                       name="dpj"
                                       id="dpj"
                                       class="input-txt-table"></td>
                            <td><input type="text"
                                       value="<?php echo $output['group_array']['dpj_top']; ?>"
                                       name="dpj_top"
                                       id="dpj_top"
                                       class="input-txt-table"></td>
                            <!-- <td><input type="text"
                                       value="<?php echo $output['group_array']['dpj_start_lay']; ?>"
                                       name="dpj_start_lay"
                                       id="dpj_start_lay"
                                       class="input-txt-table"></td> -->
                        </tr>
                    </table>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="article_title"><em>*</em><?php echo $lang['group_edit_cpj']; ?></label>
                </dt>
                <dd class="opt">
                    <table class="gridtable">
                        <tr>
                            <th>积分(整数)</th>
                            <!-- <th>代数</th> -->
                        </tr>
                        <tr>
                            <td><input type="text"
                                       value="<?php echo $output['group_array']['cpj']; ?>"
                                       name="cpj"
                                       id="cpj"
                                       class="input-txt-table"></td>
                            <!-- <td><input type="text"
                                       value="<?php echo $output['group_array']['cpjlimit']; ?>"
                                       name="cpjlimit"
                                       id="cpjlimit"
                                       class="input-txt-table"></td> -->
                        </tr>
                    </table>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <!--<dl class="row">
                <dt class="tit">
                    <label for="article_title"><em>*</em><?php echo $lang['group_edit_lead']; ?></label>
                </dt>
                <dd class="opt">
                    <table class="gridtable">
                        <tr>
                            <th>比例(%)</th>
                            <th>代数</th>
                        </tr>
                        <tr>
                            <td><input type="text" value="<?php echo $output['group_array']['lead']; ?>"
                                       name="lead"
                                       id="lead"
                                       class="input-txt-table"></td>
                            <td><input type="text" value="<?php echo $output['group_array']['leadlimit']; ?>"
                                       name="leadlimit"
                                       id="leadlimit"
                                       class="input-txt-table"></td>
                        </tr>
                    </table>
                </dd>
            </dl>-->
            <!--<dl class="row">
                <dt class="tit">
                    <label for="article_title"><em>*</em><?php echo $lang['group_edit_gej']; ?>(%)</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['group_array']['gej']; ?>" name="gej"
                           id="gej"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>-->
            <dl class="row">
                <dt class="tit">
                    <label for="article_title"><em>*</em><?php echo $lang['group_edit_subsidy']; ?>(整数)</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['group_array']['subsidy']; ?>" name="subsidy"
                           id="subsidy"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="article_title"><em>*</em><?php echo $lang['group_edit_cfxf']; ?>(%)</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['group_array']['cfxf']; ?>" name="cfxf" id="cfxf"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="article_title"><em>*</em>积分消费折扣(%)</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['group_array']['jfzk']; ?>" name="jfzk" id="re_consum_reward"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="article_title"><em>*</em>重消奖励比例(%)</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['group_array']['re_consum_reward']; ?>" name="re_consum_reward" id="re_consum_reward"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <!--<dl class="row">
                <dt class="tit">
                    <label for="article_title"><em>*</em><?php echo $lang['group_edit_tax']; ?>(%)</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['group_array']['tax']; ?>" name="tax" id="tax"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>-->
            <!--<dl class="row">
                <dt class="tit">
                    <label for="article_title"><em>*</em><?php echo $lang['group_edit_fund']; ?>(%)</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['group_array']['fund']; ?>" name="fund" id="fund"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>-->
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