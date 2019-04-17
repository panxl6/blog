#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <string.h>

// 编译前，请替换你的实际html文件所在目录
#define HTML_DIR "/home/panxl/CLionProjects/multiprocess/html/"

typedef struct{
    char* status;
    char* header;
    char content[2048];
} RESPONSE;

RESPONSE request(const int* sock);
void response(const int* sock, RESPONSE response);
RESPONSE get_content_by_url(const char* url);
RESPONSE get_404();
RESPONSE get_200();
RESPONSE get_normal_response(const char* str);


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
        return -1;
    }

    ret = listen(server_sock, 20);
    if (ret == -1) {
        puts("端口监听失败");
        return -1;
    }

    int count = 0;
    struct sockaddr_in client_addr;

    while (count < 10) {
        socklen_t client_addr_size = sizeof(client_addr);
        int client_sock = accept(server_sock, (struct sockaddr*)&client_addr, &client_addr_size);
        if (client_sock == -1) {
            puts("响应失败");
        }

        response(&client_sock, request(&client_sock));

        count++;
    }

    close(server_sock);

    return 0;
}

RESPONSE request(const int* sock)
{
    int client_sock = *sock;
    int max_size = 1024;

    char buffer[max_size];
    char method[max_size];
    char file_name[max_size];

    read(client_sock, buffer, sizeof(buffer) - 1);
    puts("请求头部");
    puts(buffer);

    // 解析HTTP请求方法
    strcpy(method, strtok(buffer, " /"));
    // 解析HTTP请求路径
    strcpy(file_name, strtok(NULL, " /"));

    puts("客户端请求的文件:");
    puts(file_name);

    return get_content_by_url(file_name);
}


void response(const int* sock, RESPONSE response)
{
    int client_sock = *sock;

    write(client_sock, response.status, strlen(response.status));
    write(client_sock, response.header, strlen(response.header));
    write(client_sock, response.content, strlen(response.content));

    close(client_sock);
}

RESPONSE get_content_by_url(const char* raw_url)
{
    char url[2048] = HTML_DIR;

    // 拼接文件名称
    strcat(url, raw_url);

    FILE *file = fopen(url, "r");
    if (file == NULL) {
        printf("请求的url不存在:%s\n", url);
        return get_404();
    }

    char buffer[1025];
    fread(buffer, 1, 1024, file);
    puts("文件内容:");
    puts(buffer);

    fclose(file);

    return get_normal_response(buffer);
}

RESPONSE get_normal_response(const char* str)
{
    RESPONSE response;

    response.status = "HTTP/1.0 200 OK\r\n";
    response.header = "serverer: A Simple Web serverer\r\nContent-Type: text/html\r\n\r\n";
    strcpy(response.content, str);

    return response;
}

RESPONSE get_404()
{
    RESPONSE not_found;

    not_found.status = "HTTP/1.0 200 Not Found\r\n";
    not_found.header = "Content-Type: text/html;charset=utf-8\r\n\r\n";
    strcpy(not_found.content, "404 您访问的页面不存在");

    return not_found;
}
