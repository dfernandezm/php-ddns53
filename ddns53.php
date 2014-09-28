

<?php

/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Holger Eilhard
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

require_once('r53.php');
require_once('IncR53.php');
$r53 = new Route53($awsid, $awskey);

function getPublicIP() {
  // create a new cURL resource
  $ch = curl_init();

  // set URL and other appropriate options
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_URL, "http://icanhazip.com");

  // grab URL and pass it to the browser
  $ip = curl_exec($ch);

  // close cURL resource, and free up system resources
  curl_close($ch);
  $ip = trim(preg_replace('/\s+/', ' ', $ip));
  return $ip;
}

function updateIP ($hostname, $newIP, $oldIP, $type = 'A', $ttl = 60) {
  global $r53;
  $delete = $r53->prepareChange('DELETE', $hostname, $type, $ttl, $oldIP);
  $result = $r53->changeResourceRecordSets('/hostedzone/YOUR-HOSTED-ZONE-ID', $delete);
  $create = $r53->prepareChange('CREATE', $hostname, $type, $ttl, $newIP);
  $result = $r53->changeResourceRecordSets('/hostedzone/YOUR-HOSTED-ZONE-ID', $create);
}

//print_r($r53->listHostedZones());
//print_r($r53->getHostedZone('/hostedzone/YOUR-HOSTED-ZONE-ID'));
$recordSet = $r53->listResourceRecordSets('/hostedzone/YOUR-HOSTED-ZONE-ID');
//print_r($recordSet['ResourceRecordSets']);

$hostname = 'YOUR-HOSTNAME';
for ($i = 0; $i < count($recordSet['ResourceRecordSets']); $i++) {
  if ($recordSet['ResourceRecordSets'][$i]['Name'] == $hostname) {
//    print_r($recordSet['ResourceRecordSets'][$i]);
//    echo $recordSet['ResourceRecordSets'][$i]['Name'];
    $oldIP = $recordSet['ResourceRecordSets'][$i]['ResourceRecords'][0];
    $type  = $recordSet['ResourceRecordSets'][$i]['Type'];
    $ttl   = $recordSet['ResourceRecordSets'][$i]['TTL'];
//    echo $oldIP;
  }}

$newIP = getPublicIP();



if ($oldIP == $newIP) {
//  echo "No change necessary.";
} else {
  echo "Updating IP from ".$oldIP." to ".$newIP;
  updateIP($hostname, $newIP, $oldIP);
//  echo " done.";
}
?>
