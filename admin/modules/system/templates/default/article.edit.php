<?php defined('InShopBN') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=article&op=article" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['article_index_manage'];?> - <?php echo $lang['nc_edit'];?>文章“<?php echo $output['article_array']['article_title'];?>”</h3>
        <h5><?php echo $lang['article_index_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="article_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="aid" value="<?php echo $output['article_array']['aid'];?>" />
    <input type="hidden" name="ref_url" value="<?php echo getReferer();?>" />
    <div class="ncap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="article_title"><em>*</em><?php echo $lang['article_index_title'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['article_array']['title'];?>" name="title" id="title" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="cate_id"><em>*</em><?php echo $lang['article_add_class'];?></label>
        </dt>
        <dd class="opt">
          <select name="typeid" id="typeid">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            <?php if(!empty($output['parent_list']) && is_array($output['parent_list'])){ ?>
            <?php foreach($output['parent_list'] as $k => $v){ ?>
            <option <?php if($output['article_array']['typeid'] == $v['id']){ ?>selected='selected'<?php } ?> value="<?php echo $v['id'];?>"><?php echo $v['typename'];?></option>
            <?php } ?>
            <?php } ?>
          </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
     
      <dl class="row">
        <dt class="tit">
          <label for="if_show"><?php echo $lang['article_add_show'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="article_show1" class="cb-enable <?php if($output['article_array']['status'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['nc_yes'];?></label>
            <label for="article_show0" class="cb-disable <?php if($output['article_array']['status'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['nc_no'];?></label>
            <input id="article_show1" name="article_show" <?php if($output['article_array']['status'] == '1'){ ?>checked="checked"<?php } ?>  value="1" type="radio">
            <input id="article_show0" name="article_show" <?php if($output['article_array']['status'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
    
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['article_add_content'];?></label>
        </dt>
        <dd class="opt">
          <?php showEditor('content',$output['article_array']['content']);?>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
     
      <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green" id="submitBtn"><?php echo $lang['nc_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script> 
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#article_form").valid()){
     $("#article_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#ac_id').on('change',function(){
		if($(this).val() == '1') {
			$('dl[nctype="article_position"]').show();
		}else{
			$('dl[nctype="article_position"]').hide();
		}
	});
	<?php if($output['article_array']['ac_id'] == '1'){ ?>
	$('dl[nctype="article_position"]').show();
    <?php } ?>
	$('#article_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            article_title : {
                required   : true
            },
			ac_id : {
                required   : true
            },
			article_url : {
				url : true
            },
			article_content : {
                required   : function(){
                    return $('#article_url').val() == '';
                }
            },
            article_sort : {
                number   : true
            }
        },
        messages : {
            article_title : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['article_add_title_null'];?>'
            },
			ac_id : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['article_add_class_null'];?>'
            },
			article_url : {
				url : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['article_add_url_wrong'];?>'
            },
			article_content : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['article_add_content_null'];?>'
            },
            article_sort  : {
                number   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['article_add_sort_int'];?>'
            }
        }
    });
    // 图片上传
    $('#fileupload').each(function(){
        $(this).fileupload({
            dataType: 'json',
            url: 'index.php?act=article&op=article_pic_upload&item_id=<?php echo $output['article_array']['article_id'];?>',
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
	var newImg = '<li id="' + file_data.file_id + '"><input type="hidden" name="file_id[]" value="' + file_data.file_id + '" /><div class="thumb-list-pics"><a href="javascript:void(0);"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_ARTICLE.'/';?>' + file_data.file_name + '" alt="' + file_data.file_name + '"/></a></div><a href="javascript:del_file_upload(' + file_data.file_id + ');" class="del" title="<?php echo $lang['nc_del'];?>">X</a><a href="javascript:insert_editor(\'<?php echo UPLOAD_SITE_URL.'/'.ATTACH_ARTICLE.'/';?>' + file_data.file_name + '\');" class="inset"><i class="fa fa-clipboard"></i>插入图片</a></li>';
    $('#thumbnails > ul').prepend(newImg);
}
function insert_editor(file_path){
	KE.appendHtml('article_content', '<img src="'+ file_path + '" alt="'+ file_path + '">');
}
function del_file_upload(file_id)
{
    if(!window.confirm('<?php echo $lang['nc_ensure_del'];?>')){
        return;
    }
    $.getJSON('index.php?act=article&op=ajax&branch=del_file_upload&file_id=' + file_id, function(result){
        if(result){
            $('#' + file_id).remove();
        }else{
            alert('<?php echo $lang['article_add_del_fail'];?>');
        }
    });
}
</script>