<?php
/**
*	PHP | OTUS HLA | UTF8 | inc/tools.mqtt.php
*	Home work
*	eXellenz (eXellenz@inbox.ru)
*	2024-02-27
*/

//====================================================================== CHECK
if (!defined('ROOT'))
{
	print 'Access denided.';
	exit(0);
}

//====================================================================== DEPENDENCIES
require_once 'inc/phpMQTT/phpMQTT.php';

//====================================================================== FUNCTIONS
function mqtt_publish_event_post($uid, $title, $text)
{
	$ts					= date('Y.m.d H:i', time());
	$event				= array(
								'ts'	=> $ts,
								'uid'	=> $uid,
								'title'	=> $title,
								'text'	=> $text
	);
	$json				= json_encode($event);
	$topic				= 'phpMQTT/otus-hla/post/' . $uid;
	$client				= 'phpMQTT-' . $uid;
	$mqtt				= new phpMQTT(MQTT_HOST, MQTT_PORT, $client);
	$mqtt->exception	= false;
	$result				= $mqtt->connect(true, NULL, MQTT_USER, MQTT_PASS);

	if ($result)
	{
		$mqtt->publish($topic, $json, 0, false);
		$mqtt->close();
		// Return success
		return array('result' => true, 'payload' => $json);
	}
	else
	{
		// Return success
		return array('result' => false, 'payload' => $mqtt->error);
	}
}
?>