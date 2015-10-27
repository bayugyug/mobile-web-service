<?php
require_once('db.inc');
require_once('settings.inc');

mysql_set_charset('utf8');

$query = "select * from jos_port_airports order by id";
$stmt = $conn->prepare($query);
$stmt->execute();

$airports = $stmt->fetchAll(); 

for ($i = 0; $i < count($airports); $i++) {
   echo "insert into airports values(" . $airports[$i]['id']  
                                       . ",'" . $airports[$i]['country_code'] . "'"
                                       . ",'" . $airports[$i]['city_code'] . "'"
                                       . ",'" . $airports[$i]['airport_code'] . "'"
                                       . ",\"" . $airports[$i]['airport_name'] . "\""
                                       . ",\"" . $airports[$i]['url'] . "\");" . "<br/>";
}
?>
