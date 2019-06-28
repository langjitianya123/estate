define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'baidueditor'], function ($, undefined, Backend, Table, Form, UE) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'community/index',
                    add_url: 'community/index/add',
                    edit_url: 'community/index/edit',
                    del_url: 'community/index/del',
                    detail_url: 'community/index/detail',
                    table: 'community',
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
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'thumb', title: __('Thumb'), operate: false, formatter: Table.api.formatter.image},
                        {field: 'code', title: __('Code'), operate: false},
                        {field: 'name', title: __('Name'), operate: false},
                        {field: 'address', title: __('Address'), operate: false},
                        {field: 'area', title: __('Area')+'（m<sup>2</sup>）', operate: false},
                        {field: 'total_building', title: __('TotalBuilding'), operate: false},
                        {field: 'total_owner', title: __('TotalOwner'), operate: false},
                        {field: 'greening_rate', title: __('GreeningRate')+'（%）', operate: false},
                        {field: 'developer', title: __('Developer'), operate: false},
                        {field: 'estate', title: __('Estate'), operate: false},
                        {field: 'create_time', title: __('CreateTime'),formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
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