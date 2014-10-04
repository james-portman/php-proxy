<?php

define(PASS,"password");
define(DESTINATION,"https://a.website.com");
define(DEBUG,false);


if ($_COOKIE['auth'] != PASS) {
	if ($_REQUEST['auth'] == PASS) {
		setcookie('auth',$_REQUEST['auth'],time() + (86400 * 365));
	} else {
		?><form method="post" action="/"><input type="text" name="auth"><input type="submit"></form><?
		exit;
	}
}

if (DEBUG) { print_r($_SERVER); }

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, DESTINATION.$_SERVER['REQUEST_URI']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$body = curl_exec($ch);
$info = curl_getinfo($ch);
if (DEBUG) { print_r($info); }

curl_close($ch);


do {
	if (false !== $start = strpos($body,"<script")) {
		if (false !== $end = strpos($body,"</script>")) {

			for ($i = $start; $i < $end+9; $i++) {
				$body[$i] = "";
			}

		} else {
			break;
		}
	}
} while ($start !== false);


do {
	if (false !== $start = strpos($body,"<iframe")) {
		if (false !== $end = strpos($body,"</iframe>")) {

			for ($i = $start; $i < $end+9; $i++) {
				$body[$i] = "";
			}

		} else {
			break;
		}
	}
} while ($start !== false);

header("Content-type: ".$info['content_type']);
print $body;
