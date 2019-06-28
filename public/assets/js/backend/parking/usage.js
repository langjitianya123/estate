define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'baidueditor'], function ($, undefined, Backend, Table, Form, UE) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'parking/usage',
                    add_url: 'parking/usage/add',
                    edit_url: 'parking/usage/edit',
                    del_url: 'parking/usage/del',
                    table: 'parking'
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
                        {field: 'parking', title: __('Parking'), formatter: function (parking) {
                            if(parking) {
                                return parking.name;
                            }
                            return '';
                        }},
                        {field: 'license_plate', title: __('LicensePlate'), operate: false},
                        {field: 'owner', title: __('Owner'), operate: false},
                        {field: 'tel', title: __('Tel'), operate: false},
                        {field: 'type', title: __('Type'), operate: false, formatter: function (value) {
                            var types = ['','Lease','Sale']
                            return Table.api.formatter.status(types[value]);
                        }},
                        {field: 'cost', title: __('Cost')+"（&yen;）", operate: false,formatter: Table.api.formatter.amount},
                        {field: 'begin_time', title: __('BeginTime'),formatter: Table.api.formatter.date},
                        {field: 'end_time', title: __('EndTime'),formatter: Table.api.formatter.date},
                        {field: 'create_time', title: __('CreateTime'),formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.handleCommunityState(true);
            $("#community_code").change();
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
        handleCommunityState: function (showAll) {
            var parking_id = $("#parking_id").val();
            $("#community_code").bind("change",function(){
                var community_code = $(this).val();
                var param = {community_code:community_code,parking_code:parking_id};
                if(showAll) {
                    param.status = 1;
                }
                $.ajax({
                    type: "POST",
                    url: 'parking/usage/get_parking_by_cm_code',
                    async: true,
                    cache: false,
                    dataType : "json",
                    data: param,
                    success: function(data) {
                        var parking = data.parking;

                        var parkingHtml = showAll ? '<option value="">全部</option>' : '';
                        $.each(parking,function(index,item){
                            parkingHtml += '<option value="'+item.code+'">'+item.name+'</option>';
                        });
                        if(parkingHtml == ''){
                            parkingHtml = '<option value="">没有任何选中项</option>';
                        }
                        $("#pk_code").html(parkingHtml);
                        if (parking_id) {
                            $("#pk_code").val(parking_id);
                        }
                        $("#pk_code").selectpicker({
                            showTick:true,
                            liveSearch:true
                        });
                        $("#pk_code").selectpicker("refresh");
                    }
                });
            });
        }
    };
    return Controller;
});