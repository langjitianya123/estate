define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'fullcalendar','fullcalendar-lang', 'template'], function ($, undefined, Backend, Table, Form, undefined, undefined, Template) {

    var Controller = {
        index: function () {
            $("#community_code").bind("change",function(){
                $("#calendar_duty").fullCalendar("destroy");
                Controller.loadCalendar();
            });
            $("#community_code").change();
            Controller.api.bindevent();
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        //选择值班人员
        chooseWatch: function(date,eventId,title) {
            var action = 'add';
            var community_code = $("#community_code").val();
            var param = "?code="+community_code;

            if(eventId) {
                action = 'edit/ids/'+eventId;
            }else{
                param += "&selDate="+date;
            }

            var options = {
                area: ['800px','450px']
            };
            Fast.api.open("duty/index/"+action+param, title, options);
        },
        removeWatch: function(eventId,obj) {
            Layer.confirm("确认删除该值班人员吗？", {
                icon: 6,
                btn: ["确定","取消"],
                closeBtn: false
            }, function(index){
                layer.close(index);
                $.ajax({
                    url: 'duty/index/del/ids/'+eventId,
                    dataType: 'json',
                    data: {},
                    success: function(cbData) {
                        obj.parent().remove();
                    }
                });
            });
        },
        //绑定编辑和删除事件
        bindEvents: function() {
            $("#calendar_duty").find(".fc-day-grid-event").each(function(){
                var attr = $(this).attr("class");
                if(attr.indexOf("event_id_") > -1) {
                    var reg = /event_id_.*\d/;
                    var result;
                    if ((result = reg.exec(attr)) != null) {
                        var id = result[0].replace("event_id_","");
                        var subDiv = $(this).children("div");
                        subDiv.eq(0).off("click").on("click",function(){
                            Controller.chooseWatch(null,id,"修改值班人员");
                        });
                        subDiv.eq(1).off("click").on("click",function(){
                            Controller.removeWatch(id,$(this));
                        });
                    }
                }
            });
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"),function(obj, data, ret){
                    parent.Layer.closeAll();
                    parent.$("#community_code").change();
                });
            }
        },
        loadCalendar: function () {
            var currentDate = $("#currentDate").val();
            var community_code = $("#community_code").val();
            $("#calendar_duty").fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listWeek'
                },
                defaultView: 'month',
                defaultDate: currentDate,
                navLinks: true,
                editable: true,
                eventLimit: true,
                events:function(start, end, timezone, callback){
                    $.ajax({
                        url: 'duty/index/index',
                        dataType: 'json',
                        data: {
                            start: start.unix(),
                            end: end.unix(),
                            code: community_code
                        },
                        success: function(cbDatas) {
                            var events = [];
                            $.each(cbDatas,function(index,item){
                                events.push({
                                    id: item.id,
                                    title: item.title,
                                    start: item.start,
                                    end: item.end,
                                    allDay: true,
                                    className: "event_id_"+item.id,
                                    editable: false
                                });
                            });
                            callback(events);
                            $(".fc-button-group").find(".fc-button").each(function(){
                                $(this).on("click",function () {
                                    Controller.bindEvents();
                                });
                            });
                            $(".fc-month-button").click();
                        }
                    });
                },
                loading: function(bool) {
                    $("#calendar_loading").toggle(bool);
                },
                dayClick: function(date, allDay, jsEvent, view) {
                    Controller.chooseWatch(date,null,"新增值班人员");
                },
            });
        }
    };
    return Controller;
});