#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <string.h>

void out(void)
{
    puts("atexit() succeeded");
}

int main()
{
    int serv_sock = socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);

    struct sockaddr_in serv_addr;
    memset(&serv_addr, 0, sizeof(serv_addr));

    serv_addr.sin_family = AF_INET;
    serv_addr.sin_addr.s_addr = inet_addr("127.0.0.1");
    serv_addr.sin_port = htons(8080);

    int ret = 0;

    ret = bind(serv_sock, (struct sockaddr*)&serv_addr, sizeof(serv_addr));
    if (ret == -1) {
        puts("端口绑定失败");
    }

    ret = listen(serv_sock, 20);
    if (ret == -1) {
        puts("端口监听失败");
    }

    struct sockaddr_in clnt_addr;
    socklen_t clnt_addr_size = sizeof(clnt_addr);
    int clnt_sock = accept(serv_sock, (struct sockaddr*)&clnt_addr, &clnt_addr_size);
    if (clnt_sock == -1) {
        puts("响应失败");
    }

    char status[] = "HTTP/1.0 200 OK\r\n";
    char header[] = "Server: A Simple Web Server\r\nContent-Type: text/html\r\n\r\n";
    char content[] = "hello world";

    write(clnt_sock, status, strlen(status));
    write(clnt_sock, header, strlen(header));
    write(clnt_sock, content, strlen(content));

    close(clnt_sock);
    close(serv_sock);

    return 0;
}