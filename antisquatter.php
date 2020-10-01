<?php
/**
 * =============================================================================
 * @author Clayton
 * @version 1.3.6
 * @link https://github.com/supimfuzzy/csgo-multi-1v1
 * =============================================================================
 */

$mysql_host = 'host';
$mysql_user = 'user';
$mysql_pass = 'pass';
$mysql_db = 'db';
$mysql_table = 'table'; //Default table set by game plugin. Do not change this if you don't know what you are doing.

$anti_squatter_pass = "pass"; //Used to access antisquatter.php

$mysql_table_id = 'id';
$mysql_table_score = 'score';
$mysql_table_lastconnect = 'lastconnect';

$antisquatter_rate_loss = 25; //Rate loss constant. A higher value equals a higher rate loss per day of inactivity.
$start_score = 1800; //Starting score of new players
$decay_start = 5; //Time in days until point decay should occur
$reverse_decay = 2; //Divisor at which reverse decay should happen (1 is equal to decay rate, 0.5 would be twice the speed as decay rate, 2 would be half the speed of decay)
$log_antisquatter = true; //Keep IP logs and the number of player stats changes. For debugging and testing purposes only.

$connect = mysqli_connect($mysql_host,$mysql_user,$mysql_pass) or die('Cannot connect to server.');
$connect->set_charset('utf8mb4');
$select_db = mysqli_select_db($connect, $mysql_db) or die('Cannot find database.')
?>

<?php
$externalIP = $_SERVER['REMOTE_ADDR'];

if (isset($_GET['p']) && !empty($_GET['p'])) {
	$passphrase = $_GET['p'];
} else{
	die("<b>You aren't supposed to be here! Only follow links!<b>");
}

$decay_days = $decay_start * 86400;

ini_set('max_execution_time', 300);
$changes = 0;

if ($passphrase == $anti_squatter_pass) {
	$run_query = "SELECT $mysql_table_id, $mysql_table_score, $mysql_table_lastconnect, TRUNCATE((unix_timestamp(NOW()) - $mysql_table_lastconnect) / $decay_days, 0) AS elapsedtime_days FROM $mysql_table WHERE $mysql_table_score != $start_score AND $mysql_table_lastconnect > 0 AND (unix_timestamp(NOW()) - $mysql_table_lastconnect) > $decay_days";
	$query = mysqli_query($connect, $run_query);

	mysqli_autocommit($connect, false);


	while ($row = mysqli_fetch_assoc($query)) {
		$id = $row[$mysql_table_id];
		$score = $row[$mysql_table_score];
		$lastconnect = $row[$mysql_table_lastconnect];
		$elapsedtime_days = $row['elapsedtime_days'];

		$rating_loss = $antisquatter_rate_loss * $elapsedtime_days * ($score - $start_score) / $score;
		
		if ($rating_loss >= 0)
		{
			$score_new = number_format((float)$score - $rating_loss, 2, '.', '');
		}
		else{
			$score_new = number_format((float)$score - ($rating_loss / $reverse_decay), 2, '.', '');
		}

		mysqli_query($connect, "UPDATE $mysql_table SET $mysql_table_score='$score_new' WHERE $mysql_table_id='$id'");
		$changes++;
	}

	mysqli_commit($connect);

	if ($log_antisquatter == true) {
		file_put_contents("antisquatter.log", date('d/m/y h:i:s')." Script ran successfully. | Players updated: ".$changes."\n", FILE_APPEND);
	}
	echo "Complete. Updated $changes players.";
} else{
	if ($log_antisquatter == true) {
		file_put_contents("antisquatter.log", date('d/m/y h:i:s')." Invalid password from IP: ".$externalIP."\n", FILE_APPEND);
	}
	echo "<b>Incorrect Password!</b>";
}
?>
