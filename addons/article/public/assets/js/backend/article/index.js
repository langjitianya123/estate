define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'baidueditor'], function ($, undefined, Backend, Table, Form, UE) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'article/index',
                    add_url: 'article/index/add',
                    edit_url: 'article/index/edit',
                    del_url: 'article/index/del',
                    detail_url: 'article/index/detail',
                    table: 'article',
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
                        {field: 'title', title: __('Title'), align: 'left'},
                        {field: 'category', title: __('Category'),formatter: function (category) {
                            return category.name;
                        }},
                        {field: 'author', title: __('Author')},
                        {field: 'reading', title: __('Reading'), operate: false},
                        {field: 'is_top', title: __('IsTop'),formatter: function (value) {
                            return (value == '1') ? __('Yes') : __('No');
                        }},
                        {field: 'is_recommend', title: __('IsRecommend'),formatter: function (value) {
                            return (value == '1') ? __('Yes') : __('No');
                        }},
                        {field: 'status', title: __('Status'), operate: false, formatter: function (value) {
                            var status = (value == '1') ? 'normal' : 'hidden'
                            return Table.api.formatter.status(status);
                        }},
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