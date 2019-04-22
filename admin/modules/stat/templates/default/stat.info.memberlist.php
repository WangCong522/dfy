<?php defined('InShopBN') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>会员统计</h3>
        <h5>平台针对会员的各项数据统计</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script> 
<script>
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=stat_member&op=get_member_xml&t=<?php echo $_GET['t'];?>',
        colModel : [
            /*{display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},//*/
            {display: '会员名', name : 'username', width : 100, sortable : false, align: 'left'},
			{display: '编号', name : 'num',  width : 80, sortable : false, align: 'center'},
			{display: '注册时间', name : 'time',  width : 120, sortable : false, align: 'center'},
            {display: '真实姓名', name : 'name',  width : 100, sortable : false, align: 'left'},
			{display: '电话', name : 'tel',  width : 120, sortable : false, align: 'left'},
			{display: 'QQ', name : 'qq',  width : 120, sortable : false, align: 'left'},
            {display: '邮箱', name : 'email',  width : 120, sortable : false, align: 'left'},
			{display: '状态', name : 'status',  width : 60, sortable : false, align: 'left'},
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation }
        ],
        usepager: true,
        rp: 15,
        title: '会员详细'
    });
});
function fg_operation(name, bDiv){
    var stat_url = 'index.php?act=stat_member&op=showmember&exporttype=excel&t=<?php echo $_GET['t'];?>';
    get_excel(stat_url,bDiv);
}
</script> 
