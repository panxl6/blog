# PHP从入门到放弃

    PHP用了三年，做个总结。

- 起源
- 从`hello world`说起
- PHP的语言缺陷
- PHP在开发流程上带来的改变
- 性能问题
- 底层实现

## 起源
TODO: PHP的演变，历史。
来自C语言，最后想走向Java。


## 从`hello world`说起

```php
<?php

echo "hello world";
```

```php
<html>
<?php echo "hello world"; ?>
</html>
```

PHP的`hello world`可以有两种。一种是cli模式下的脚本语言，另一种是cgi模式下的模板语言。
PHP的`hello world`里面特地添加了`<?php`标记来区分，或者说兼容这两种场景。
在Java EE里面，模板语言跟java是另一回事。两者的文件后缀就不一样。一个是`.java`，了一个是`.jsp`。
这样一来，java独立发展，jsp只是java的一个应用。
而PHP，却把模板语言的功能融合到了语言本身。对于开发小型web应用来说，这种开箱即用的特性就代表了效率，尤其是在JavaScript到处是坑的年代。
然而，如今的JavaScript得到大幅度的增强，并且前后端分离开始流行了。PHP作为可以进行服务端渲染的模板语言，就没有了比较优势。毕竟，JavaScript可以运行在任意的浏览器，并且是前端开发的标准语言。前端的归前端，后端的归后端。

我们再来看一下最经典的C语言`hello world`。
```c
#include <stdio.h>
int main()
{
    printf("hello, world");
    return 0;
}
```



## PHP的语言缺陷

### `for/foreach`循环中的变量作用域问题
### 变量覆盖问题
### 动态类型一时爽
### “码分复用”，PHP对空状态的宽容带来的问题
### 引用，以及其他残留的C语言特性
### 内部库函数的命名方式和风格
### 异常处理的不足
### 难以调试，错误信息对开发者不友好

## PHP在开发流程上带来的改变
敏捷还是混乱？


## 底层实现
### 为什么PHP7要比PHP5的性能好？
### 万能的数组：成也萧何，败也萧何
### PHP扩展开发，真香？还是鸡肋？