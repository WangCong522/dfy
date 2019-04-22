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
        <div class="item-title"><a class="back" href="index.php?act=cs&op=star" title="返回列表"><i
                    class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3>星级管理 - <?php echo $lang['nc_edit']; ?>
                    星级名称“<?php echo $output['star_array']['name']; ?>”</h3>
            </div>
        </div>
    </div>
    <form id="article_form" method="post">
        <input type="hidden" name="form_submit" value="ok"/>
        <input type="hidden" name="id" value="<?php echo $output['star_array']['id']; ?>"/>
        <input type="hidden" name="ref_url" value="<?php echo getReferer(); ?>"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label for="article_title"><?php echo $lang['group_edit_title']; ?></label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['star_array']['name']; ?>" name="name" id="name"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="article_title">等级</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo
                    $output['star_array']['level']; ?>" readonly name="level"
                           id="level"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="article_title">级差奖比例</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['star_array']['jicha_rate']; ?>" name="jicha_rate" id="jicha_rate"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="article_title">全球分红奖比例</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['star_array']['bonus_rate']; ?>" name="bonus_rate" id="bonus_rate"
                           class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="article_title">升级条件(根据所需董事)</label>
                </dt>
                <dd class="opt">
                    <table class="gridtable">
                        <tr>
                            <th >条件</th>
                        </tr>
                        <?php foreach ($output['star_array']['con_star'] as $key => $value) { ?>
                            <tr>
                                <td><input type="text" value="<?php echo $value ?>"
                                           name="con_star[<?php echo $key ?>]"
                                           class="input-txt-table">
                                    <?php switch($key){
                                        case 1:
                                            echo "个普通会员";
                                            break;
                                        case 2:
                                            echo "个1星董事";
                                            break;
                                        case 3:
                                            echo "个2星董事";
                                            break;
                                        case 4:
                                            echo "个3星董事";
                                            break;
                                        case 5:
                                            echo "个4星董事";
                                            break;
                                        case 6:
                                            echo "个5星董事";
                                            break;
                                        case 7:
                                            echo "个6星董事";
                                            break;
                                        case 8:
                                            echo "个7星董事";
                                            break;
                                        case 9:
                                            echo "个一皇冠董事";
                                            break;
                                        case 10:
                                            echo "个二皇冠董事";
                                            break;

                                    } ?>

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
                    <label for="article_title">升级条件(根据PV)</label>
                </dt>
                <dd class="opt">
                    <table class="gridtable">
                        <tr>
                            <th style="width: 100px">账户PV需达到</th>
                        </tr>
                            <tr>
                                <td><input type="text" value="<?php echo $output['star_array']['con_pv'] ?>"
                                           name="con_pv"
                                           style="width: 100px">PV
                                </td>
                            </tr>

                    </table>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="article_title">升到此等级所需要的PV（等级为1时填写）</label>
                </dt>
                <dd class="opt">
                    <table class="gridtable">
                        <tr>
                            <th style="width: 100px">账户PV需达到</th>
                        </tr>
                        <tr>
                            <td><input type="text" value="<?php echo $output['star_array']['first_pv'] ?>"
                                       name="first_pv"
                                       style="width: 100px">PV
                            </td>
                        </tr>

                    </table>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="article_title">下个星级</label>
                </dt>
                <dd class="opt">
                    <select name="next_star" class="input" style="width: 100px">
                        <option value="0">最高星级</option>
                        <?php foreach ($output['star_list'] as $value):?>
                            <option value="<?= $value['level']?>"
                                    <?php if($value['level'] == $output['star_array']['next_star']):?>
                                    selected
                                    <?php endif;?>><?= $value['name']?></option>
                        <?php endforeach;?>
                    </select>
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