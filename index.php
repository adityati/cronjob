<?php
require ("cronjob.php");
$cronjob = new cronjob("cronjob","root","");
function cron(){
	echo "Cronjob Test, 10 second intervals";
}
$cronjob->defineCron("cron","10");

