<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="index.php?act=jfzk&op=index" title="返回列表"><i
                    class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3><?php echo $lang['member_index_manage'] ?> - <?php echo $lang['nc_new'] ?>会员积分折扣</h3>
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
            <li>新增一条会员积分折扣。</li>
        </ul>
    </div>
    <form id="user_form" enctype="multipart/form-data" method="post">
        <input type="hidden" name="form_submit" value="ok"/>
        <div class="ncap-form-default">
           
            <dl class="row">
                <dt class="tit">
                    <label for="member_passwd"><em>*</em>会员级别</label>
                </dt>
                <dd class="opt">
                    <select class="form-control edited" id="group_id" name="groud_id">
                        <?php foreach ($output['groupList'] as $item) { ?>
                            <option value="<?php echo $item['group_id'] ?>">
                                <?php echo $item['name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </dd>
            </dl>
            
            <dl class="row">
                <dt class="tit">
                    <label for="member_passwd"><em>*</em>折扣率</label>
                </dt>
                <dd class="opt">
                    <input type="number" id="rate" name="rate" class="input-number" required>
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
            $("#user_form").submit();
        });

        
    });
</script> 
