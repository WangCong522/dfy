<?php defined('InShopBN') or exit('Access Invalid!');?>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="/">首页</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>我推荐过的人</span>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="portlet-body">
            <ul class="nav nav-tabs">
                <li class="{$link}">
                    <a href="#tab_1_1" class="link1" data-toggle="tab" aria-expanded="true">我推荐过的人</a>
                </li>
                <li class="{$link2}">
                    <a href="#tab_1_2" class="link2" data-toggle="tab" aria-expanded="false">推荐我的人</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade active in" id="tab_1_1">
                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>会员编号</th>
                                        <th>手机号</th>
                                       
                                        <th>接点人</th>
										<th>区域</th>
                                        <th>账号级别</th>
                                        <th>日期</th>
                                         
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
									    if(is_array($output['list'])){
										    foreach($output['list'] as $val){
									?>
                                        <tr>
                                            <td>{$vo.num}</td>
                                            <td>{$vo.details.tel}</td>
                                            
                                            <td>{$vo.jdr_name}</td>
											 <td>{$vo.zone}</td>
                                            <td>
                                                <span class="label label-sm label-success">{$vo.level}</span>
                                            </td>
                                            <td>{$vo.time}</td>
                                             
                                        </tr>
                                    <?php
											}
										}
									?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade {$link2} in" id="tab_1_2">
                    <div class="portlet-body">
                        <div class="table-scrollable">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>会员编号</th>
                                        <th>用户名</th>
                                        <th>手机号</th>
                                        
                                        <th>接点人</th>
                                        <th>账号级别</th>
                                        <th>注册日期</th>
                                         
                                    </tr>
                                </thead>
                                <tbody>
									<?php
									    if(is_array($output['list'])){
										    foreach($output['list'] as $val){
									?>
                                     
                                        <tr>
                                            <td>{$carray.num}</td>
                                            <td>{$carray.uname}</td>
                                            <td>{$carray.details.tel}</td>
                                           
                                            <td>{$carray.jdr_name}</td>
                                            <td>
                                                <span class="label label-sm label-success">{$vo.level}</span>
                                            </td>
                                            <td>{$carray.time}</td>
                                           
                                        </tr>
                                     <?php
											}
										}
									?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>