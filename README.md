ThinkPHP 6.0
===============

##简介
简单的tp6后台，可用于所有的php项目，实现了权限设置和菜单管理，其他的随意添加  
  实现功能：
1. 权限设置
   - 菜单列表
   - 权限组
   - 管理员

运行环境
------
> php版本：php8  
> ***
> MySQL版本：8.0 数据库是跟目录下的 tp6.sql  
> ***
> 后台账号： admin 123456
>


##nginx伪静态
```md
if (!-e $request_filename) {
    rewrite  ^/index.php(.*)$  index.php?s=/$1  last;
        rewrite  ^(.*)$  /index.php?s=/$1  last;
    break;
}
```    
##Apache伪静态
```md
<IfModule mod_rewrite.c>
  Options +FollowSymlinks -Multiviews
  RewriteEngine On

  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ index.php?$1 [QSA,PT,L]
</IfModule>
```

##延伸开发
这是一个基础版本的，后期会不断添加一些新的功能，使它变得更加强大

