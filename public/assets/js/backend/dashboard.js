define(['jquery', 'bootstrap', 'backend', 'addtabs', 'table', 'echarts', 'echarts-theme', 'template', 'form'], function ($, undefined, Backend, Datatable, Table, Echarts, undefined, Template, Form) {
    var Controller = {
        index: function () {
            var myChart = Echarts.init(document.getElementById('echart'), 'walden');
            Controller.handleCommunityState(myChart);
            $("#community_code").change();
            $(window).resize(function () {
                myChart.resize();
            });
            Controller.api.bindevent();
        },
        handleCommunityState: function (chartInstace) {
            $("#community_code").bind("change",function () {
                Controller.getGeneralData($(this).val());
                Controller.getExpenses($(this).val(),chartInstace);
                Controller.getActivity($(this).val());
                Controller.getRepair($(this).val());
            });
        },
        getGeneralData: function (code) {
            var param = {};
            if (code) {
                param.code = code;
            }
            $.ajax({
                url: "dashboard/get_general_data",
                type: "post",
                dataType: "json",
                data: param,
                success: function (ret) {
                    $("#total_building").html(ret.total_building);
                    $("#total_house").html(ret.total_house);
                    $("#total_member").html(ret.total_member);
                    $("#total_lease").html(ret.total_lease);
                    $("#total_parking_space").html(ret.total_parking_space);
                    $("#parking_space_percent").html(ret.parking_space_percent);
                    $("#total_vehicle").html(ret.total_vehicle);
                    $("#total_pet").html(ret.total_pet);
                    $("#total_repair_pending").html(ret.total_repair_pending);
                    $("#total_repair_processing").html(ret.total_repair_processing);
                }
            });
        },
        //获取每月收费统计数据
        getExpenses: function (code,chartInstace) {
            var param = {};
            if (code) {
                param.code = code;
            }
            $.ajax({
                url: "dashboard/get_expenses",
                type: "post",
                dataType: "json",
                data: param,
                success: function (ret) {
                    Controller.renderChart(chartInstace,ret);
                }
            });
        },
        //获取小区最新活动
        getActivity: function (code) {
            var param = {};
            if (code) {
                param.code = code;
            }
            $.ajax({
                url: "dashboard/get_activity",
                type: "post",
                dataType: "html",
                data: param,
                success: function (ret) {
                    $("#latest_activity").html(ret);
                    $("#latest_activity").find("a").bind("click",function () {
                        var url = $(this).attr("src");
                        Controller.showDetail(url,"活动详情");
                    });
                }
            });
        },
        //获取最新报修申请
        getRepair: function (code) {
            var param = {};
            if (code) {
                param.code = code;
            }
            $.ajax({
                url: "dashboard/get_repair",
                type: "post",
                dataType: "html",
                data: param,
                success: function (ret) {
                    $("#latest_repair").html(ret);
                    $("#latest_repair").find("a").bind("click",function () {
                        var url = $(this).attr("src");
                        Controller.showDetail(url,"报修明细",['800px','450px']);
                    });
                }
            });
        },
        showDetail: function (url,title,area) {
            var options = null;
            if(area) {
                options = {
                    area: area
                };
            }
            Fast.api.open(url, title, options);
        },
        //根据需要，可以通过Ajax获取数据，然后动态填充
        refurbish: function (chartInstace) {
            setInterval(function () {
                chartInstace.setOption({
                    xAxis: {
                        data: []
                    },
                    series: []
                });
            }, 2000);
        },
        //渲染图表
        renderChart: function (chartInstace,options) {
            //加载进度
            chartInstace.showLoading({
                text: 'loading',
                effect : 'whirling',//spin,bar,ring,whirling,dynamicLine
                effectOption:{
                    backgroundColor:"#FAFAFA"
                },
            });
            // 指定图表的配置项和数据
            var option = {
                title : {
                    text: options.title ? options.title : '',
                    subtext: options.subTitle ? options.subTitle : '',
                    x:'center',
                    y:'top',
                    textStyle:{
                        fontSize: 15,
                        fontWeight: 'bolder'
                    },
                    padding: 0
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: options.legend,
                    orient:'horizontal', //图例布局方式,可选值为:'horizontal','vertical'
                    x:'center',
                    y:'bottom',
                    textStyle:{
                        fontSize: 12
                    }
                },
                //是否显示工具栏
                toolbox: {
                    show : false,
                    feature : {
                        magicType : {show: true, type: ['line', 'bar']},
                        saveAsImage : {show: true}
                    },
                    padding : 15
                },
                xAxis: [{
                    type: 'category',
                    boundaryGap: false,
                    data: options.xAxis
                }],
                yAxis: [{
                    type : 'value',
                    axisLabel : {
                        formatter : '{value}'+options.units,
                        textStyle : {
                            fontSize : 10
                        }
                    },
                    //splitLine : false,	//是否显示纵向分割线
                    splitArea : false	//是否显示纵向分割区域
                }],
                grid: [{
                    left: 'left',
                    top: 'top',
                    right: '10',
                    bottom: 30
                }],
                series: options.series,
                grid : {
                    x:'50', 	//图表顶部边距,单位px
                    y:'70',  	//图表左侧边距,单位px
                    x2:'50', 	//图表右侧边距,单位px
                    y2:'60'  	//图表底部边距,单位px
                }
            };
            setTimeout(function(){
                chartInstace.hideLoading();
                // 使用刚指定的配置项和数据显示图表。
                chartInstace.setOption(option);
            },1000)
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };

    return Controller;
});