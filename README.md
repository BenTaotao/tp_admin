ThinkPHP 6.0
===============


##简单的tp6后台，实现了权限设置和菜单管理

>运行环境：php8

##nginx伪静态
    if (!-e $request_filename) {
    	rewrite  ^/index.php(.*)$  index.php?s=/$1  last;
           	rewrite  ^(.*)$  /index.php?s=/$1  last;
    	break;
    }
    
##Apache伪静态
    <IfModule mod_rewrite.c>
      Options +FollowSymlinks -Multiviews
      RewriteEngine On
    
      RewriteCond %{REQUEST_FILENAME} !-d
      RewriteCond %{REQUEST_FILENAME} !-f
      RewriteRule ^(.*)$ index.php?$1 [QSA,PT,L]
    </IfModule>
