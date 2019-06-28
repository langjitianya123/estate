define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'baidueditor'], function ($, undefined, Backend, Table, Form, UE) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'service/activity',
                    add_url: 'service/activity/add',
                    edit_url: 'service/activity/edit',
                    del_url: 'service/activity/del',
                    detail_url: 'service/activity/detail',
                    table: 'service'
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                pk: 'id',
                sortName: 'create_time',
                sortOrder: 'desc',
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
                        {field: 'title', title: __('Title'), operate: false},
                        {field: 'place', title: __('Place'), operate: false},
                        {field: 'sponsor_unit', title: __('SponsorUnit'), operate: false},
                        {field: 'status', title: __('Status'), operate: false, formatter: function (value) {
                            var statuses = ['InValid','Valid']
                            return Table.api.formatter.status(statuses[value]);
                        }},
                        {field: 'begin_time', title: __('BeginTime'),formatter: Table.api.formatter.datetime},
                        {field: 'end_time', title: __('EndTime'),formatter: Table.api.formatter.datetime},
                        {field: 'create_time', title: __('CreateTime'),formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();
            $("#common_search").bind("click",function () {
                table.bootstrapTable('refresh',{
                    url: $.fn.bootstrapTable.defaults.extend.index_url,
                    pageNumber: 1
                });
            });
        },
        detail: function () {
            var editor = UE.getEditor('container');
            Controller.api.bindevent();
        },
        add: function () {
            var editor = UE.getEditor('container');
            Controller.api.bindevent();
        },
        edit: function () {
            var editor = UE.getEditor('container');
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});