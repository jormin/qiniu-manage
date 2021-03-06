<?php
use yii\helpers\Url;
use common\models\AuthAccount;
?>
<div class="layui-fluid">
    <div class="layui-card">

        <form class="layui-form layui-card-header layuiadmin-card-header-auto">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <input type="hidden" name="marker" id="marker">
                    <div class="layui-input-inline">
                        <select name="accountID" lay-filter="accountID">
                            <option value="">请选择七牛授权账号</option>
                            <?php foreach (AuthAccount::options() as $accountID => $alias): ?>
                                <option value="<?= $accountID ?>"><?= $alias ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-list" lay-filter="form-filter" layadmin-event="form_search" type="button">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                    <button class="layui-btn layuiadmin-btn-tags" type="button" layadmin-event="create" data-url="<?= Url::to(['bucket/create']) ?>" data-title="新建七牛空间">添加</button>
                </div>
            </div>
        </form>
        <div class="layui-card-body">
            <table class="layui-table" lay-data="{url:'<?= Url::current()?>', page:true, limit:10, id:'dataTable'}" lay-filter="dataTable">
                <thead>
                <tr>
                    <th lay-data="{field:'id', width:80}">ID</th>
                    <th lay-data="{field:'bucket', width:120}">空间名称</th>
                    <th lay-data="{field:'accountID', width:120}">七牛授权账号</th>
                    <th lay-data="{field:'domains', width:700}">空间绑定域名</th>
                    <th lay-data="{field:'defaultDomain', width:260}">默认域名</th>
                    <th lay-data="{field:'createTime'}">创建时间</th>
                    <th lay-data="{toolbar:'#tableBar', width:260}">操作</th>
                </tr>
                </thead>
            </table>
            <script type="text/html" id="tableBar">
                <a class="cmd-btn" lay-event='view' data-url="<?= Url::to(['bucket/view']) ?>" data-title="查看七牛空间" >[查看]</a>
                <a class="cmd-btn" lay-event='update' data-url="<?= Url::to(['bucket/update']) ?>" data-title="编辑七牛空间" >[编辑]</a>
                <a class="cmd-btn btn-danger" lay-event='delete' data-url="<?= Url::to(['bucket/delete']) ?>" data-title="删除七牛空间" data-confirm="<span style='color:red;'>危险!!!删除操作会删除七牛上对应的空间!!!</span><br>确定删除吗?">[删除]</a>
                <a class="cmd-btn" lay-event='delete' data-url="<?= Url::to(['bucket/sync-domains']) ?>" data-confirm="确定同步域名吗?">[同步域名]</a>
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