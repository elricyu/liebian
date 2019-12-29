@echo off
rem 判断是否存在QQ浏览器 并且在默认安装位置
if exist "C:\Program Files (x86)\Tencent\QQBrowser\QQBrowser.exe" (goto setcookie) else (goto err)

:setcookie
SETLOCAL ENABLEDELAYEDEXPANSION
for /f %%a in ('wmic nicconfig where^(ipenabled^=true^) get index^|findstr [0-99]') do set i=%%a
for /f %%a in ('wmic nicconfig where^(index^=%i%^) get macaddress^|findstr [0-99]') do set a=%%a &set a=!a::=-!

start "C:\Program Files (x86)\Tencent\QQBrowser\QQBrowser.exe" http://www.cc.com/JxcOrder/getmac?mac=%a%
exit

:err
rem 输出错误信息
color fC
echo 未找到QQ浏览器
echo 请安装QQ浏览器并登录系统后重新运行次注册程序
color f1
echo 若已安装QQ浏览器,请联系技术人员辅助注册
pause

