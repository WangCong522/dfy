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
        <input type="hidden" name="id" value="<?php echo $output['classInfo']['id']; ?>"/>
        <div class="ncap-form-default">
            <dl class="row">
                <dt class="tit">
                    <label><em>*</em>分类名称:</label>
                </dt>
                <dd class="opt">
                    <input type="text" value="<?php echo $output['classInfo']['typename']; ?>" name="typename"
                           id="typename" class="input-txt" required>
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
    });
</script>