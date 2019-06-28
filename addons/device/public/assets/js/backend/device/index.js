define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'device/index',
                    add_url: 'device/index/add',
                    edit_url: 'device/index/edit',
                    del_url: 'device/index/del',
                    detail_url: 'device/index/detail',
                    table: 'device',
                    //设置不同操作下的弹窗宽高
                    area: {
                        add:['800px','450px'],
                        edit:['800px','450px'],
                        detail:['100%','100%']
                    }
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                pk: 'id',
                sortName: 'buy_time',
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
                        {field: 'code', title: __('Code'), operate: false},
                        {field: 'name', title: __('Name'), operate: false},
                        {field: 'brand', title: __('Brand'), operate: false},
                        {field: 'price', title: __('Price')+"（&yen;）", operate: false, formatter: Table.api.formatter.amount},
                        {field: 'quantity', title: __('Quantity'), operate: false},
                        {field: 'buy_time', title: __('BuyTime'),formatter: Table.api.formatter.date},
                        {field: 'durable_years', title: __('DurableYears')+"（年）", operate: false},
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
            // 初始化表格参数配置
            Table.api.init({
                search: false,
                extend: {
                    index_url: 'device/index/detail/ids/'+$("#code").val(),
                    add_url: 'device/history/add/ids/'+$("#code").val(),
                    edit_url: 'device/history/edit',
                    del_url: 'device/history/del',
                    table: 'history',
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
                sortName: 'id',
                sortOrder: 'desc',
                pagination: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'device_code', title: __('Code'), operate: false},
                        {field: 'unit', title: __('Unit'), operate: false},
                        {field: 'contacts', title: __('Contacts'), operate: false},
                        {field: 'contacts_tel', title: __('ContactsTel'), operate: false},
                        {field: 'last_maintain_time', title: __('LastMaintainTime'),formatter: Table.api.formatter.date},
                        {field: 'next_maintain_time', title: __('NextMaintainTime'),formatter: Table.api.formatter.date},
                        {field: 'remark', title: __('Remark'), operate: false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
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