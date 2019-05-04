# 简单的静态文件web服务器

## 1. Hello world
### 编译运行
`g++ main.c -o main && main`

### 访问url
```bash
curl -I 127.0.0.1:8080

HTTP/1.0 200 OK
Server: A Simple Web Server
Content-Type: text/html
```

## 2. 根据url动态查找文件
### 替换html所在路径(用你的实际路径,下面不再重复说明)
`#define HTML_DIR "/home/panxl/CLionProjects/multiprocess/html/"`

### 编译运行
`g++ main.c -o main && main`

### 访问url
```bash
curl  127.0.0.1:8080/index.html
<!DOCTYPE html>
<html>
<head>
    <title>Welcome to home page!</title>
</head>
<body>
<h1>Welcome to home page</h1>

</body>
</html>
```

## 3. 并发模型(ppc,一个连接一个进程)

### 编译运行
`g++ main.c -o main && main`

### 压测工具访问url
```bash
ab -k -c 350 -n 9000 http://127.0.0.1:8080/index.html
This is ApacheBench, Version 2.3 <$Revision: 1706008 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient)
Completed 900 requests
Completed 1800 requests
Completed 2700 requests
Completed 3600 requests
Completed 4500 requests
Completed 5400 requests
Completed 6300 requests
Completed 7200 requests
Completed 8100 requests
Completed 9000 requests
Finished 9000 requests


Server Software:        
Server Hostname:        127.0.0.1
Server Port:            8080

Document Path:          /index.html
Document Length:        133 bytes

Concurrency Level:      350
Time taken for tests:   54.536 seconds
Complete requests:      9000
Failed requests:        0
Keep-Alive requests:    0
Total transferred:      1890000 bytes
HTML transferred:       1197000 bytes
Requests per second:    165.03 [#/sec] (mean)
Time per request:       2120.827 [ms] (mean)
Time per request:       6.060 [ms] (mean, across all concurrent requests)
Transfer rate:          33.84 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0   33 180.5      0    1036
Processing:     0  227 2654.9      5   53504
Waiting:        0  227 2654.9      5   53504
Total:          0  260 2741.8      5   54533

Percentage of the requests served within a certain time (ms)
  50%      5
  66%      9
  75%     12
  80%     14
  90%     23
  95%     35
  98%   1268
  99%   4327
 100%  54533 (longest request)


```


## 4. 并发模型(tpc,一个连接一个线程)

### 编译运行
`g++ main.c -o main -lpthread -fpermissive`

### 压测工具访问url
```bash
ab -k -c 350 -n 9000 http://127.0.0.1:8080/index.html
This is ApacheBench, Version 2.3 <$Revision: 1706008 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient)
Completed 900 requests
Completed 1800 requests
Completed 2700 requests
Completed 3600 requests
Completed 4500 requests
Completed 5400 requests
Completed 6300 requests
Completed 7200 requests
Completed 8100 requests
Completed 9000 requests
Finished 9000 requests


Server Software:        
Server Hostname:        127.0.0.1
Server Port:            8080

Document Path:          /index.html
Document Length:        133 bytes

Concurrency Level:      350
Time taken for tests:   6.752 seconds
Complete requests:      9000
Failed requests:        1
   (Connect: 0, Receive: 0, Length: 1, Exceptions: 0)
Keep-Alive requests:    0
Total transferred:      1889886 bytes
HTML transferred:       1196898 bytes
Requests per second:    1332.91 [#/sec] (mean)
Time per request:       262.583 [ms] (mean)
Time per request:       0.750 [ms] (mean, across all concurrent requests)
Transfer rate:          273.33 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0   22 145.7      0    1036
Processing:     0  238 229.1    192    2006
Waiting:        0  237 229.0    191    2006
Total:          0  259 295.3    193    2861

Percentage of the requests served within a certain time (ms)
  50%    193
  66%    248
  75%    325
  80%    383
  90%    542
  95%    795
  98%   1240
  99%   1626
 100%   2861 (longest request)
```

当并发连接数越来越多时，线程模型耗费的资源要比多进程模型小很多。


## 验证一些已有的并发模型
- [ ] PHP-fpm进程模型
- [ ] Nginx进程（并发模型）
- [ ] 线程池/进程池模型

## 参考文献
1. [《Unix环境高级编程》](https://book.douban.com/subject/25900403/)
2. [《Concurrent Programming for Scalable Web Architectures》](https://berb.github.io/diploma-thesis/original/)
3. [《极客时间.从0开始学架构》](https://time.geekbang.org/column/article/8697)
