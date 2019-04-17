# 简单的静态文件web服务器

### 编译运行
`g++ main.c -o main && main`

### 访问url
```bash
curl -I 127.0.0.1:8080

HTTP/1.0 200 OK
Server: A Simple Web Server
Content-Type: text/html
```