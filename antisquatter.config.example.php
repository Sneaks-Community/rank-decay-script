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

$mysql_column_id = 'id';
$mysql_column_score = 'score';
$mysql_column_lastconnect = 'lastconnect';

$antisquatter_rate_loss = 25; //Rate loss constant. A higher value equals a higher rate loss per day of inactivity.
$start_score = 1800; //Starting score of new players
$decay_start = 5; //Time in days until point decay should occur
$reverse_decay = 2; //Divisor at which reverse decay should happen (1 is equal to decay rate, 0.5 would be twice the speed as decay rate, 2 would be half the speed of decay)
$log_antisquatter = true; //Keep IP logs and the number of player stats changes. For debugging and testing purposes only.

$connect = mysqli_connect($mysql_host,$mysql_user,$mysql_pass) or die('Cannot connect to server.');
$connect->set_charset('utf8mb4');
$select_db = mysqli_select_db($connect, $mysql_db) or die('Cannot find database.')
?>