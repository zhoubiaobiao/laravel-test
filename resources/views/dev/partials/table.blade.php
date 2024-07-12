<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Sql Excute result') }}
        </h2>
    </header>

    <div style="padding: 16px;">
        <table class="layui-hide" id="test" lay-filter="test"></table>
    </div>

    <script src="//cdn.staticfile.net/layui/2.9.14/layui.js"></script>
    <script>
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
        });
    </script>
</section>
