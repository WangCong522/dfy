<?php defined('InShopBN') or exit('Access Invalid!');?>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">首页</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>发件箱</span>
            </li>
        </ul>
    </div>
    <div class="row ">
        <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
        </div>
       <div id="sample_1_wrapper" class="dataTables_wrapper no-footer">
            <div class="table-scrollable">
                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="sample_1" role="grid" aria-describedby="sample_1_info">
                    <thead>
                        <tr role="row">
                            <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">发件人</th>
                            <th class="sorting_desc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" >标题</th>
                            <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" >收件日期</th>
                            <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" >状态</th>
                            <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" >操作</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
					    if(is_array($output['list'])){
							foreach($output['list'] as $val){
					?>
                        <tr class="gradeX odd" role="row">
                            <td><?php echo $val['formname'];?></td>
                            <td class="sorting_1">
                               <?php echo $val['title'];?>
                            </td>
                            <td class="sorting_1">
                               <?php echo date("Y-m-d H:i:s",$val['addtime']);?>
                            </td>
                            <td class="sorting_1">
                               <?php if($val['status'] ==0) echo "已阅读";
							   else echo "未阅读";
							   ?>
                            </td>
                            <td>
                                        <button class="btn btn-sm green btn-outline filter-submit margin-bottom get" data-id="<?php echo $val['id'];?>"><i class="fa fa-commenting-o"></i>查看消息</button>
                            </td>
                        </tr>
                        <?php
								}
							}
						?>
                    </tbody>
                </table>
            </div>
			<div class="pagination"><?php echo $output['show_page'];?></div>
        </div>
    </div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body con">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>
    <script>
     $('.get').click(function(event) {
        var id=$(this).attr('data-id');
        $.post("index.php?act=article&op=GetMessageFj", {id:id}, function(data) {
                    $('#myModalLabel').text(data.info.title);
                    $('.con').html(data.info.content);
                    $('#myModal').modal('show');
        },'json');
            
     });
    </script>