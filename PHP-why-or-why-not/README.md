# PHP是世界上最好的语言吗？

PHP，曾是2004年的[编程语言流行度排行榜](https://www.tiobe.com/tiobe-index/)的winner，而如今（2019年）将要跌出top 10，它都面临着什么样的问题？从2012年起，iPhone引领的移动互联网兴起之后，后端开发新秀有：Golang、NodeJS、rust、kotlin等。而老牌的java，也随着spring boot，spring cloud一类的优秀框架的生态繁荣，也进入了新的增长期。PHP似乎不再是世界上最好的语言了，我们一起来反思一下。

- 历史
- 从`hello world`说起
- PHP的语言缺陷
- PHP在开发流程上带来的改变
- 底层实现

## 历史

PHP，最早的叫法是*Personal Home Page Tools*。它的定位是个人主页开发工具，跟java这样的企业级开发语言相比，在起初的定位上就有着很大的差别。发展到现在，它已经改名叫*Hypertext Preprocessor*。尽管它吸收了C、Java、Perl等语言的一些语法特性，并且在web应用的快速、敏捷开发中有着成功的范例(LNMP)，但是它如今的比较优势已经没有那么明显了。

---------------------

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

在Java EE里面，模板语言跟java是另一回事。两者的文件后缀就不一样。一个是`.java`，另一个是`.jsp`。这样一来，java独立发展，jsp只是java的一个应用。

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

不仅仅是C语言，基本上编译型语言都是从main函数开始的。单一入口的规定，在语言层面就建议或者说约束你，后面的模块、函数、类等要素要以树状结构进行组织。比如，java强制代码以类的形式组织，python强制缩进一致，Golang强制代码风格的统一。而PHP，作为一门脚本语言。所有的调用都可以成为入口。一个文件就是一个应用。如果你不按照最佳实践进行代码组织，最后的PHP代码一般都会是混乱的。



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

### 动态类型一时爽
PHP是动态类型的语言，对于变量的类型并没有约束。任何时间，任何变量可以是任意类型。

其实，C语言也可以理解为“动态类型”的语言。由于C语言中，(void*)指针可以指向任意类型的变量，然后就看你怎么去解析指针指向的变量了。

比如：

```c
pthread_create(&thread_id, NULL, request_handler, (void *)client_sock);

void *request_handler(void *p)
{
    int client_sock = (int)p;

    response(&client_sock, request(&client_sock));
}
```

#### 一个变量做多件事情
如果一个变量
```php
$a = 'userName'; // 用户输入

$b = $a; //变量$a的使命已经完成了

$a = 3; // 又一次用$a变量这个容器去承载另一件事的数据
```

当你重构这段代码的时候，不同类型的值对应则不同的业务逻辑。那么你就需要增加更多的变量来填补以前的逻辑缺陷。这样一来代码膨胀了，膨胀到一个变量做一件事时应有的代码量。这样会有一个问题，你原先满大街用的变量要换个名称了，你要改很多逻辑，替换很多变量。


### “码分复用”带来的问题
PHP对空状态的宽容，以及动态类型带来的灵活性，打破了一次只做一件事的约束。

#### 概念升维
在C语言家族中，错误码是很常见的一个概念。
在java中，遇到问题就直接抛出一个异常。就只有一个概念。

在C语言编程中，对于简单的问题，返回`true`代表执行成功，返回`false`表示执行失败。但是，当我们需要更细的粒度是，`true`和`false`就不够用了，毕竟一位二进制只能表示两种可能。那么我们就扩充一下，返回一个十进制的`errno`变量吧。这样一来就发生了概念的维度上升。

原来的`true`或者`false`仅仅表示系统错误(概念上对应于Java中的Error异常，不可预测的错误)，比如：mysql读取失败、redis读取失败、网络异常；errCode表示业务错误(对应于Java中的Exception，可以预测的错误)，比如：参数非法，输入不符合限制。


```php
// 版本1
// 此时，$ret变量既表示返回结果的内容，又表示执行状况
function getUserInfo()
{
	$ret = $redis->hget('hashName', $uid);
	// 系统错误
	if (!$ret)
		return false;

	// 业务逻辑错误
	if (empty($ret['uid']))
		return false;
		
	return true;
}

// 版本2
class Test
{
	protected $errCode = 0;
	function getUserInfo(&$data)
	{
		$ret = $redis->hget('hashName', $uid);

		// 系统错误(redis读取异常)
		if ($ret === false) {
			return false;
		}
		
		// 业务逻辑错误(缺少必要字段)
		if (empty($ret['uid'])) {
			$this->errCode = 10001;
			return true;
		}
		
		$data = $ret;
		return true;
	}
}
```

PHP的很多类库函数就继承了C语言的这一类思想。

比如，字符串查找函数`strpos($src, $neddle)`。如果查找失败返回`false`，查找成功返回起始下标。这一点可以借鉴Go语言，返回两个参数。

#### 状态码歧义

```php
function demo()
{
	$userInput = $_GET['user_input'];
	
	if (empty($userInput)) {
		echo "用户投了否定票";
		return false;
	}

	echo "用户投了支持票";
	
	return true;
}
```

在应用中，我们一般使用枚举值来表示业务的状态。

从上面的例子来看，会产生歧义。我们以0或者说空，作为否定票的标记，以非空的值作为支持票的定义。但是，如果前端把参数传丢了，我们就默认为用户投了否定票。

而实际上是前端有bug。


```php
function demo()
{
	$userInput = $_GET['user_input'];
	
	if ($userInput == 1) {
		echo "用户投了否定票";
		return false;
	}

	if ($userInput == 2) {
		echo "用户投了支持票";
		return true;
	}
	
	echo "未定义的参数";	
	
	return false;
}
```

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
PHP中，即使是同一个领域的库函数，也有着五花八门的名称组织方式。下面以时间处理系列函数为例。

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

----------------------------------------------------------------------------------------------------------

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

另一方面，PHP报错的时候，只是给出了哪一行有问题，但并没有给出一个调用栈的信息。也就是说你得自己看代码，然后一路脑补。如果调用层次比较深，文件数量比较多，那就很头疼了。

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

---------------------

## PHP在开发流程上带来的改变
在PHP中，有着一定量跟HTTP有关的全局变量和函数。它们成为PHP的语言要素。

可以这么说，Java是面向对象的，而PHP是面向请求的。

### 开发流程
用PHP开发web应用时，你只需要改一下代码，然后上传代码到服务器，刷新浏览器，你就可以看到你的代码执行效果了。

但是Java EE一类的开发方式，要求你编译代码-->构建jar包-->部署jar包-->重启web容器。java的编译是比较耗时的操作。如果中途出现任何的错误（比如语法错误、端口被占用等），你又得重新再来一次。

即便是同为动态语言的Python，开发web应用时，你也依然避免不了重启服务器的动作。

你会发现，你很多时间都花在了写代码本身以外的事情。


### 请求处理方式
php-fpm为每个请求分配一个进程。这样一来，就起到了沙箱隔离的作用。

每个请求的全局变量，都是从初始状态开始的，一致的。每个请求的生命周期互不影响，无状态的。

即使一个请求出现了故障（比如执行了语法错误的代码等，比如你在某个进程中调用`die()`或者`exit()`），并不会影响其他请求。但是像php swoole一类的处理方式--每个请求复用一个进程或线程，就有可能互相影响。

这种方式的一个弊端就是带来了一些性能问题。每个请求之间无法复用一些资源，比如文件句柄、配置文件等。每个请求都必须重新初始化。


---------------------

## 底层实现
### 为什么PHP7要比PHP5的性能好？
- [ ] 对比jvm虚拟机

### 万能的数组：成也萧何，败也萧何

```java
Map<Integer, Integer> map = new HashMap<Integer, Integer>();
for (int k = 0; k < 100000; k++) {
	map.put(k, k);
}

for (String key : map.keySet()) {
	String value = map.get(key);
	// do something
}
```
上面的代码，第一步将10万条数据放到HashMap中；第二步，取出来用。这里有两个问题。

1. resize问题。HashMap创建时，没有根据已知数据量进行初始化，导致后续的put操作需要执行多次的哈希表扩容操作。频繁的执行哈希表扩容操作，对CPU和垃圾收集都不友好。
2. 在循环取元素时，没用使用EntrySet，而是用了keySet。导致map.get()操作中出现重复计算hash的操作。

在`java`集合中，当HashMap的链表长度超过8时，会自动转为红黑树。数据量扩张导致的哈希表问题就没那么明显了。

有了上面的应用背景，我们来对比一下PHP中的数组。

虽然PHP也有SPL集合框架，但是应用最广泛的还是PHP的数组。PHP数组是一个有序字典。底层实现仅仅是一个哈希表。

为了实现有序遍历，PHP的哈希表维护了一个全局的双向链表。同时，为了区分PHP数组的key是数字还是字符串，PHP底层的哈希表维护了两个哈希值--增加了两次哈希值计算。


### PHP扩展开发，真香？还是鸡肋？
PHP语言的函数和功能都是较为粗粒度的。如果你需要改造PHP，以实现差异化的功能，有两种方式。

一种是[定制PHP的语法](http://www.laruence.com/2010/07/16/1648.html)，比如向PHP中添加新的关键字。另一种是[开发PHP扩展](http://www.phpinternalsbook.com/)，提升性能或者将C语言的能力透传给PHP，比如swoole、yaf框架等。

PHP的维护者，更像是一群个体户。而Java的官方组织，更像是一家大企业。两者的差别导致了很多的问题。比如，前面提到的PHP库函数命名混乱，还有PHP发版时间不确定，而且更新升级较为缓慢等问题。

增强PHP，或者改造PHP是一件做出来容易，做好很难的事情。你用C语言写了PHP扩展，后续的维护呢？比如swoole，swoole扩展要开发，php使用者也要更新自己的知识。这不见得省事。

我们换一个思路，如果我们用Go语言来开发PHP扩展（比如swoole），那么扩展开发的效率要比用C语言时高一些。但是增强后的php的业务开发效率，跟Go语言开发业务的效率差别就不太大了。那么我们得到结论是，直接使用Go语言来开发业务，而不是PHP。



### 参考文献
1. [taking php serious](https://slack.engineering/taking-php-seriously-cf7a60065329)
2. [php.net](https://php.net)
3. [php sadness](http://phpsadness.com/)
4. [《大型网站性能优化实战》](https://book.douban.com/subject/30437260/)

