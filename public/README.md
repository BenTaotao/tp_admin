
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

##URL重写，去掉index.php
    location / {
        if (!-e $request_filename) {
            rewrite  ^(.*)$  /index.php?s=/$1  last;
        }
    }