<?php
//print_r(parse_ini_file("test.ini"));
$vals = parse_ini_file("db.ini");
echo $vals["host"];
echo $vals["user"];
echo $vals["password"];
?>