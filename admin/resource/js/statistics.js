//导出Excel
function download_excel(obj){
	var tablehtml = $("#datatable").html();
	if(!tablehtml){
		showDialog('暂时没有数据');
		return false;
	}
	var data = $(obj).attr('data-param');
	if(data == undefined  || data.length<=0){
		showDialog('参数错误');
		return false;
	}
	eval("data = "+data);
	go(data.url);
}

//Flexigrid查询导出
function get_search_excel(stat_url,obj){
    stat_url = stat_url+'&exporttype=excel&'+$("#formSearch").serialize();
    get_excel(stat_url,obj);
}

//Flexigrid导出
function get_excel(stat_url,obj){
    obj = $(obj);
    obj.find("tr.trSelected").each(function(){
        var _id = $(this).attr("data-id");
        stat_url = stat_url+'&id[]='+_id;
    });
    window.location.href = stat_url;
}

//统计地图
function getMap(stat_json,obj_id){
	$('#'+obj_id).vectorMap({ map: 'china_zh', color: "#ffd5d5",
        onLabelShow: function (event, label, code) {
            $.each(stat_json, function (i, items) {
                if (code == items.cha) {
                    label.html(items.name + items.des);
                }
            });
        }
	});
	//改变有活动省份区域的颜色
	$.each(stat_json, function (i, items) {
		var jsonStr = "{" + items.cha + ":'"+items.color+"'}";
		$('#'+obj_id).vectorMap('set', 'colors', eval('(' + jsonStr + ')'));
	});
}
