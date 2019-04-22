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
            url: 'index.php?act=orders&op=get_xml',
            colModel: [
                {display: '操作', name: 'operation', width: 150, sortable: false, align: 'center', className: 'handle'},
                {display: '状态', name: 'is_actived', width: 60, sortable: true, align: 'center'},
                {display: '订单ID', name: 'order_id', width: 80, sortable: false, align: 'center'},
                {display: '用户名', name: 'username', width: 100, sortable: false, align: 'center'},
                {display: '商品名称', name: 'goods_name', width: 240, sortable: false, align: 'center'},
                {display: '单价', name: 'unit_price', width: 80, sortable: false, align: 'center'},
                {display: '数量', name: 'num', width: 80, sortable: false, align: 'center'},
                {display: '总价', name: 'total', width: 80, sortable: false, align: 'center'},
                {display: '添加时间', name: 'addtime', width: 130, sortable: false, align: 'center'},
                {display: '收货人', name: 'consignee', width: 80, sortable: false, align: 'center'},
                {display: '联系电话', name: 'tel', width: 80, sortable: false, align: 'center'},
                {display: '收货地址', name: 'address', width: 350, sortable: false, align: 'center'},
                {display: '物流单号', name: 'express', width: 350, sortable: false, align: 'center'}
            ],
            buttons: [
                {
                    display: '<i class="fa fa-trash"></i>批量删除',
                    name: 'del',
                    bclass: 'del',
                    title: '将选定行数据批量删除',
                    onpress: fg_operate
                }
            ],
            sortname: "order_id",
            sortorder: "desc",
            title: '订单列表'
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
            window.location.href = 'index.php?act=goods&op=goods_add';
        }
    }
    /*
     function deliver_goods(id) {
     if (typeof id == 'number') {
     var id = new Array(id.toString());
     }
     if (confirm('确认发货？')) {
     $.ajax({
     type: "GET",
     dataType: "json",
     url: "index.php?act=orders&op=deliver_goods&order_id=" + id,
     success: function (data) {
     if (data.state) {
     $("#flexigrid").flexReload();
     } else {
     alert(data.msg);
     }
     }
     });
     } else {
     return false;
     }
     }
     */
    function deliver_goods(id) {
        var express = prompt("请输入快递名称和单号", ""); //将输入的内容赋给变量 name ，
        //这里需要注意的是，prompt有两个参数，前面是提示的话，后面是当对话框出来后，在对话框里的默认值
        if (express)//如果返回的有内容
        {
            if (typeof id == 'number') {
                var id = new Array(id.toString());
            }
            $.ajax({
                type: "GET",
                dataType: "json",
                url: "index.php?act=orders&op=deliver_goods&order_id=" + id,
                data: {express: express},
                success: function (data) {
                    if (data.state) {
                        $("#flexigrid").flexReload();
                    } else {
                        alert(data.msg);
                    }
                }
            });
        }
    }
</script> 
