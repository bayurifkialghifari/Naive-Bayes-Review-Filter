<?php

require_once './utils/DB.php';

$connection = DB::connect();

$query = "UPDATE rewiew_words SET positive = 0, negative = 0";
$result = DB::query($connection, $query);