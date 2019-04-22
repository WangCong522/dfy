<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>提升等级</span>
        </li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="note note-success">
                    <h4 class="block">升级条件说明</h4>
                    <p>
                        升级扣除报单币,升级成功后既享受相应等级待遇，并对您关系网会员产生相应奖励。
                    </p>
                </div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-md-3 control-label new_style_padding1 col-xs-4 text_new_style_r new_style_margin1 " for="title">当前级别:</label>
                        <div class="col-md-5 new_style_padding_none col-xs-7">
                            <input id="growl_text" type="text" class="form-control"
                                   value="<?php echo $output['userinfo']['group_name']; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label new_style_padding1 col-xs-4 text_new_style_r new_style_margin1 " for="title">升级为:</label>
                        <div class="col-md-5 new_style_padding_none col-xs-7">
                            <select class="form-control edited" id="group_id" name="group_id">
                                <?php foreach ($output['groupList'] as $item) { ?>
                                    <option value="<?php echo $item['group_id'] ?>"
                                            date-rel="<?php echo $item['lsk'] ?>">
                                        <?php echo $item['name'] ?>--(￥<?php echo $item['lsk'] ?>)
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label new_style_padding1 col-xs-4 text_new_style_r new_style_margin1 " for="title">当前报单币总额:</label>
                        <div class="col-md-5 new_style_padding_none col-xs-7">
                            <input id="growl_text" type="text" class="form-control"
                                   value="<?php echo $output['userinfo']['bao_balance']; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label new_style_padding1 col-xs-4 text_new_style_r new_style_margin1 " for="title">升级所需费用:</label>
                        <div class="col-md-5 new_style_padding_none col-xs-7">
                            <input id="fee_lsk" type="text" class="form-control"
                                   value="0" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="title"></label>
                        <div class="col-md-5 text-center">
                            <a href="javascript:;" class="btn red btn-lg tj" id="bs_growl_show">立即升级</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var level = '<?php echo $output['userinfo']['group_id'];?>';
    $('.tj').click(function (event) {
        if (level == 4) {
            alert('您已是最高等级,无需升级');
            return false;
        }
        if (confirm('确定升级吗?')) {
            var group_id = $('#group_id').val();
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "index.php?act=user&op=DoUpLevel",
                data: {group_id: group_id},
                success: function (data) {
                    if (data.status) {
                        swal("成功!", "升级成功！", "success");
                        window.setTimeout('location.href="index.php?act=member&op=home"', '1000');
                    } else {
                        swal("失败!", data.info, "error");
                    }
                }
            });
        }
    });
    $('#group_id').change(function () {
        var user_lsk = <?php echo $output['userinfo']['lsk'];?>;
        var select_lsk = $(this).find("option:selected").attr('date-rel');
        var fee_lsk = select_lsk - user_lsk;
        if (fee_lsk >= 0) {
            $('#fee_lsk').val(fee_lsk);
        }
    })
</script>