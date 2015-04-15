<?php

/**
 * PHP Script to be run as cron job
 *
 */
require_once('Route53DynDns.php');

// Change this with your AWS Route 53 details
$awsKey = "AWS-KEY";
$awsId = "AWS-ID";
$hostedZoneId = "HOSTED-ZONE-ID";
$hostNamesToChangeIp = array ("HOST-NAME-1","HOST-NAME-2", "HOST-NAME-3"];

Route53DynDns::updateIpForHostnameIfChanged($awsKey, $awsId, $hostedZoneId, $hostNamesToChangeIp);

?>


