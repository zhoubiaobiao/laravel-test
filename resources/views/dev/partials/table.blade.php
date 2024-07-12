<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Sql Excute result') }}
        </h2>
    </header>

    <div style="padding: 16px;">
        <table class="layui-hide" id="test" lay-filter="test"></table>
    </div>

{{--    <table id="myTable" class="display">--}}
{{--        <thead>--}}
{{--        <tr>--}}
{{--            <th>Name</th>--}}
{{--            <th>Email</th>--}}
{{--            <th>Role</th>--}}
{{--            <th>Create</th>--}}
{{--            <th>Update</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        <tbody>--}}
{{--        </tbody>--}}
{{--    </table>--}}
    <script src="//cdn.staticfile.net/layui/2.9.14/layui.js"></script>
    <script>
        // $(function() {
        //     $("#myTable").DataTable({
        //         ajax: '/excute',
        //     });
        // });
        layui.use(['table', 'dropdown'], function(){
            var table = layui.table;

            // 创建渲染实例
            table.render({
                elem: '#test',
                url: '/excute', // 此处为静态模拟数据，实际使用时需换成真实接口
                // method: 'post',
                toolbar: '#toolbarDemo',
                where: {sql: '{{request('sql')}}'},
                defaultToolbar: ['exports'],
                height: 'full-35', // 最大高度减去其他容器已占有的高度差
                css: [ // 重设当前表格样式
                    '.layui-table-tool-temp{padding-right: 145px;}'
                ].join(''),
                cellMinWidth: 80,
                totalRow: true, // 开启合计行
                page: true,
                parseData:function(res) {
                    if (res.code == 1) {
                        layer.alert(res.error);
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

            // 工具栏事件
            table.on('toolbar(test)', function(obj){
                var id = obj.config.id;
                var checkStatus = table.checkStatus(id);
                var othis = lay(this);
                switch(obj.event){
                    case 'getCheckData':
                        var data = checkStatus.data;
                        layer.alert(layui.util.escape(JSON.stringify(data)));
                        break;
                    case 'getData':
                        var getData = table.getData(id);
                        console.log(getData);
                        layer.alert(layui.util.escape(JSON.stringify(getData)));
                        break;
                };
            });
            // 表头自定义元素工具事件 --- 2.8.8+
            table.on('colTool(test)', function(obj){
                var event = obj.event;
                console.log(obj);
                if(event === 'email-tips'){
                    layer.alert(layui.util.escape(JSON.stringify(obj.col)), {
                        title: '当前列属性配置项'
                    });
                }
            });
        });
    </script>
</section>
