<?php
$mysqli = new mysqli('127.0.0.1:3306', 'carrfacy_Lift', 'Eirik16498991', 'Carrfacy_wp137');
if ($mysqli->connect_error) {
    die('Database connection failed: ' . $mysqli->connect_error);
}
echo '✅ Database connected successfully!'; 