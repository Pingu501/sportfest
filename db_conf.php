<?php

$dbConfig = [
	"host" => "127.0.0.1",
	"username" => "root",
	"password" => "",
	"name" => "sportfest"
];

$dbConnection = mysqli_connect($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['name']);
