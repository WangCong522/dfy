<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title"><a class="back" href="index.php?act=article&op=article" title="返回列表"><i
                    class="fa fa-arrow-circle-o-left"></i></a>
            <div class="subject">
                <h3><?php echo $lang['article_index_manage']; ?> - <?php echo $lang['nc_new']; ?>商品</h3>
                <h5><?php echo $lang['article_index_manage_subhead']; ?></h5>
            </div>
        </div>
    </div>
    <form id="article_form" method="post" name="articleForm">
        <input type="hidden" name="form_submit" value="ok"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em><?php echo $lang['article_index_title']; ?></label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="goods_name" id="title" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label for="cate_id"><em>*</em><?php echo $lang['article_add_class']; ?></label>
                </dt>
                <dd class="opt">
                    <select name="typeid" id="typeid" onchange="selectType(this)">
                        <option value=""><?php echo $lang['nc_please_choose']; ?></option>
                        <?php if (!empty($output['parent_list']) && is_array($output['parent_list'])) { ?>
                            <?php foreach ($output['parent_list'] as $k => $v) { ?>
                                <option <?php if ($output['typeid'] == $v['id']){ ?>selected='selected'<?php } ?>
                                        value="<?php echo $v['id']; ?>"><?php echo $v['typename']; ?></option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                    <span class="err"></span>
                </dd>
            </dl>

            <dl class="row dlgroup_id">
                <dt class="tit">
                    <label for="member_passwd"><em>*</em>会员级别</label>
                </dt>
                <dd class="opt">
                    <select class="form-control edited" id="group_id" name="group_id">
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
                    <label><em>*</em><?php echo $lang['goods_price']; ?></label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="price" id="price" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row dlgive">
                <dt class="tit">
                    <label><em>*</em>赠送数量</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="give" id="give" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label><em>*</em><?php echo $lang['goods_stock']; ?></label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="stock" id="price" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>运费:</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="ship_fee" id="ship_fee" class="input-txt" required>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row" style="display: none" id="fanli">
                <dt class="tit">
                    <label><em>*</em><?php echo $lang['goods_ylb_fanli']; ?></label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="fanli" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row" style="display: none" id="beout">
                <dt class="tit">
                    <label><em>*</em><?php echo $lang['goods_ylb_beout']; ?></label>
                </dt>
                <dd class="opt">
                    <input type="text" value="" name="beout" class="input-txt">
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em><?php echo $lang['goods_index_thumb']; ?></label>
                </dt>
                <dd class="opt">
                    <input id="fileupload" type="file" name="fileupload">
                    <div id="thumbnails">
                        <ul></ul>
                    </div>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <dl class="row">
                <dt class="tit">
                    <label><?php echo $lang['article_add_show']; ?></label>
                </dt>
                <dd class="opt">
                    <div class="onoff">
                        <label for="article_show1" class="cb-enable selected"><?php echo $lang['nc_yes']; ?></label>
                        <label for="article_show0" class="cb-disable"><?php echo $lang['nc_no']; ?></label>
                        <input id="article_show1" name="article_show" checked="checked" value="1" type="radio">
                        <input id="article_show0" name="article_show" value="0" type="radio">
                    </div>
                    <p class="notic"></p>
                </dd>
            </dl>

            <dl class="row">
                <dt class="tit">
                    <label><em>*</em><?php echo $lang['article_add_content']; ?></label>
                </dt>
                <dd class="opt">
                    <?php showEditor('content'); ?>
                    <span class="err"></span>
                    <p class="notic"></p>
                </dd>
            </dl>
            <div class="bot"><a href="JavaScript:void(0);" class="ncap-btn-big ncap-btn-green"
                                id="submitBtn"><?php echo $lang['nc_submit']; ?></a></div>
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
    $('.dlgive').hide();
    $('.dlgroup_id').hide();
    $('#group_id').val('');
    //按钮先执行验证再提交表单
    $(function () {
        $("#submitBtn").click(function () {
            if ($("#article_form").valid()) {
                $("#article_form").submit();
            }
        });
    });

    $(document).ready(function () {
        $('#ac_id').on('change', function () {
            if ($(this).val() == '1') {
                $('dl[nctype="article_position"]').show();
            } else {
                $('dl[nctype="article_position"]').hide();
            }
        });
        $('#article_form').validate({
            errorPlacement: function (error, element) {
                var error_td = element.parent('dd').children('span.err');
                error_td.append(error);
            },
            rules: {
                article_title: {
                    required: true
                },
                ac_id: {
                    required: true
                },
                article_url: {
                    url: true
                },
                article_content: {
                    required: function () {
                        return $('#article_url').val() == '';
                    }
                },
                article_sort: {
                    number: true
                }
            },
            messages: {
                article_title: {
                    required: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['article_add_title_null'];?>'
                },
                ac_id: {
                    required: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['article_add_class_null'];?>'
                },
                article_url: {
                    url: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['article_add_url_wrong'];?>'
                },
                article_content: {
                    required: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['article_add_content_null'];?>'
                },
                article_sort: {
                    number: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['article_add_sort_int'];?>'
                }
            }
        });
        // 图片上传
        $('#fileupload').each(function () {
            $(this).fileupload({
                dataType: 'json',
                url: 'index.php?act=goods&op=goods_pic_upload',
                done: function (e, data) {
                    if (data != 'error') {
                        add_uploadedfile(data.result);
                    }
                },
                change: function (e, data) {
                    if ($('#thumbnails ul li').length == 1) {
                        alert("只允许上传一张缩略图");
                        return false;
                    }
                },
                drop: function (e, data) {
                    if (data.files.length > 1) {
                        alert("Max 1 files are allowed");
                        return false;
                    }
                }
            });
        });
    });

    function add_uploadedfile(file_data) {
        var idfun = "'" + file_data.file_id + "'";
        var newImg = '<li id="thumb"><input type="hidden" name="thumb" value="' + file_data.file_path + '" /><div class="thumb-list-pics"><a href="javascript:void(0);"><img src="<?php echo UPLOAD_SITE_URL . '/' . ATTACH_ARTICLE_GOODS . '/' . date('Y-m-d', time()) . '/';?>' + file_data.file_name + '" style="width:200px" alt="' + file_data.file_name + '"/></a></div><a href="javascript:del_file_upload(this,' + idfun + ');" class="del" title="<?php echo $lang['nc_del'];?>">X</a><a href="javascript:insert_editor(\'<?php echo UPLOAD_SITE_URL . '/' . ATTACH_ARTICLE_GOODS . '/';?>' + file_data.file_name + '\');" class="inset"><i class="fa fa-clipboard"></i>插入图片</a></li>';
        $('#thumbnails > ul').prepend(newImg);
    }
    function insert_editor(file_path) {
        KE.appendHtml('article_content', '<img src="' + file_path + '" alt="' + file_path + '">');
    }

    function del_file_upload(obj, file_id) {
        if (!window.confirm('<?php echo $lang['nc_ensure_del'];?>')) {
            return;
        }
        $('#thumb').remove();

    }

    function selectType(obj) {
        $typeid = $(obj).val();
        if ($typeid == 1) {
            $('#fanli').show();
            $('#beout').show();
        } else {
            $('#fanli input').val(0);
            $('#beout input').val(0);
            $('#fanli').hide();
            $('#beout').hide();
        }

        if ($typeid == 6) {
            $('.dlgive').show();
            $('.dlgroup_id').show();
            $('#fanli input').val(0);
            $('#beout input').val(0);
            $('#fanli').hide();
            $('#beout').hide();
        }else{
            $('.dlgive').hide();
            $('.dlgroup_id').hide();
            $('#group_id').val(0);
            $('#give').val(0);
        }
    }

</script>