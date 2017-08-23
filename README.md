# Tiekaa    [![Build Status]]

A high performance open-source forum software written in PHP.




## Demo/Official Website

* [项目简体中文官网: www.tiekaa.com](http://www.tiekaa.com/)
* [Project's English Official Website: ]


## Requirements

* PHP version 5.4.0 or higher.
* The [__PDO_MYSQL__](http://php.net/manual/en/ref.pdo-mysql.php) PHP Package.
* MySQL version 5.0 or higher.
* The [__mod_rewrite__](http://httpd.apache.org/docs/2.2/mod/mod_rewrite.html) Apache module / [__ngx_http_rewrite_module__](https://github.com/lincanbin/Carbon-Forum/blob/master/nginx.conf) / [__ISAPI_Rewrite__](http://www.helicontech.com/isapi_rewrite/) IIS module / IIS7+. 
* The [__mod_headers__](http://httpd.apache.org/docs/2.2/mod/mod_headers.html) module is needed if you run Carbon Forum on Apache HTTP Server.

## Install

1. Ensure that the entire directory are writable.
2. Open ```http://www.yourdomain.com/install``` and install.
3. Open the Forum, and register. The first registered user will become administrator.

## Upgrade

1. Backup files( ```/upload/*``` ) and databases. 
2. Delete all files except ```/upload/*```, and upload the new version files that extract from the the latest version packet. 
3. Ensure that the entire directory are writable.
4. Open ```http://www.yourdomain.com/update``` and update.

## Features

* Mobile version. 
* Real-time notifications push. 
* Discussions Tags based with Quora/StackOverflow style. 
* High FE&BE performance. 
* Full asynchronous design, improve the loading speed. 
* Excellent search engine optimization (mobile adaptation Sitemap support) .
* Perfect draft saving mechanism. 
* The modern Notification Center (currently supported and @ replies).





## License

