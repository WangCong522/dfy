<?php defined('InShopBN') or exit('Access Invalid!');?>
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="/Public/Home/zTreeStyle/css/demo.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Home/zTreeStyle/css/zTreeStyle.css" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="/Public/Home/assets/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="/Public/Home/assets/global/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="/Public/Home/assets/layouts/layout/css/layout.min.css" rel="stylesheet" type="text/css" />



<style type="text/css">
    table{
        margin: auto;
    }
	body{ background:#FFFFFF;}
</style>
    <script type="text/javascript" src="/Public/Home/zTreeStyle/js/jquery.ztree.core.js"></script>
    <script type="text/javascript" src="/Public/Home/zTreeStyle/js/jquery.ztree.excheck.js"></script>
    <script type="text/javascript" src="/Public/Home/zTreeStyle/js/jquery.ztree.exedit.js"></script>
    <style type="text/css">
    a {
        cursor: pointer;
        color: yellow;
    }
    
    .red a{
        color: red;
    }
    </style>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <span>直推图谱</span>
            </li>
        </ul>
    </div>
    <div class="row">

        <div><input type="text" id="key" placeholder="请输入要搜索的用户账号"
                    style="width:
        20%;
            margin-left:5% ;" onchange="location.href='index' +
             '.php?act=pushmap&op=index&username='+this.value"></div>
        <div>
            <ul id="treeDemo" class="ztree" style="width: 90%;height: 100%;
            margin-left:5% ;"></ul>
            <script>
                <!--
                var setting = {
                    async: {
                        enable: true,
                        url: getUrl
                    },
                    check: {
                        enable: false
                    },
                    data: {
                        simpleData: {
                            enable: true
                        }
                    },
                    view: {
                        expandSpeed: ""
                    },
                    callback: {
                        beforeExpand: beforeExpand,
                        onAsyncSuccess: onAsyncSuccess,
                        onAsyncError: onAsyncError
                    }
                };

                var zNodes =[
                    <?php foreach ($output['info'] as $value):?>
                      <?php if($value['count'] == 0):?>
                         {name:"<?=$value['name']?>", id:"<?=$value['user_id']?>", count:<?=$value['count']?>, times:1,},
                      <?php else:?>
                         {name:"<?=$value['name']?>", id:"<?=$value['user_id']?>", count:<?=$value['count']?>, times:1, isParent:true},
                      <?php endif;?>
                    <?php endforeach;?>
                ];

                var className = "dark", startTime = 0, endTime = 0, perCount
                    = 100, perTime = 100;
                function getUrl(treeId, treeNode) {
                    var curCount = (treeNode.children) ? treeNode.children.length : 0;
                    var getCount = (curCount + perCount) > treeNode.count ? (treeNode.count - curCount) : perCount;
                    var param = "pid="+treeNode.id+"&pCount="+getCount;
                    return "index.php?act=pushmap&op=getDownByAsyns&" + param;
                }
                function beforeExpand(treeId, treeNode) {
                    if (!treeNode.isAjaxing) {
                        startTime = new Date();
                        treeNode.times = 1;
                        ajaxGetNodes(treeNode, "refresh");
                        return true;
                    } else {
                        alert("加载下级,请稍等...");
                        return false;
                    }
                }
                function onAsyncSuccess(event, treeId, treeNode, msg) {
                    if (!msg || msg.length == 0) {
                        return;
                    }

                    var zTree = $.fn.zTree.getZTreeObj("treeDemo"),
                        totalCount = treeNode.count;
                    if (treeNode.children.length < totalCount) {
                        setTimeout(function() {ajaxGetNodes(treeNode);}, perTime);
                    } else {
                        treeNode.icon = "";
                        zTree.updateNode(treeNode);
                        zTree.selectNode(treeNode.children[0]);
                        endTime = new Date();
                        var usedTime = (endTime.getTime() - startTime.getTime())/1000;
                        className = (className === "dark" ? "":"dark");
                    }
                }
                function onAsyncError(event, treeId, treeNode, XMLHttpRequest, textStatus, errorThrown) {
                    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                    alert("ajax error...");
                    treeNode.icon = "";
                    zTree.updateNode(treeNode);
                }
                function ajaxGetNodes(treeNode, reloadType) {
                    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                    if (reloadType == "refresh") {
                        treeNode.icon = "/Public/Home/zTreeStyle/img/loading.gif";
                        zTree.updateNode(treeNode);
                    }
                    zTree.reAsyncChildNodes(treeNode, reloadType, true);
                }
                function getTime() {
                    var now= new Date(),
                        h=now.getHours(),
                        m=now.getMinutes(),
                        s=now.getSeconds(),
                        ms=now.getMilliseconds();
                    return (h+":"+m+":"+s+ " " +ms);
                }

                $(document).ready(function(){
                    $.fn.zTree.init($("#treeDemo"), setting, zNodes);

                });
            </script>

        </div>
    </div>

</body>

</html>