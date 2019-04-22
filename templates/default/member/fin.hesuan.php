<?php defined('InShopBN') or exit('Access Invalid!');?>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">首页</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>核算</span>
            </li>
        </ul>
    </div>
    <div class="row ">
        <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
        </div>
        
        
            <div class="form-group">
                <label class="col-sm-5 control-label">  </label>
                <div class="col-sm-2">
                   
                </div>
                <div class="col-sm-5" id="__extension__">
                    <div class="form-control-static"></div>
                </div>
            </div>
            <div class="form-group">
			 <label class="col-sm-2 control-label">  </label>
					 <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <div class="mt-element-ribbon bg-grey-steel" style="padding-left: 0px;padding-top: 0px;padding-bottom:5px;">
                                            <div class="row">
                                                <div class="col-xs-3">
                                                        <img src="/Public/Home/images/header1.jpg" style="" alt="..."  class="img-responsive img-circle">
                                                </div>
                                                
                                            </div>
                                            <div style="margin-left: 15px;pdding:15px;margin-top:5px;" class="info">
											<br/>
											<br/>
											<style>
												table,tr,td{border:solid 1px #e7e7e7;}
												
											</style>
                                                    <table style="width:400px; margin:0px auto;">
													<tr> 
														<td>
														</td>
														</tr>
													 
													<tr>
														<td style="padding:8px;text-align:center; color:#286090; "> 
																应得奖金
														</td>
														<td style="padding:8px;text-align:center; color:#286090; ">
																当前奖金
														</td>
														</tr>
													<tr>
													<td style="width:200px;">
													<label>层碰奖应得个数：</label><span style="color:green" id="cengpengs"><?php echo $output['info']['yd']['ceng'];?></span><br/>
													<label>推荐奖应得个数：</label><span style="color:green" id="tuijians"><?php echo $output['info']['yd']['tui'];?></span><br/>
													<label>见点奖应得个数：</label><span style="color:green" id="jiandians"><?php echo $output['info']['yd']['jian'];?></span><br/>
													<label>电子原始股应得个数：</label><span style="color:green" id="yuanshis"><?php echo $output['info']['yd']['xu'];?></span><br/>
													</td>
													<td style="width:200px;">
													<label>层碰奖实际得到个数：</label><span style="color:red" id="scengpengs"></span><?php echo $output['info']['sd']['ceng'];?><br/>
													<label>推荐奖实际得到个数：</label><span style="color:red" id="stuijians"><?php echo $output['info']['sd']['tui'];?></span><br/>
													<label>见点奖实际得到个数：</label><span style="color:red" id="sjiandians"><?php echo $output['info']['sd']['jian'];?></span><br/>
													<label>电子原始股实际得到个数：</label><span style="color:red" id="syuanshis"><?php echo $output['info']['sd']['xu'];?></span><br/>
													</td>
													</tr>
														<tr>
														<td>
														</td>
														<td   style="padding:8px;text-align:center; color:#000000"> 
																<div id="jjjzzz" <?php if(!$output['info']['do']){?>style="display:none;"<?php }?>>
																 <button class=" btn-primary btn" id="jiaozhun" name="jiaozhun"><span 	class="glyphicon  glyphicon-floppy-disk"></span>结算</button>
																</div>
														</td>
														 
														
														</tr>													
													</table>	
                                            </div>
                                             
                                            <div class="ribbon ribbon-right ribbon-clip ribbon-shadow ribbon-round ribbon-border-dash-hor ribbon-color-info uppercase">
                                                    <div class="ribbon-sub ribbon-clip ribbon-right"></div>核算信息</div>
                                                
                                            </div>
					</div>		
	
				 <label class="col-sm-2 control-label">  </label>
            </div>
             
           
    
    </div>

    <script>
		$(function(){
			$("#hesuan").click(function(){
				$.post("index.php?act=fin&op=getinfo",{},function(data){
					$("#cengpengs").text(data.ceng_num);
					$("#scengpengs").text(data.s_ceng_num);
					$("#tuijians").text(data.Tui_num);
					$("#stuijians").text(data.s_Tui_num);
					$("#jiandians").text(data.jian_num);
					$("#sjiandians").text(data.s_jian_num);		
					$("#yuanshis").text(data.dian_num);
					$("#syuanshis").text(data.s_dian_num);
	                var pcc=(data.ceng_num-data.s_ceng_num)+(data.Tui_num-data.s_Tui_num)+(data.jian_num-data.s_jian_num)+(data.dian_num-data.s_dian_num);
					if(pcc>0){
						$("#jjjzzz").show();
					}					
				});
			});
			$("#jiaozhun").click(function(){
				$.post("index.php?act=fin&op=AddBlance",{},function(data){
					if(data.status){
						 swal("校准成功", "校准成功", "success");
						 $("#hesuan").click();
					}else{
						 swal("校准失败", data.info, "warning");
					}
				},'json')
			});
		});
    </script>