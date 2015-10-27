<?php
require_once('db.inc');
require_once('settings.inc');

mysql_set_charset('utf8');

$query = "SELECT
    `tbl_port_airports`.*
    , `tbl_port_city`.`city_code`
    , `tbl_port_country`.`country_code`
FROM
    `DB_RCT`.`tbl_port_airports`
    INNER JOIN `DB_RCT`.`tbl_port_country` 
        ON (`tbl_port_airports`.`country_id` = `tbl_port_country`.`id`)
    INNER JOIN `DB_RCT`.`tbl_port_city` 
        ON (`tbl_port_airports`.`city_id` = `tbl_port_city`.`id`) ORDER BY `tbl_port_airports`.`id`";
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
