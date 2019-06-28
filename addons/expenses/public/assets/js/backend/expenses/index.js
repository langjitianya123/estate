define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'baidueditor'], function ($, undefined, Backend, Table, Form, UE) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'expenses/index',
                    add_url: 'expenses/index/add',
                    edit_url: 'expenses/index/edit',
                    del_url: 'expenses/index/del',
                    table: 'expenses',
                    //设置不同操作下的弹窗宽高
                    area: {
                        add:['800px','450px'],
                        edit:['800px','450px']
                    }
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                pk: 'id',
                sortName: 'create_time',
                pagination: true,
                pageSize: 10,
                commonSearch: false,
                queryParams: function queryParams(params) {
                    var searchForm = $("form[role=form]");
                    if(searchForm.length){
                        var searchFields = searchForm.serializeArray();
                        for(var i=0;i<searchFields.length;i++) {
                            if(searchFields[i]['value']) {
                                params[searchFields[i]['name']] = searchFields[i]['value'];
                            }
                        }
                    }
                    return params;
                },
                queryParamsType : "limit",
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'community', title: __('Community'), formatter: function (community) {
                            if(community) {
                                return community.name;
                            }
                            return '';
                        }},
                        {field: 'project', title: __('Project'),  formatter: function (project) {
                            if(project) {
                                return project.name;
                            }
                            return '';
                        }},
                        {field: 'house', title: __('House'), formatter: function (house) {
                            if(house) {
                                return house.name;
                            }
                            return '';
                        }},
                        {field: 'amount_total', title: __('AmountTotal')+'（&yen;）', operate: false, formatter: Table.api.formatter.amount},
                        {field: 'amount_paid', title: __('AmountPaid')+'（&yen;）', operate: false, formatter: Table.api.formatter.amount},
                        {field: 'remark', title: __('Remark'), operate: false},
                        {field: 'create_time', title: __('CreateTime'),formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.handleCommunityState(true);
            $("#community_code").change();
            Controller.api.bindevent();
            $("#common_search").bind("click",function () {
                table.bootstrapTable('refresh',{
                    url: $.fn.bootstrapTable.defaults.extend.index_url,
                    pageNumber: 1
                });
            });
        },
        detail: function () {
            Controller.api.bindevent();
        },
        add: function () {
            Controller.handleCommunityState();
            $("#community_code").change();
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.handleCommunityState();
            $("#community_code").change();
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        },
        handleCommunityState: function (showAll) {
            var house_id = $("#house_id").val();
            var project_id = $("#project_id").val();
            $("#community_code").bind("change",function(){
                var community_code = $(this).val();
                $.ajax({
                    type: "POST",
                    url: 'expenses/index/get_project_by_cm_code',
                    async: true,
                    cache: false,
                    dataType : "json",
                    data: {community_code:community_code},
                    success: function(data) {
                        var project = data.project;
                        var house = data.house;

                        var projectHtml = showAll ? '<option value="">全部</option>' : '';
                        $.each(project,function(index,item){
                            projectHtml += '<option value="'+item.code+'">'+item.name+'</option>';
                        });
                        if(projectHtml == ''){
                            projectHtml = '<option value="">没有任何选中项</option>';
                        }
                        $("#project_code").html(projectHtml);
                        if (project_id) {
                            $("#project_code").val(project_id);
                        }
                        $("#project_code").selectpicker({
                            showTick:true,
                            liveSearch:true
                        });
                        $("#project_code").selectpicker("refresh");

                        var houseHtml = showAll ? '<option value="">全部</option>' : '';
                        $.each(house,function(index,item){
                            houseHtml += '<option value="'+item.code+'">'+item.name+'</option>';
                        });
                        if(houseHtml == ''){
                            houseHtml = '<option value="">没有任何选中项</option>';
                        }
                        $("#house_code").html(houseHtml);
                        if (house_id) {
                            $("#house_code").val(house_id);
                        }
                        $("#house_code").selectpicker({
                            showTick:true,
                            liveSearch:true
                        });
                        $("#house_code").selectpicker("refresh");
                    }
                });
            });
        }
    };
    return Controller;
});