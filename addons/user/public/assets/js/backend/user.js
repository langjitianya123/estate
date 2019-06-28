define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/index',
                    add_url: '',
                    edit_url: 'user/edit',
                    del_url: 'user/del',
                    multi_url: 'user/multi',
                    table: 'user',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'username', title: __('Username')},
                        {field: 'nickname', title: __('Nickname')},
                        {field: 'email', title: __('Email')},
                        {field: 'mobile', title: __('Mobile')},
                        {field: 'avatar', title: __('Avatar'), formatter: Table.api.formatter.image},
                        {field: 'level', title: __('Level')},
                        {field: 'gender', title: __('Gender'), formatter: function (value) {
                                return value == '1' ? __('Male') : __('Female');
                            }},
                        {field: 'birthday', title: __('Birthday')},
                        {field: 'score', title: __('Score')},
                        {field: 'prevtime', title: __('Prevtime'), operate: false, visible: false, formatter: Table.api.formatter.datetime},
                        {field: 'loginfailure', title: __('Loginfailure'), visible: false},
                        {field: 'logintime', title: __('Logintime'), operate: false, visible: false, formatter: Table.api.formatter.datetime},
                        {field: 'loginip', title: __('Loginip'), visible: false},
                        {field: 'joinip', title: __('Joinip'), formatter: Table.api.formatter.search},
                        {field: 'jointime', title: __('Jointime'), operate: false, formatter: Table.api.formatter.datetime},
                        {field: 'createtime', title: __('Createtime'), operate: false, visible: false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate: false, visible: false, formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status},
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