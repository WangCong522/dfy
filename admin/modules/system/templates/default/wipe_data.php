<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?php echo $lang['cache_cls_operate']; ?></h3>
                <h5><?php echo $lang['cache_cls_operate_subhead']; ?></h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li>清空所有会员数据！请谨慎操作</li>
        </ul>
    </div>
    <form id="cache_form" method="post">
        <input type="hidden" name="form_submit" value="ok"/>
        <div class="ncap-form-all">
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green"
                                id="submitBtn">确定清空</a></div>
        </div>
    </form>
</div>
<script>
    //按钮先执行验证再提交表
    $(function () {
        $("#submitBtn").click(function () {
            $("#cache_form").submit();
        });

        $('#cls_full').click(function () {
            $('input[name="cache[]"]').attr('checked', $(this).attr('checked') == 'checked');
        });
    });
</script>
