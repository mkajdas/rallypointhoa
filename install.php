<?php
require_once("connectnolimit.php");
//  Place what you want for your first user in the next four variables.
$unitname = "";
$name = "";
$email = "";
$password = "";

$result = query("CREATE TABLE unit(id INTEGER PRIMARY KEY, name TEXT)");
$result = query("INSERT INTO unit(name) VALUES('" . escape_data($unitname). "')");
$unitid = insert_id("dog");
$result = query("CREATE TABLE user(id INTEGER PRIMARY KEY, unitid INTEGER, phone TEXT, name TEXT, email TEXT, pass TEXT, admin INTEGER, owner INTEGER, board INTEGER)");
$result = query("INSERT INTO user(unitid, email, pass, admin, owner, board, name) VALUES(" . escape_data($unitid). ", '" . strtolower(escape_data($email)). "', '" . escape_data($password). "', 1, 1, 1, '" .escape_data($name) . "')");
?>