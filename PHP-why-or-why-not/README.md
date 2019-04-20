# PHP从入门到放弃

    PHP用了三年，做个总结。

- 起源
- 从`hello world`说起
- PHP的语言缺陷
- PHP在开发流程上带来的改变
- 性能问题
- 底层实现

## 起源

PHP，从最初的*Personal Home Page*到现在的*Hypertext Preprocessor*。
它来自C语言，最后向Java一类的面向对象语言靠拢。


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

PHP的`hello world`可以有两种。一种是cli模式下的脚本语言，另一种是cgi模式下的模板语言。PHP的`hello world`里面特地添加了`<?php`标记来区分，或者说兼容这两种场景。

在Java EE里面，模板语言跟java是另一回事。两者的文件后缀就不一样。一个是`.java`，了一个是`.jsp`。这样一来，java独立发展，jsp只是java的一个应用。

而PHP，却把模板语言的功能融合到了语言本身。对于开发小型web应用来说，这种开箱即用的特性就代表了效率，尤其是在JavaScript到处是坑的年代。

然而，如今的JavaScript得到大幅度的增强，并且前后端分离开始流行了。PHP作为可以进行服务端渲染的模板语言，就没有了比较优势。毕竟，JavaScript可以运行在任意的浏览器，并且是前端开发的标准语言。最终，前端的归前端，后端的归后端。

跟同一时代的胶水语言Python语言相比，你很少会看到PHP会在web以外的领域有其他的成功应用。而Python则遍地开花。

我们再来看一下最经典的C语言`hello world`。
```c
#include <stdio.h>
int main()
{
    printf("hello, world");
    return 0;
}
```

不仅仅是C语言，基本上编译型语言都是从main函数开始的。单一入口的规定，在语言层面就建议或者说约束你，后面的模块、函数、类等要素要以树状结构进行组织。而PHP，作为一门脚本语言。所有的调用都可以成为入口。一个文件就是一个应用。如果你不按照最佳实践进行代码组织，最后的PHP代码一般都会是混乱的。



## PHP的语言缺陷

### `for/foreach`循环中的变量作用域问题
```php
<?php

for ($i=0; $i<3; $i++) {
    echo $i . "\n";
}

echo $i;

/* 最终的输出结果：
0
1
2
3
*/
```

你会发现，变量`$i`在for循环以外的地方居然也被打印了。在C语言里面，这肯定是语法错误了。变量`$i`被使用的时候，就被声明了，但是PHP并没有把它的作用范围限制在for循环以内。python也有这个问题。

如果你不留意这个现象（作用域被污染），那么就会出现计数器的初始值错误，或者变量值被覆盖等问题。

### 变量覆盖问题

### “码分复用”，PHP对空状态的宽容带来的问题

### 引用，以及其他残留的C语言特性
在Java、Python等一类较为现代的编程语言，都在极力的隐藏指针这个概念。但是PHP中，你还可以看到指针的影子。PHP里面可以使用引用。这样你就可以获取变量的地址，并传递变量的地址。跟Go语言中的指针一样，不能做变量的位移计算，是一个阉割版的指针。

下面的例子中，使用引用的方式进行传参，意图打印$data中的所有值。最终输出的3个1，和1个2。由于第一次调用时变量$b拥有了值1，所以接下来的两次调用$b就不为空，也就不会赋予默认值了。

指针是有状态的，全局的。它破坏了作用域的限制，并且还会影响垃圾回收的执行。不过，只读的指针可以减少内存的拷贝。

```php
class Demo
{
    private $data = array(1, '', '', 2, 'string');

    private function defaultParmaWithReference($index, &$b=array())
    {
        if ($index === null) {
            return;
        }

        $b = $this->data[$index];
    }

    public function testReference()
    {
        $this->defaultParmaWithReference(0, $b);
        var_dump('第一次调用：', $b);

        $this->defaultParmaWithReference($a, $b);
        var_dump('第二次调用：', $b);

        $this->defaultParmaWithReference($a, $b);
        var_dump('第三次调用：', $b);

        $this->defaultParmaWithReference(3, $b);
        var_dump('第四次调用：', $b);
    }    
}


$demo = new Demo();
$demo->testReference();
```

输出结果:

```
string(18) "第一次调用："
int(1)
PHP Notice:  Undefined variable: a in /home/ubuntu/Projects/php/php_flaw/DefaultParam.php on line 22
string(18) "第二次调用："
int(1)
PHP Notice:  Undefined variable: a in /home/ubuntu/Projects/php/php_flaw/DefaultParam.php on line 25
string(18) "第三次调用："
int(1)
string(18) "第四次调用："
int(2)
```

### 内部库函数的命名方式和风格
PHP上手快的一个原因，是它里面的各种高度封装的函数。但是，PHP里面的函数、类库的组织方式相比其他工业级标准语言要混乱得多。给你讲个笑话，某个PHP程序猿拍了一下脑袋苦思冥想。你猜他在干嘛？喂，那个很牛逼的函数，叫什么名字来着？

#### 命名风格混乱
有些是按C语言的单词下划线的方式命名的，有些是按面向对象大小写的方式命名的。剩下的就是没有风格的。

```
1. gettype() vs get_class()
2. str_pos() str_replace() str_split() vs strcmp() strchr() strcasecmp()
3. base64_encode() vs urlencode()
4. htmlentities() vs html_entity_decode()
5. swoole_server() vs Swoole\Server()
```

#### 函数的命名方式(前缀、缩写、类)
PHP某个领域的库函数，有着五花八门的名称组织方式。下面以时间处理系列函数为例。


```php
1. void usleep  () vs mixed microtime  () // 单位缩写 vs 单词缩写
2. strtolower() strtotime() strtoupper() vs ip2long () bin2hex() // 数字缩写 vs 单词
3. date('Y-md') vs DateTime::createFromFormat('j-M-Y', '15-Feb-2009') // 全局函数 vs 静态类方法
4. swoole_server() vs Swoole\Server() // 全局函数 vs 类
```

#### 函数的参数签名混乱
这里的callback或者needle只是个例子，用于指示在一系列功能相关的接口中，具有相同含义的入参或者出参。

PHP类库的函数，一会儿把关注点放在前面，一会儿放在后面。使用者很难形成稳固的印象。你必须看文档，否则很容易出错。

相比之下，C++的STL、Java的Collection类库，函数的入参出参是高度的一致。

``` php
Callback last:
1. array array_filter  ( array $input  [, callback $callback  ] )
2. array array_uintersect  ( array $array1  , array $array2  [, array $ ...  ], callback $data_compare_func  )
3. bool usort  ( array &$array  , callback $cmp_function  )

Callback first:
1. array array_map  ( callback $callback  , array $arr1  [, array $...  ] )
2. mixed call_user_func ( callback $function [, mixed $parameter [, mixed $... ]] )

Needle last:
1. int strpos ( string $haystack  , mixed $needle  [, int $offset= 0  ] )
2. string stristr ( string $haystack , mixed $needle [, bool $before_needle = false ] )

Needle first:
1. bool in_array  ( mixed $needle  , array $haystack  [, bool $strict  ] )
2. mixed array_search  ( mixed $needle  , array $haystack  [, bool $strict  ] )
3. str_replace ( mixed $needle , mixed $replace , mixed $subject [, int &$count ] )
```

### 难以调试，错误信息对开发者不友好
如果你合并代码，出现括号、双引号缺少了一个的情况，那么就会造成语法错误。本来语法错误，是最容易处理的。但是PHP的语法提示，却很容易误导你。

比如，下面的错误其实是在第3行，但是语法错误却提示你在20行。

```PHP Parse error:  syntax error, unexpected 'thank' (T_STRING) in /home/ubuntu/Projects/php/php_flaw/CompileDetail.php on line 20```

```php
<?php

$string = "welcome to my personal home page;

# a fake example; just imagine some lengthy code here
if ($page_id == 0) {
  render_home_page();
} elseif ($page_id == 1) {
  render_contacts_page();
} elseif ($page_id == 2) {
  render_about_page();
} elseif ($page_id == 3) {
  render_services_page();
} elseif ($page_id == 4) {
  render_weather_page();
} elseif ($page_id == 5) {
  render_news_page();
}

print "thank you for visiting!";
```

另一方面，PHP报错的时候，只是给出了哪一行有问题，但并没有给出一个调用栈的信息。也就是说你的自己看代码，然后一路脑补。如果调用层次比较深，文件数量比较多，那就很头疼了。

```
PHP Notice:  Undefined variable: 0 in /home/ubuntu/Web/test/ErrorStack.php on line 5
PHP Notice:  Undefined variable:  in /home/ubuntu/Web/test/ErrorStack.php on line 5
int(3)
PHP Parse error:  syntax error, unexpected '$a' (T_VARIABLE) in /home/ubuntu/Web/test/classA.php on line 7
```

```php
<?php

function a($a)
{
    return $$$a + 1;
}

function b($a)
{
    return a($a) + 1;
}

function c($a)
{
    return b($a) + 1;
}

var_dump(c(0));
```

## PHP在开发流程上带来的改变
敏捷还是混乱？


## 底层实现
### 为什么PHP7要比PHP5的性能好？
### 万能的数组：成也萧何，败也萧何
### PHP扩展开发，真香？还是鸡肋？
