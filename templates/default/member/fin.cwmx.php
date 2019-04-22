<?php defined('InShopBN') or exit('Access Invalid!'); ?>
<style type="text/css">
    /* Styles for basic forms
-----------------------------------------------------------*/

    fieldset {
        border: 1px solid #ddd;
        padding: 0 1.4em 1.4em 1.4em;
        margin: 0 0 1.5em 0;
    }

    legend {
        font-size: 1.2em;
        font-weight: bold;
    }

    textarea {
        min-height: 75px;
    }

    .editor-label {
        margin: 1em 0 0 0;
    }

    .editor-field {
        margin: 0.5em 0 0 0;
    }

    /* Styles for validation helpers
-----------------------------------------------------------*/

    .field-validation-error {
        color: #ff0000;
    }

    .field-validation-valid {
        display: none;
    }

    input.input-validation-error,
    textarea.input-validation-error {
        border: 1px solid #ff0000;
        background-color: #ffeeee;
    }

    .validation-summary-errors {
        font-weight: bold;
        color: #ff0000;
    }

    .validation-summary-valid {
        display: none;
    }

    /*表格*/

    .grid-action-td {
        width: 80px;
        white-space: nowrap;
    }

    .grid-action {
        white-space: nowrap;
    }

    .grid-action a {
        margin: 0 5px;
        white-space: nowrap;
    }

    .table th {
        background-color: #E3EFFB;
        white-space: nowrap;
    }

    /*页面顶部操作栏*/

    #page-operating {
        border-bottom: solid 1px #ccc;
        padding: 10px 60px 10px 20px;
        box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 3px;
        background-color: #fff;
        position: fixed;
        height: 50px;
        top: 0;
        left: 0px;
        width: 100%;
        z-index: 100;
    }

    /*加载等待动画*/

    #sys_shadow {
        position: fixed;
        _position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 99;
    }

    #sys_loading {
        position: fixed;
        _position: absolute;
        top: 50%;
        left: 50%;
        width: 124px;
        height: 124px;
        overflow: hidden;
        background: url(../images/loading.gif) no-repeat;
        z-index: 100;
        margin: -62px 0 0 -62px;
    }

    /*表单*/

    .zmz-form-row {
        margin-bottom: 5px;
    }

    .zmz-form-row-validation {
        margin-bottom: 10px;
        padding-left: 160px;
    }

    .zmz-form-row-label {
        display: inline-block;
        width: 150px;
        text-align: right;
        font-weight: bold;
        margin-right: 5px;
    }

    .zmz-form-row-required {
        color: red;
        display: inline-block;
        margin: 0 5px;
    }

    .zmz-form-row-extension {
        display: inline-block;
        margin-left: 20px;
    }

    #form_search input[type='text'],
    #form_search select,
    .zmz-form-row input[type='text'],
    .zmz-form-row input[type='password'],
    .zmz-form-row textarea,
    .zmz-form-row select {
        display: inline-block;
        height: 34px;
        width: 300px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.428571429;
        color: #555;
        vertical-align: middle;
        background-image: none;
        border: 1px solid #CCC;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        -webkit-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    }

    .zmz-form-tooltip {
        color: #aaa;
    }

    /*查询*/

    #form_search div.form-group {
        margin-bottom: 5px;
        margin-left: 10px;
    }

    #form_search .btn-search {
        margin-left: 20px;
    }

    .caret {
        cursor: pointer;
    }

    .caret-open {
        display: inline-block;
        width: 0;
        height: 0;
        margin-right: 2px;
        vertical-align: middle;
        border-bottom: 4px solid;
        border-right: 4px solid transparent;
        border-left: 4px solid transparent;
        cursor: pointer;
    }

    /*tab页*/

    .zmz-tab {
        border-bottom: 1px solid #5ba2e8;
        margin-bottom: 10px;
    }

    .zmz-tab-item {
        font-weight: bold;
        font-size: 14px;
        margin-right: 5px;
        float: left;
        padding: 5px 20px;
    }

    .zmz-tab-item-selected {
        background: #5ba2e8;
    }

    .zmz-tab-item-normal {
        background: #8FBFEF;
    }

    .zmz-tab a {
        color: #fff;
    }

    .zmz-tab a:hover {
        color: #fff;
        text-decoration: none;
    }
</style>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="/">首页</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>财务记录</span>
        </li>
    </ul>
</div>
<div class="row ">
    <div id="sys_message" style="padding-left:15px;padding-bottom:15px;font-size:16px;color:red; font-family:'微软雅黑';">
    </div>
    <div id="sample_1_wrapper" class="dataTables_wrapper no-footer">
        <div class="table-scrollable">
            <table
                class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer"
                id="sample_1" role="grid" aria-describedby="sample_1_info">
                <thead>
                <tr role="row">
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">流水号</th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">金额</th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">货币类型</th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">日期</th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">类型</th>
                    <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1">描述</th>
                </tr>
                </thead>
                <tbody>
                <?php
                if (is_array($output['list'])) {
                    foreach ($output['list'] as $val) {
                        ?>
                        <tr class="gradeX odd" role="row">
                            <td align="center"><?php echo $val['id']; ?></td>
                            <td align="center" class="">
                                <span style="color: red;"><?php echo $val['money']; ?></span></td>
                            <td align="center" class="sorting_1">
                                <?php echo $val['money_type']; ?></td>
                            <td align="center" class="sorting_1">
                                <?php echo date("Y-m-d H:i:s", $val['time']); ?></td>
                            <td align="center" class="sorting_1">
                                <?php echo $val['type']; ?></td>
                            <td class="center"><?php echo $val['intro']; ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>

        </div>
        <div class="pagination"><?php echo $output['show_page']; ?></div>
    </div>
</div>