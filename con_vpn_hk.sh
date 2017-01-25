#!/bin/bash
vpnpid=$(ps -ef | grep "ssh -D" | grep -v grep | awk '{print $2}')
if test $vpnpid
then
echo "发现已有端口转发: $vpnpid."
echo "现在开始kill."
kill $vpnpid
else
echo "没有发现已存在ssh端口"
fi
echo "开始创建新的连接"
ssh -D 10000 root@47.52.2.172
ps -ef | grep ssh | grep -v grep