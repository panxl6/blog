## PHP数据导出优化案例

**主要依赖**

本文以php7.1、thinkphp5.1、swoole4.1为基础

```bash
# 1. 安装thinkphp 5.1
composer create-project topthink/think=5.1 tp5
# 2. 安装Excel读写类库
composer require phpoffice/phpspreadsheet
# 3. 安装Swoole扩展
sudo pecl install swoole
```



**数据表设计**

```sql
CREATE DATABASE demo DEFAULT CHARSET utf8mb4;
USE demo;

DROP TABLE IF EXISTS t_user;
CREATE TABLE `t_user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `password` varchar(128) NOT NULL DEFAULT '',
  `headurl` varchar(1024) NOT NULL DEFAULT '',
  `nickname` varchar(32) NOT NULL DEFAULT '',
  `truename` varchar(32) NOT NULL DEFAULT '',
  `province` varchar(32) NOT NULL DEFAULT '',
  `city` varchar(32) NOT NULL DEFAULT '',
  `address` varchar(32) NOT NULL DEFAULT '',
  `nation` varchar(32) NOT NULL DEFAULT '',
  `age` int(11) NOT NULL DEFAULT '0',
  `desc` varchar(128) NOT NULL DEFAULT '',
  `reg_src` int(11) NOT NULL DEFAULT '0',
  `reg_at` int(11) NOT NULL DEFAULT '0',
  `sex` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0：男性，1女性',
  `birthday` char(10) NOT NULL DEFAULT '',
  `last_login_ip` varchar(16) NOT NULL DEFAULT '',
  `last_login_time` int(11) NOT NULL DEFAULT '0',
  `email` varchar(34) NOT NULL DEFAULT '',
  `mobile` char(11) NOT NULL DEFAULT '',
  `level` int(11) NOT NULL DEFAULT '0' COMMENT '0青铜会员，1白银会员，2黄金会员，3铂金会员',
  `id_card` varchar(64) NOT NULL DEFAULT '',
  `bank_card` varchar(64) NOT NULL DEFAULT '',
  `create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```



**生成模拟数据**(200000条)

checkout代码：`git checkout mock-data`

`php think test`

#### Hello World

`php -S 0.0.0.0:8000 public/index.php`

访问http://localhost:8000/能看到输出，说明部署好了。端口可以修改。localhost也可以改成自己的IP或者域名。

### 版本1

checkout代码：`git checkout exportV1`

访问http://localhost:8000/index.php/Index/exportUserV1，即可导出文件。你可以修改导出条数，直至接口崩溃。此时，你将得到版本1的导出上限。

### 版本2

### 版本3

运行swoole服务器

`php think server`