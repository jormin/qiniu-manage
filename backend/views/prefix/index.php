<?php
use yii\helpers\Url;
?>
<div class="layui-fluid">
    <div class="layui-card">
        <div class="layui-card-header layuiadmin-card-header-auto">
            <button class="layui-btn layuiadmin-btn-tags" layadmin-event="create" data-url="<?= Url::to(['prefix/create']) ?>" data-title="新建前缀">添加</button>
        </div>
        <div class="layui-card-body">
            <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                <tr>
                    <th lay-data="{field:'id', width:80}">ID</th>
                    <th lay-data="{field:'prefix'}">前缀</th>
                    <th lay-data="{field:'accountID'}">授权账号</th>
                    <th lay-data="{field:'bucketID'}">七牛空间</th>
                    <th lay-data="{field:'createTime'}">创建时间</th>
                    <th lay-data="{field:'updateTime'}">更新时间</th>
                    <th lay-data="{toolbar:'#tableBar', width:180}">操作</th>
                </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='view' data-url="<?= Url::to(['prefix/view']) ?>" data-title="查看前缀" >[查看]</a>
                <a class="cmd-btn" lay-event='update' data-url="<?= Url::to(['prefix/update']) ?>" data-title="编辑前缀" >[编辑]</a>
                <a class="cmd-btn" lay-event='delete' data-url="<?= Url::to(['prefix/delete']) ?>" data-confirm="确定删除这个前缀吗?">[删除]</a>
            </script>
        </div>
    </div>
</div>

<?php $this->beginBlock('js_footer') ?><script>
    layui.config({
        base: '/layuiadmin/' //静态资源所在路径
    }).extend({
        index: 'lib/index' //主入口模块
    }).use(['index', 'table']);
</script>
<?php $this->endBlock(); ?>