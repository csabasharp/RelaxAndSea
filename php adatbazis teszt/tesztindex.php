<?php

require "./adatbkez/pdoop.php";
$pdoop = new PDOOP();

# $pdoop->select("SELECT * FROM userek WHERE nev LIKE ? GROUP BY ? ORDER BY ? ;");

$pdoop->connect();

$select = [
    "SELECT" => "*",
    "FROM" => "userek",
    /*
    "WHERE" => "",
    "GROUP BY" => "",
    "HAVING" => "",
    "ORDER BY" => "",
    "LIMIT" => ""
    */
];

# print_r($pdoop->select($select));
# print_r($pdoop->select("SELECT * FROM userek WHERE nev LIKE ? GROUP BY ? ORDER BY ? ;",array("Amy%","szuldat","nev")));

# $pdoop->insert("INSERT INTO szobak(tipus,meret) VALUES(?,?)",array('kicsi',2));
# $pdoop->insert("INSERT INTO foglalasok(szobaId, userId, felnott, fiatal, mettol, meddig) VALUES(?,?,?,?,?,?)",array(1,1,1,0,"2024-01-27","2024-02-05"));




$select = [
    "SELECT" => "*",
    "FROM" => "userek",
    #"WHERE" => "userek.nev LIKE 'Amy Freeman'",
    /*
    "GROUP BY" => "",
    "HAVING" => "",
    "ORDER BY" => "",
    "LIMIT" => ""
    */
];

print "<br>";
var_dump($pdoop->select($select));
print "<br><br>";
$select = [
    ":s0" => "nev",
    ":s1" => "szuldat"
];
print_r($pdoop->select("SELECT :s0, :s1 FROM userek",$select));

print_r(preg_split("/,/","nev, szuldat",-1));
?>