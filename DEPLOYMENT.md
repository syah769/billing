# WIFI-DESA Deployment Guide

This guide provides instructions for deploying the WIFI-DESA (MIKHMON) application to a live server.

## Prerequisites

- Web server with PHP 7.4 or higher
- Access to upload files to your web server
- Basic knowledge of web server configuration

## Deployment Steps

### 1. Prepare Your Files

1. Download all the project files from your development environment
2. Make sure to include all necessary files and directories

### 2. Upload Files to Server

1. Upload all files to your web server using FTP, SFTP, or your hosting control panel
2. Ensure file permissions are set correctly:
   - Directories: 755 (drwxr-xr-x)
   - Files: 644 (rw-r--r--)
   - Configuration files: 600 (rw-------)

### 3. Configure Your Web Server

#### For Apache

Make sure the `.htaccess` file is present and contains appropriate rules. If not, create one with:

```
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . /index.php [L]
</IfModule>
```

#### For Nginx

Add the following to your server block:

```
location / {
    try_files $uri $uri/ /index.php?$query_string;
    gzip_static on;
}

location ~ \.php$ {
    try_files $uri =404;
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
    fastcgi_pass unix:/var/run/php/php7.4-fpm.sock; # Adjust this path as needed
    fastcgi_index index.php;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    fastcgi_param PATH_INFO $fastcgi_path_info;
}
```

### 4. Login Credentials

The application has been modified to support both encrypted and hardcoded credentials:

#### Admin Login
- Username: `amnasiac`
- Password: `0163968146`

#### Client Login
- Username: `wifidesa`
- Password: `wifidesa`

### 5. Troubleshooting

If you encounter login issues:

1. Check the `debug.log` file for authentication details
2. Verify that the encryption/decryption functions are working correctly
3. Use the hardcoded credentials as a fallback

### 6. Security Considerations

1. Change the default passwords after successful login
2. Consider implementing HTTPS for secure connections
3. Regularly update the application and server software

## Docker Deployment (Alternative)

If you prefer to use Docker:

1. Make sure Docker and Docker Compose are installed on your server
2. Upload the project files including `docker-compose.yml` and `nginx.conf`
3. Run `docker-compose up -d` to start the containers
4. Access the application at `http://your-server-ip:8080`

## Support

If you encounter any issues during deployment, please check the debug logs and ensure your server meets all requirements.
