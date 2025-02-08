<?php
$db = new mysqli('localhost', 'lumihost_newsletter', 'MZTAGAkPgPaS34MECBLn', 'lumihost_newsletter');

if ($db->connect_error) {
    die('Connection failed: ' . $db->connect_error);
}
?>