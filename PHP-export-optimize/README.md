## PHP数据导出优化案例

**安装步骤**

本文以php7.1、thinkphp5.1、swoole4.1为基础

```bash
# 1. 安装thinkphp 5.1
composer create-project topthink/think=5.1.x-dev tp5
# 2. 安装Excel读写类库
composer require phpoffice/phpspreadsheet
# 3. 安装Swoole工具包
composer require topthink/think-swoole
```

