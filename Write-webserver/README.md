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
### 替换html所在路径(用你的实际路径)
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