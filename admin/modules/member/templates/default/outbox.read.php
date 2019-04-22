<?php defined('InShopBN') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=outbox" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $output['message_array']['title'];?></h3>
      </div>
    </div>
  </div>
  <form id="message_form" method="post">
    <div class="ncap-form-default">
	  <dl class="row">
        <dt class="tit">
          <label for="message_title">收件人</label>
        </dt>
        <dd class="opt">
          <?php echo $output['message_array']['who'];?>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
	  
	   <dl class="row">
        <dt class="tit">
          <label for="message_title">时间</label>
        </dt>
        <dd class="opt">
          <?php echo date("Y-m-d H:i:s",$output['message_array']['addtime']);?>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
    
      <dl class="row">
        <dt class="tit">
          <label>内容</label>
        </dt>
        <dd class="opt">
          <?php echo $output['message_array']['content'];?>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script> 
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#message_form").valid()){
     $("#message_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#ac_id').on('change',function(){
		if($(this).val() == '1') {
			$('dl[nctype="message_position"]').show();
		}else{
			$('dl[nctype="message_position"]').hide();
		}
	});
	<?php if($output['message_array']['ac_id'] == '1'){ ?>
	$('dl[nctype="message_position"]').show();
    <?php } ?>
	$('#message_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            message_title : {
                required   : true
            },
			ac_id : {
                required   : true
            },
			message_url : {
				url : true
            },
			message_content : {
                required   : function(){
                    return $('#message_url').val() == '';
                }
            },
            message_sort : {
                number   : true
            }
        },
        messages : {
            message_title : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['message_add_title_null'];?>'
            },
			ac_id : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['message_add_class_null'];?>'
            },
			message_url : {
				url : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['message_add_url_wrong'];?>'
            },
			message_content : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['message_add_content_null'];?>'
            },
            message_sort  : {
                number   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['message_add_sort_int'];?>'
            }
        }
    });
    // 图片上传
    $('#fileupload').each(function(){
        $(this).fileupload({
            dataType: 'json',
            url: 'index.php?act=message&op=message_pic_upload&item_id=<?php echo $output['message_array']['message_id'];?>',
            done: function (e,data) {
                if(data != 'error'){
                	add_uploadedfile(data.result);
                }
            }
        });
    });
});
function add_uploadedfile(file_data)
{
	var newImg = '<li id="' + file_data.file_id + '"><input type="hidden" name="file_id[]" value="' + file_data.file_id + '" /><div class="thumb-list-pics"><a href="javascript:void(0);"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_message.'/';?>' + file_data.file_name + '" alt="' + file_data.file_name + '"/></a></div><a href="javascript:del_file_upload(' + file_data.file_id + ');" class="del" title="<?php echo $lang['nc_del'];?>">X</a><a href="javascript:insert_editor(\'<?php echo UPLOAD_SITE_URL.'/'.ATTACH_message.'/';?>' + file_data.file_name + '\');" class="inset"><i class="fa fa-clipboard"></i>插入图片</a></li>';
    $('#thumbnails > ul').prepend(newImg);
}
function insert_editor(file_path){
	KE.appendHtml('message_content', '<img src="'+ file_path + '" alt="'+ file_path + '">');
}
function del_file_upload(file_id)
{
    if(!window.confirm('<?php echo $lang['nc_ensure_del'];?>')){
        return;
    }
    $.getJSON('index.php?act=message&op=ajax&branch=del_file_upload&file_id=' + file_id, function(result){
        if(result){
            $('#' + file_id).remove();
        }else{
            alert('<?php echo $lang['message_add_del_fail'];?>');
        }
    });
}
</script>