define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'baidueditor'], function ($, undefined, Backend, Table, Form, UE) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'frontendnav/nav',
                    add_url: 'frontendnav/nav/add',
                    edit_url: 'frontendnav/nav/edit',
                    del_url: 'frontendnav/nav/del',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                escape: false,
                pk: 'id',
                sortName: 'sort',
                sortOrder: 'asc',
                pagination: false,
                pageSize: 10,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'cate', title: __('Category'),formatter: function (category) {
                            return category.name;
                        }},
                        {field: 'icon', title: __('Icon'), operate: false, formatter: Table.api.formatter.image},
                        {field: 'name', title: __('Name'), align: 'left'},
                        {field: 'target', title: __('Target'),formatter: function (value){
                            var target = {
                                _self:"当前窗口",
                                _blank:"新窗口"
                            };
                            return target[value];
                        }},
                        {field: 'url', title: __('Url'), align: 'left'},
                        {field: 'status', title: __('Status'), operate: false, formatter: function (value) {
                            var status = (value == '1') ? 'normal' : 'hidden'
                            return Table.api.formatter.status(status);
                        }},
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
                Form.api.bindevent($("form[role=form]"), null, null, function () {
                    if($("#nav_id").val() && $("#nav_id").val() == $("#pid").val()){
                        Toastr.error("当前导航不能作为父级导航");
                        return false;
                    }
                    return true;
                });
            }
        }
    };
    return Controller;
});