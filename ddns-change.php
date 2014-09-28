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
$hostNameToChangeIp = "HOST-NAME-TO-CHANGE";

Route53DynDns::updateIpForHostnameIfChanged($awsKey, $awsId, $hostedZoneId, $hostNameToChangeIp);

?>


