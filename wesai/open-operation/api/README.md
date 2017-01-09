基于CI框架的前端实现

代码部署结构说明：
* WORKSPACE             是工作目录

* WORKSPACE/            放置各项目代码
* WORKSPACE/logs/       放置各项目产生的日志
* WORKSPACE/config/     放置各项目配置信息

项目代码结构说明：
* /application/config   复制config.dist即可。这个文件夹不要提交（.gitignore加过滤）
* /application/config/local.php 框架配置信息都集中到这里修改

