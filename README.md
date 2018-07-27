### 项目介绍

大家都知道香港虚拟机价格比较贵, 这个程序是使用阿里云提供的api创建按时收费实例，随用随开，用完即销，可以节省大量费用.

使用 aliyun-socks5-listen 创建本地代理端口

### 安装说明

1. 将本项目clone到本地任一目录
```
git clone https://github.com/liuyibao/aliyun.git

composer install
```

2. 将这个项目clone到与上一步相同的目录, 此项目是阿里云官方api
```
git clone https://github.com/aliyun/aliyun-openapi-php-sdk.git
```

3. 创建配置文件

将此项目目录下的config/config-recommend.php 复制一份命名config/config.php

将你的阿里云控制台key等信息填写进去

4. 可以开始使用了，注意阿里云对创建按时收费的实例有账户余额限制

```
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