<?php
/**
 * =============================================================================
 * @author Clayton, sneaK
 * @version 2.0
 * @link https://github.com/supimfuzzy/csgo-multi-1v1 https://github.com/Sneaks-Community/rank-decay-script
 * =============================================================================
 */

require_once 'antisquatter.config.php';

$externalIP = $_SERVER['REMOTE_ADDR'];

if (isset($_GET['p']) && !empty($_GET['p'])) {
	$passphrase = $_GET['p'];
} else{
	die("<b>You aren't supposed to be here! Only follow links!<b>");
}

$decay_days = $decay_start * 86400;

ini_set('max_execution_time', 300);
$reset = 0;
$changes = 0;

if ($passphrase == $anti_squatter_pass) {
	$run_query = "SELECT $mysql_column_id, $mysql_column_score, $mysql_column_lastconnect, TRUNCATE((unix_timestamp(NOW()) - $mysql_column_lastconnect) / $decay_days, 0) AS elapsedtime_days FROM $mysql_table WHERE $mysql_column_score != $start_score AND $mysql_column_lastconnect > 0 AND (unix_timestamp(NOW()) - $mysql_column_lastconnect) > $decay_days";
	$query = mysqli_query($connect, $run_query);
	
	mysqli_autocommit($connect, false);

	while ($row = mysqli_fetch_assoc($query)) {
		$id = $row[$mysql_column_id];
		$score = $row[$mysql_column_score];
		$lastconnect = $row[$mysql_column_lastconnect];
		$elapsedtime_days = $row['elapsedtime_days'];
		
		if ($elapsedtime_days > $reset_days)
		{
			$result = mysqli_query($connect, "UPDATE $mysql_table SET $mysql_column_score='$start_score' WHERE $mysql_column_id='$id'");
			if (!$result) {
				file_put_contents("antisquatter.log", "DBERROR: . mysqli_error($connect) . \n", FILE_APPEND);
			}
			$reset++;
		}
		else
		{
			$rating_loss = $antisquatter_rate_loss * $elapsedtime_days * ($score - $start_score) / $score;
			$rating_gain = $antisquatter_rate_loss * $elapsedtime_days * ($start_score - $score) / (2 * $start_score - $score);
			
			if ($score > $start_score)
			{
				$score_new = number_format((float)$score - $rating_loss, 2, '.', '');
			}
			else{
				$score_new = number_format((float)$score + $rating_gain, 2, '.', '');
			}

			mysqli_query($connect, "UPDATE $mysql_table SET $mysql_column_score='$score_new' WHERE $mysql_column_id='$id'");
			$changes++;
		}
	}

	mysqli_commit($connect);

	if ($log_antisquatter == true) {
		file_put_contents("antisquatter.log", date('d/m/y h:i:s')." Script ran successfully. | Players Updated: ".$changes." | Players Reset: ".$reset."\n", FILE_APPEND);
	}
	echo "Complete. Updated $changes players.";
} else{
	if ($log_antisquatter == true) {
		file_put_contents("antisquatter.log", date('d/m/y h:i:s')." Invalid password from IP: ".$externalIP."\n", FILE_APPEND);
	}
	echo "<b>Incorrect Password!</b>";
}
?>
