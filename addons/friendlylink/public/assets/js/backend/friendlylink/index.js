define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'baidueditor'], function ($, undefined, Backend, Table, Form, UE) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'friendlylink/index',
                    add_url: 'friendlylink/index/add',
                    edit_url: 'friendlylink/index/edit',
                    del_url: 'friendlylink/index/del',
                    table: 'friendlylink',
                    //设置不同操作下的弹窗宽高
                    area: {
                        add:['800px','400px'],
                        edit:['800px','400px']
                    }
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                pk: 'id',
                sortName: 'sort',
                pagination: true,
                pageSize: 10,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'image', title: __('Image'), operate: false, formatter: Table.api.formatter.image},
                        {field: 'name', title: __('Name'), align: 'left'},
                        {field: 'link', title: __('Link')},
                        {field: 'status', title: __('Status'), operate: false, formatter: function (value) {
                            var status = (value == '1') ? 'normal' : 'hidden'
                            return Table.api.formatter.status(status);
                        }},
                        {field: 'sort', title: __('Sort'), operate: false},
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