<?php defined('InShopBN') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <div class="subject">
                <h3><?php echo $lang['article_index_manage']; ?></h3>
                <h5><?php echo $lang['article_index_manage_subhead']; ?></h5>
            </div>
        </div>
    </div>
    <!-- 操作说明 -->
    <div class="explanation" id="explanation">
        <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
            <h4 title="<?php echo $lang['nc_prompts_title']; ?>"><?php echo $lang['nc_prompts']; ?></h4>
            <span id="explanationZoom" title="<?php echo $lang['nc_prompts_span']; ?>"></span></div>
        <ul>
            <li><?php echo $lang['article_index_help1']; ?></li>
        </ul>
    </div>
    <div id="flexigrid"></div>
</div>
<script type="text/javascript">
    $(function () {
        $("#flexigrid").flexigrid({
            url: 'index.php?act=article&op=get_goods_class_xml',
            colModel: [
                {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
                {display: '分类ID', name: 'gid', width: 150, sortable: false, align: 'left'},
                {display: '分类名称', name: 'title', width: 240, sortable: false, align: 'left'},
            ],
            buttons: [
                {
                    display: '<i class="fa fa-plus"></i>新增数据',
                    name: 'add',
                    bclass: 'add',
                    title: '新增数据',
                    onpress: fg_operate
                },
                {
                    display: '<i class="fa fa-trash"></i>批量删除',
                    name: 'del',
                    bclass: 'del',
                    title: '将选定行数据批量删除',
                    onpress: fg_operate
                }
            ],
            sortname: "id",
            sortorder: "asc",
            title: '分类列表'
        });
        $('input[name="q"]').prop('placeholder', '搜索标题内容...');
    });
    function fg_operate(name, bDiv) {
        if (name == 'del') {
            if ($('.trSelected', bDiv).length > 0) {
                var itemlist = new Array();
                $('.trSelected', bDiv).each(function () {
                    itemlist.push($(this).attr('data-id'));
                });
                fg_delete(itemlist);
            } else {
                return false;
            }
        } else if (name == 'add') {
            window.location.href = 'index.php?act=article&op=article_class_add';
        }
    }

    function fg_delete(id) {
        if (typeof id == 'number') {
            var id = new Array(id.toString());
        }
        ;
        if (confirm('删除后将不能恢复，确认删除这 ' + id.length + ' 项吗？')) {
            id = id.join(',');
        } else {
            return false;
        }
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "index.php?act=article&op=article_class_delete",
            data: "del_id=" + id,
            success: function (data) {
                if (data.state) {
                    $("#flexigrid").flexReload();
                } else {
                    alert(data.msg);
                }
            }
        });
    }
</script> 
