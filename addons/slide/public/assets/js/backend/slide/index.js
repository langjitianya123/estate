define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'slide/index',
                    add_url: 'slide/index/add',
                    edit_url: 'slide/index/edit',
                    del_url: 'slide/index/del',
                    detail_url: 'slide/index/detail',
                    table: 'slideCategory',
                    //设置不同操作下的弹窗宽高
                    area: {
                        add:['800px','250px'],
                        edit:['800px','250px'],
                        //detail:['100%','100%']
                    }
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                pk: 'id',
                sortName: 'name',
                pagination: true,
                pageSize: 10,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('CateType'), operate: false},
                        {field: 'code', title: __('SlideCode'), operate: false},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        detail: function () {
            // 初始化表格参数配置
            Table.api.init({
                search: false,
                extend: {
                    index_url: 'slide/index/detail/ids/'+$("#cid").val(),
                    add_url: 'slide/detail/add/ids/'+$("#cid").val(),
                    edit_url: 'slide/detail/edit',
                    del_url: 'slide/detail/del',
                    table: 'slide',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                pk: 'id',
                sortName: 'sort',
                pagination: false,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'image', title: __('Image'), operate: false, formatter: Table.api.formatter.image},
                        {field: 'name', title: __('SlideName'), align: 'left'},
                        {field: 'description', title: __('Description'), operate: false},
                        {field: 'status', title: __('Status'), operate: false, formatter: function (value) {
                            var status = (value == '1') ? 'normal' : 'hidden'
                            return Table.api.formatter.status(status);
                        }},
                        {field: 'link', title: __('Link')},
                        {field: 'target', title: __('Target'),formatter: function (value){
                            var target = {
                                _self:"当前窗口",
                                _blank:"新窗口"
                            };
                            return target[value];
                        }},
                        {field: 'sort', title: __('Sort')},
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