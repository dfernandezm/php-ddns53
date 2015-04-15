<?php

/**
 * Utility to change the IP assigned to a host name in a Amazon Route 53 hosted zone
 *
 * It is a refactor of Holger Eilhard ddns53.php utility.
 *
 */
require_once('r53.php');
class Route53DynDns {

	private $hostedZoneId = "";
	private $hostNames = array();
	private $r53;

	private static function setup($awsKey, $awsId, $hostedZone, $hosts) {
		global $r53, $hostedZoneId, $hostNames;
		$r53 = new Route53($awsId, $awsKey);
		$hostedZoneId = $hostedZone;
		$hostNames = $hosts;
	}

	private static function getPublicIp() {

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

	private static function updateIp($hostname, $newIP, $oldIP, $type = 'A', $ttl = 60) {
  		global $r53, $hostedZoneId;

        $delete = $r53->prepareChange('DELETE', $hostname, $type, $ttl, $oldIP);
        $result = $r53->changeResourceRecordSets('/hostedzone/'.$hostedZoneId, $delete);
        $create = $r53->prepareChange('CREATE', $hostname, $type, $ttl, $newIP);
        $result = $r53->changeResourceRecordSets('/hostedzone/'.$hostedZoneId, $create);
	}

	public static function updateIpForHostnameIfChanged($awsKey, $awsId, $hostedZoneId, $hostNames) {
		global $r53;

		self::setup($awsKey, $awsId, $hostedZoneId, $hostNames);

        $newIp = self::getPublicIp();
        $recordSet = $r53->listResourceRecordSets('/hostedzone/'.$hostedZoneId);

        foreach ($hostNames as $hostName) {

            echo "Starting checking for changes for hosted zone ".$hostedZoneId. " and hostname ".$hostName. "\n";

            $oldIp = "";

            for ($i = 0; $i < count($recordSet['ResourceRecordSets']); $i++) {

                echo "Checking ".$recordSet['ResourceRecordSets'][$i]['Name']."\n";

                if ($recordSet['ResourceRecordSets'][$i]['Name'] === $hostName) {
                    echo "Retrieving data for hostname ".$recordSet['ResourceRecordSets'][$i]['Name']."\n";
                    $oldIp = $recordSet['ResourceRecordSets'][$i]['ResourceRecords'][0];
                    $type  = $recordSet['ResourceRecordSets'][$i]['Type'];
                    $ttl   = $recordSet['ResourceRecordSets'][$i]['TTL'];

                    if ($oldIp == $newIp) {
                        echo "No change necessary for $hostName.\n";
                    } else {
                        echo "Updating IP of ".$hostName." from ".$oldIp." to ".$newIp."\n";
                        self::updateIp($hostName, $newIp, $oldIp, 'A', $ttl);
                        echo "IP updated for $hostName.";
                    }

                    break;
                }
            }
        }
	}
}