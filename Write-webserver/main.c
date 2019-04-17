#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <string.h>

void request(const int* sock);
void response(const int* sock);
char* get_content_by_url(const char* url);


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
    int count = 0;
    while (count < 1) {
        socklen_t client_addr_size = sizeof(client_addr);
        int client_sock = accept(server_sock, (struct sockaddr*)&client_addr, &client_addr_size);
        if (client_sock == -1) {
            puts("响应失败");
        }

        request(&client_sock);
        response(&client_sock);

        count++;
    }

    close(server_sock);

    return 0;
}

void request(const int* sock)
{
    int client_sock = *sock;
    int max_size = 1024;

    char buffer[max_size];
    char method[max_size];
    char file_name[max_size];

    read(client_sock, buffer, sizeof(buffer) - 1);
    puts("请求头部");
    puts(buffer);

    char sample[] = "GET /index.html HTTP/1.1";
    char *p = strtok(sample, " ");

    // 解析HTTP请求方法
    strcpy(method, strtok(buffer, " "));
    // 解析HTTP请求路径
    strcpy(file_name, strtok(NULL, " "));

    puts("客户端请求的文件:");
    puts(file_name);
}


void response(const int* sock)
{
    int client_sock = *sock;
    char status[] = "HTTP/1.0 200 OK\r\n";
    char header[] = "serverer: A Simple Web serverer\r\nContent-Type: text/html\r\n\r\n";
    char content[] = "hello world";

    write(client_sock, status, strlen(status));
    write(client_sock, header, strlen(header));
    write(client_sock, content, strlen(content));

    close(client_sock);
}

char* get_content_by_url(const char* url)
{
    
}