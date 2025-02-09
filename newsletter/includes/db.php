<?php
$db = new mysqli('localhost', 'lumihost_news', 'C2Dk2cRvygXP2Sj2MeSM', 'lumihost_news');

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}
?>