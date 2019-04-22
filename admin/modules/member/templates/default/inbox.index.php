<?php defined('InShopBN') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['message_index_manage']?></h3>
        <h5><?php echo $lang['message_manage_subhead']?></h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['nc_prompts_title'];?>"><?php echo $lang['nc_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['message_index_help1'];?></li>
      <li><?php echo $lang['message_index_help2'];?></li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=inbox&op=get_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '标题', name : 'title', width : 300, sortable : true, align: 'left'},
			{display: '发件人', name : 'formname', width : 120, sortable : true, align: 'left'},
			{display: '日期', name : 'date', width : 150, sortable : true, align: 'left'},
			{display: '阅读', name : 'is_read', width : 60, sortable : true, align: 'left'},
			{display: '回复', name : 'is_reply', width : 60, sortable : true, align: 'left'}
            ],
        buttons : [
            ],
        searchitems : [
            {display: '发件人', name : 'formame'},
            ],
        sortname: "id",
        sortorder: "desc",
        title: '会员列表'
    });
	
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=inbox&op=message_add';
    }
    if (name == 'csv') {
        if ($('.trSelected', bDiv).length == 0) {
            if (!confirm('您确定要下载全部数据吗？')) {
                return false;
            }
        }
        var itemids = new Array();
        $('.trSelected', bDiv).each(function(i){
            itemids[i] = $(this).attr('data-id');
        });
        fg_csv(itemids);
    }
}

function fg_csv(ids) {
    id = ids.join(',');
    window.location.href = $("#flexigrid").flexSimpleSearchQueryString()+'&op=export_csv&id=' + id;
}
</script> 

