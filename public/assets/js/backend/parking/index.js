define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'baidueditor'], function ($, undefined, Backend, Table, Form, UE) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'parking/index',
                    add_url: 'parking/index/add',
                    edit_url: 'parking/index/edit',
                    del_url: 'parking/index/del',
                    table: 'parking',
                    //设置不同操作下的弹窗宽高
                    area: {
                        add:['800px','350px'],
                        edit:['800px','350px']
                    }
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                pk: 'id',
                sortName: 'community_code,status',
                sortOrder: 'asc,asc',
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
                        {field: 'code', title: __('Code'), operate: false},
                        {field: 'name', title: __('Name'), operate: false},
                        {field: 'status', title: __('Status'), operate: false, formatter: function (value) {
                            var statuses = ['Idling','Using'];
                            return Table.api.formatter.status(statuses[value]);
                        }},
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
            Controller.api.bindevent();
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
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