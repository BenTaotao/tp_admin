ThinkPHP 6.0
===============


##简单的tp6后台，实现了权限设置和菜单管理

>运行环境：php8
> 数据库是跟目录下的 tp6.sql
> 超级管理员账号 admin 123456

##nginx伪静态
```php
if (!-e $request_filename) {
    rewrite  ^/index.php(.*)$  index.php?s=/$1  last;
        rewrite  ^(.*)$  /index.php?s=/$1  last;
    break;
}
```    
##Apache伪静态
```php
<IfModule mod_rewrite.c>
  Options +FollowSymlinks -Multiviews
  RewriteEngine On

  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ index.php?$1 [QSA,PT,L]
</IfModule>
```
