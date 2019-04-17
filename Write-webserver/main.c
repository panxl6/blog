#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <string.h>

int main()
{
    int server_sock = socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);

    struct sockaddr_in server_addr;
    memset(&server_addr, 0, sizeof(server_addr));

    server_addr.sin_family = AF_INET;
    server_addr.sin_addr.s_addr = inet_addr("127.0.0.1");
    server_addr.sin_port = htons(8080);

    int ret = 0;

    ret = bind(server_sock, (struct sockaddr*)&server_addr, sizeof(server_addr));
    if (ret == -1) {
        puts("端口绑定失败");
    }

    ret = listen(server_sock, 20);
    if (ret == -1) {
        puts("端口监听失败");
    }

    struct sockaddr_in client_addr;
    socklen_t client_addr_size = sizeof(client_addr);
    int client_sock = accept(server_sock, (struct sockaddr*)&client_addr, &client_addr_size);
    if (client_sock == -1) {
        puts("响应失败");
    }

    char status[] = "HTTP/1.0 200 OK\r\n";
    char header[] = "serverer: A Simple Web serverer\r\nContent-Type: text/html\r\n\r\n";
    char content[] = "hello world";

    write(client_sock, status, strlen(status));
    write(client_sock, header, strlen(header));
    write(client_sock, content, strlen(content));

    close(client_sock);
    close(server_sock);

    return 0;
}