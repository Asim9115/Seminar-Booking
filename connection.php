<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "prologin_db";
$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
if(!$con)
{

	die("failed to connect!");
}
