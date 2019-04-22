<?php defined('InShopBN') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['cache_cls_operate'];?></h3>
        <h5><?php echo $lang['cache_cls_operate_subhead'];?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li>当系统修改设置后，前后台部分内容需及时更新缓存方可显示正常。</li>
    </ul>
  </div>
  <form id="cache_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="ncap-form-all">
      <dl class="row">
      <dt class="tit"><span>选择要更新的缓存数据</span></dt>
        <dd class="opt nobg nopd nobd nobs">
          <div class="ncap-account-container">
            <h4>
              <input id="cls_full" name="cls_full" value="1" type="checkbox" class="checkbox">
              <label for="cls_full"><?php echo $lang['cache_cls_all'];?></label>
            </h4>
            <ul class="ncap-account-container-list">
              <li>
                <label>
                  <input type="checkbox" class="checkbox" name="cache[]" value="setting" >
                  网站配置</label>
              </li>
              <li>
                <label>
                  <input type="checkbox" class="checkbox" name="cache[]" id="admin_menu" value="admin_menu" >
                  后台菜单</label>
              </li>
              
            </ul>
          </div>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表
$(function(){
	$("#submitBtn").click(function(){
		if($('input[name="cache[]"]:checked').size()>0){
			$("#cache_form").submit();
		}
	});

	$('#cls_full').click(function(){
		$('input[name="cache[]"]').attr('checked',$(this).attr('checked') == 'checked');
	});
});
</script>
