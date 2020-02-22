# YLOJ

Yali High School Online Judge

## 网站端安装
### 安装
需求：mysql,nodejs&npm,php,redis,composer,php-mysql,php-fpm,php-phar,php-xml,php-zip.

```
git clone https://github.com/YLOJ/YLOJ
cd YLOJ
npm install
composer install
```

在`mysql`里面建立数据库，假设名字叫做`yloj_data`

```
mysql yloj_data -u root -p < database.sql
cp .env.example .env
php artisan key:generate
vim .env
```

### 启动
```
php artisan serve
```
之后YLOJ将在`localhost:8000`上启动开发服务器

如果要使用nginx或者apache，将网站根放在`YLOJ/public`。

web服务器配置参考[官方文档](https://learnku.com/docs/laravel/5.7/installation/2242)

## 评测端安装
需求：redis,python3,python3的pyyaml,pymysql,redis,psutil模块。

当前仅支持网站端评测端在同一台机器

```
git clone https://github.com/YLOJ/YLOJ-judger
cp env.example.py oj/env.py
vim oj/env.py
```
下载sandbox，并解压（下载链接咕咕咕）
```
python3 processor.py
```

## 实时显示评测进度
```
npm install -g laravel-echo-server
cd /path/to/YLOJ
cp laravel-echo-server-example.json laravel-echo-server.json
vim laravel-echo-server.json
laravel-echo-server start
```
## 开机自启评测机和laravel-echo-server 

laravel-echo-server.service:
```
[Unit]
Description=Laravel Echo Server
After=network.target redis-server.service

[Service]
Type=simple
WorkingDirectory=/path/to/YLOJ/
User=root
Group=root
ExecStart=/usr/bin/laravel-echo-server start
[Install]
WantedBy=multi-user.target
```

yloj-judger.service:
```
[Unit]
Description=YLOJ Judger
After=network.target mysql.service redis-server.service

[Service]
Type=simple
WorkingDirectory=/path/to/judger
User=root
Group=root
ExecStart=/usr/bin/python3 /path/to/judger/processor.py
LimitSTACK=infinity
[Install]
WantedBy=multi-user.target
```

如果要多开评测机，可以存为yloj-judger@.service:
```
[Unit]
Description=YLOJ Judger
After=network.target mysql.service redis-server.service

[Service]
Type=simple
WorkingDirectory=/path/to/judger%I
User=root
Group=root
ExecStart=/usr/bin/python3 /path/to/judger%I/processor.py
LimitSTACK=infinity
[Install]
WantedBy=multi-user.target
```
这样可以用yloj-judger@1 yloj-judger@2来启动。
