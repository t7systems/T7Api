Example Apache2 Configuration

```
  <VirtualHost *:80 >
    DocumentRoot "/path/to/T7Api/example/public"
    ServerName t7api.example.org

    <Directory /path/to/T7Api/example/public>
       AllowOverride all
       Options MultiViews FollowSymlinks
       Order allow,deny
       Allow from all
       Require all granted
    </Directory>
  </VirtualHost>
```  