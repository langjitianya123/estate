define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'baidueditor'], function ($, undefined, Backend, Table, Form, UE) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'owners/pet',
                    add_url: 'owners/pet/add',
                    edit_url: 'owners/pet/edit',
                    del_url: 'owners/pet/del',
                    table: 'owners',
                    //设置不同操作下的弹窗宽高
                    area: {
                        add:['800px','500px'],
                        edit:['800px','500px']
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
                sortOrder: 'asc',
                pagination: true,
                pageSize: 10,
                commonSearch: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'photo', title: __('Photo'), operate: false, formatter: Table.api.formatter.image},
                        {field: 'member', title: __('Member'), formatter: function (member) {
                            if(member) {
                                return member.name;
                            }
                            return '';
                        }},
                        {field: 'name', title: __('Name'), operate: false},
                        {field: 'color', title: __('Color'), operate: false},
                        {field: 'remark', title: __('Remark'), operate: false},
                        {field: 'adopt_time', title: __('AdoptTime'),formatter: Table.api.formatter.datetime},
                        {field: 'create_time', title: __('CreateTime'),formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
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
        handleCommunityState: function () {
            var member_id = $("#member_id").val();
            $("#community_code").bind("change",function(){
                var community_code = $(this).val();
                $.ajax({
                    type: "POST",
                    url: 'owners/pet/get_member_by_cm_code',
                    async: true,
                    cache: false,
                    dataType : "json",
                    data: {community_code:community_code},
                    success: function(data) {
                        var member = data.member;

                        var memberHtml = '';
                        $.each(member,function(index,item){
                            memberHtml += '<option value="'+item.id+'">'+item.name+'</option>';
                        });
                        if(memberHtml == ''){
                            memberHtml = '<option value="">没有任何选中项</option>';
                        }
                        $("#member_code").html(memberHtml);
                        if (member_id) {
                            $("#member_code").val(member_id);
                        }
                        $("#member_code").selectpicker({
                            showTick:true,
                            liveSearch:true
                        });
                        $("#member_code").selectpicker("refresh");
                    }
                });
            });
        }
    };
    return Controller;
});