### 项目介绍

大家都知道香港虚拟机价格比较贵, 这个程序是使用阿里云提供的api创建按时收费实例，随用随开，用完即销，可以节省大量费用.

### PHP要求

PHP 5.3+ (Mac已默认安装PHP, Ubuntu用户请使用apt安装php: apt install php)

### 安装说明

1.下载socks-proxy.phar

[aliyun-ecs.zip](http://openpublic.oss-cn-shanghai.aliyuncs.com/2018/aliyun-ecs.zip)

2.配置阿里云key, secret 及 服务器默认ROOT密码

./socks-proxy config

3.创建虚拟机

```
#查看有哪些机器
./socks-proxy.phar list

#创建一台机器
./socks-proxy.phar create

#创建代理端口
./socks-proxy.phar listen

#关闭机器
./socks-proxy.phar stop

#销毁机器 一定要先关闭，销毁后阿里云不会收费
./socks-proxy.phar destroy
```

4.关于本地端口代理的配置

mac系统在 设置 网络 高级设置 代理 里配置socks代理， 代理地址是127.0.0.1, 端口是10000

或者在Chrome浏览器里装一个插件，SwitchyOmega, 同样是配置socks代理，仅供Chrome使用

Ubuntu或其它linux下类似

目前不支持Windows, windows下配置比较麻烦

5.不用了记住一定要先关闭，再销毁；下次用的时候再创建一个；不然阿里云会一直扣费的



