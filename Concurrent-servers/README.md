# 并发服务器：第一部分 - 简介

这是网络并发服务器系列专题中的第一篇。我的计划是验证处理多客户端连接的常见网络并发模型，并确认它们在扩展性和实现的简易程度。所有的服务器都会监听套接字连接，然后实现一个简单的协议与客户端交互。

系列专题的全部文章：
- 第一部分：[简介]()
- 第二部分：[线程](https://github.com/panxl6/blog/blob/master/Concurrent-servers/Part2%20-%20Threads.md)
- 第三部分：[事件驱动](https://github.com/panxl6/blog/blob/master/Concurrent-servers/Part3%20-%20Event-driven.md)
- 第四部分：[libuv](https://github.com/panxl6/blog/blob/master/Concurrent-servers/Part4%20-%20libuv.md)
- 第五部分：[Redis案例研究](https://github.com/panxl6/blog/blob/master/Concurrent-servers/Part5%20-%20Redis%20case%20study.md)
- 第六部分：[`Callbacks,Promises,async/await`](https://github.com/panxl6/blog/blob/master/Concurrent-servers/Part6%20-%20Callbacks%2CPromises%20and%20async%20await.md)

## 协议
本专题中通篇使用的协议是很简单的。但是足以阐释清楚并发服务器设计的方方面面。注意了，此协议是有状态的--服务器内部的状态会跟着客户端发送的数据而改变。事实上，并非所有的协议都是有状态的，许多基于HTTP的协议是无状态的。但是有状态的协议通常有利于专题讨论。

从服务端的角度来看，协议长这样：

![https://eli.thegreenplace.net/images/2017/concurrent-server-protocol.svg](https://eli.thegreenplace.net/images/2017/concurrent-server-protocol.svg)


## 串行服务器

换句话说：
1. 服务器一直在等待新的客户端连接；
2. 当客户端跟服务器建立连接时，服务器发送`*`字符给客户端，并进入等待接受消息的状态；
3. 在这个状态（状态2）下，服务器会丢弃客户端发送的所有信息，直到它收到`^`字符。这代表着新的消息开始了。
4. 此时服务端进入“接收消息”的状态。在此状态下，服务端将客户端发送过来的消息原封不动的输出给客户端。

在每个状态中，都隐藏了一个箭头，指向“等待客户端”的状态。例如，客户端断开连接了。由此推出，客户端表达传输完成的唯一方式是，主动断开连接。


## 多个并发连接

## 小结与下文的介绍


[原文地址](https://eli.thegreenplace.net/2017/concurrent-servers-part-1-introduction/)