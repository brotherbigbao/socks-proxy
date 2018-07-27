### 项目介绍

大家都知道香港虚拟机价格比较贵, 这个程序是使用阿里云提供的api创建按时收费实例，随用随开，用完即销，可以节省大量费用.

使用 aliyun-socks5-listen 创建本地代理端口

mac系统在 设置 网络 高级设置 代理 里配置socks代理， 代理地址是127.0.0.1, 端口是10000

或者在Chrome浏览器里装一个插件，SwitchyOmega, 同样是配置socks代理，仅供Chrome使用

Ubuntu或其它linux下类似

命令行下如果使用最好装一下privoxy,文档后面会详细写

目前不支持Windows, windows下配置比较麻烦

### PHP要求

PHP 5.3+

### 安装说明

1.可以直接下载这个压缩包，包含所有文件 

[aliyun-ecs.zip](http://openpublic.oss-cn-shanghai.aliyuncs.com/2018/aliyun-ecs.zip)

2.创建配置文件

将此项目目录下的aliyun-ecs/aliyun/config/config-recommend.php 复制为config/config.php

将你的阿里云控制台key等信息填写进去

3.可以开始使用了，注意阿里云对创建按时收费的实例有账户余额限制

```
cd aliyun-ecs/aliyun/

#查看有哪些机器
./aliyun list

#创建一台机器
./aliyun create

#关闭所有机器
./aliyun stop

#销毁所有机器
./aliyun destroy

#创建本地socks5代理端口 默认端口号是10000 需要变更请手动更改文件aliyun-socks5-listen
./aliyun-socks5-listen
```

> 命令执行过程中可能会存在php notice错误，是aliyun sdk的问题，可以忽略

4.关于本地端口代理的配置

mac系统在 设置 网络 高级设置 代理 里配置socks代理， 代理地址是127.0.0.1, 端口是10000

或者在Chrome浏览器里装一个插件，SwitchyOmega, 同样是配置socks代理，仅供Chrome使用

Ubuntu或其它linux下类似

目前不支持Windows, windows下配置比较麻烦

5.不用了记住一定要先关闭，再销毁；下次用的时候再创建一个；不然阿里云会一直扣费的


6.如果你是PHP开发者，也可以自己clone代码安装，详细 [点击这里](README_EXTRA.md)



