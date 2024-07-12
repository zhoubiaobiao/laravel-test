<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Sql Excute result') }}
        </h2>
        <div class="layui-btn-container">
            <button type="button" lay-active="exportExcel" class="layui-btn">Export Excel</button>
            <button type="button" lay-active="exportJson" class="layui-btn layui-bg-blue">Export Json</button>
        </div>
    </header>

    <div style="padding: 16px;">
        <table class="layui-hide" id="test" lay-filter="test"></table>
    </div>

    <script src="//cdn.staticfile.net/layui/2.9.14/layui.js"></script>
    <script>

        layui.use(['table', 'util'], function(){
            var table = layui.table;
            var util = layui.util;
            $ = layui.jquery;

            // 创建渲染实例
            table.render({
                elem: '#test',
                url: '/excute',
                // method: 'post',
                toolbar: '#toolbarDemo',
                where: {sql: '{{request('sql')}}'},
                height: 'full-35',
                defaultToolbar: [],
                css: [
                    '.layui-table-tool-temp{padding-right: 145px;}'
                ].join(''),
                cellMinWidth: 80,
                totalRow: true,
                page: true,
                parseData:function(res) {
                    if (res.code == 1) {
                        layer.alert(res.error);
                        res.msg = res.error
                    }
                    return res;
                },
                cols: [[
                    {field:'id', fixed: 'left', title: 'ID'},
                    {field:'name', title: 'Name'},
                    {field:'email', width: 200, title:'Email'},
                    {field:'role', title: 'Role'},
                    {field:'created_at', title: 'Create Time', width: 200},
                    {field:'updated_at', width: 200, title: 'Update Time'},
                ]],
                done: function(){

                },
                error: function(res, msg){
                    console.log(res, msg)
                }
            });

            //处理属性 为 lay-active 的所有元素事件
            util.event('lay-active', {
                exportExcel: function(){
                    $.ajax({
                        type: 'GET',
                        url: '/export',
                        data:{
                            sql: '{{request('sql')}}',
                            type: 'excel'
                        },
                        dataType: "json",
                        success: function (res) {//res为相应体,function为回调函数
                            if (res.code != 0) {
                                layer.alert(res.error);
                            }
                            window.open(res.path);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            layer.alert('操作失败！！！' + XMLHttpRequest.status + "|" + XMLHttpRequest.readyState + "|" + textStatus, { icon: 5 });
                        }
                    });
                }
                ,exportJson: function(){
                    $.ajax({
                        type: 'GET',
                        url: '/export',
                        data:{
                            sql: '{{request('sql')}}',
                            type: 'json'
                        },
                        dataType: "json",
                        success: function (res) {//res为相应体,function为回调函数
                            if (res.code != 0) {
                                layer.alert(res.error);
                            }
                            window.open(res.path);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            layer.alert('操作失败！！！' + XMLHttpRequest.status + "|" + XMLHttpRequest.readyState + "|" + textStatus, { icon: 5 });
                        }
                    });
                }
            });
        });
    </script>
</section>
