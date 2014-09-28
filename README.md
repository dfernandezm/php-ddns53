php-ddns53
==========

A simple PHP script to update Amazon's Route 53 with a new public IP.

This is a simple hack to use Route 53 as a dynamic DNS host. This is a version based on [Holger Eilhard work](https://github.com/holgr/php-ddns53) work.

Usage
=====

It requires Dan Myers's [Amazon Route 53 PHP Class](http://sourceforge.net/projects/php-r53/) to work. A version of this class is provided here.

Fill the details of your AWS account in `ddns-change.php`

```php
$awsKey = "AWS-KEY";
$awsId = "AWS-ID";
$hostedZoneId = "HOSTED-ZONE-ID";
$hostNameToChangeIp = "HOST-NAME-TO-CHANGE-AS-LISTED";
```

Put `ddns-change.php`, `Route53DynDns.php` and `r53.php` files in the same folder. For a basic usage just run

```
php ddns-change.php
```

If you want to use this as a cron job with output to a log file, a basic utility script is provided for that.

Fill the full path of the PHP script (and log file) in `ddns-cron.sh`

```
FULL_SCRIPT_PATH=/path/to/script

FULL_LOG_PATH=/path/to/logs
...
```

Now you can create an entry in `crontab` with the full path for `ddns-cron.sh` script or execute the following:

```
./cron-installer.sh /path/to/ddns-cron.sh "30 * * * *"
```

which will install a cron job for the user which will execute the script every 30 minutes (change the cron
pattern accordingly).

