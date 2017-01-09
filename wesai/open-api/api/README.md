基于CI框架的前端实现

代码部署结构说明：
* WORKSPACE             是工作目录

* WORKSPACE/            放置各项目代码
* WORKSPACE/logs/       放置各项目产生的日志
* WORKSPACE/config/     放置各项目配置信息

项目代码结构说明：
* /application/config   为软连接连接到WORKSPACE/config/下的对应配置
* /application/config/local.php 框架基础配置信息
* /application/config/config.php 自定义配置信息

