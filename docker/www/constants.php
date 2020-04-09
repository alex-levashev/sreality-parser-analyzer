<?php
## Request Parameters
Global $category_sub_cb;

$category_type_cb = array(1 => "Prodej", 2 => "Pronajem", 3 => "Drazby");

# Real Eastate Type
$category_main_cb = array(1 => "Byty", 2 => "Domy", 3 => "Pozemky", 4 => "Komercni", 5 => "Ostatni");

# Real Estate Size/Config
$category_sub_cb = array(47 => "Pokoj", 2 => "1+kk", 3 => "1+1", 4 => "2+kk", 5 => "2+1", 6 => "3+kk", 7 => "3+1", 8 => "4+kk", 9 => "4+1", 10 => "5+kk", 11 => "5+1", 12 => "6 a vice", 16 => "Atypicky");

# Additional parameters
$something_more1 = array(3090 => "Balkon", 3110 => "Terasa", 3100 => "Lodzie", 3120 => "Sklep");
$something_more2 = array(3140 => "Parkovani", 3150 => "Garaz");
$something_more3 = array(3310 => "Vytah", 1820 => "Bezbarierovy");
$building_type_search = array(1 => "Panel", 2 => "Cihla", 3 => "Ostatni");

# Real Estate Ownership
$ownership = array(1 => "Osobni", 2 => "Druzstevni", 3 => "Statni/obecni");

# Floors (as inteval/range)
$floor_number = array(0 => "Patro od", 100 => "Patro do");

# Real estate condition
$building_condition = array(1	=> "Velmi dobrý", 2	=> "Dobrý", 3	=> "Špatný", 4	=> "Ve výstavbě", 5	=> "Developerské projekty", 6	=> "Novostavba", 7	=> "K demolici", 8	=> "Před rekonstrukcí", 9	=> "Po rekonstrukci");

# Price (as inteval/range)
$czk_price_summary_order2 = array(1000 => "od", 5000 => "do");

# Usable area (square meters as range/inteval)
$usable_area = array(0 => "Neomezeno", 10000000000 => "Neomezeno");

# Furiture
$furnished = array( 1	=> "Ano", 2	=> "Ne", 3	=> "Částečně");

# Advertisement age
$estate_age = array(0 => "Bez omezeni", 2 => "Den", 8 => "Poslednich 7 dni", 31 => "Poslednich 30 dni");

# Country
$locality_country_id = array(10001 => "Vse", 112 => "Ceska republika");

# CZ Regions
$locality_region_id = array(1 => "Jihocesky", 14 => "Jihomoravsky", 3 => "Karlovarsky", 6 => "Kralovehradecky", 5 => "Liberecky", 12 => "Moravskoslezsky", 8 => "Olomoucky", 7 => "Pardubicky", 2 => "Plzensky", 10 => "Praha", 11 => "Stredocesky", 4 => "Ustecky", 13 => "Vysocina", 9 => "Zlinsky");

# Prague districts
$locality_district_id = array(56 => "Praha-vychod", 57 => "Praha-zapad", 5001 => "Praha 1", 5002 => "Praha 2", 5003 => "Praha 3", 5004 => "Praha 4", 5005 => "Praha 5", 5006 => "Praha 6", 5007 => "Praha 7", 5008 => "Praha 8", 5009 => "Praha 9", 5010 => "Praha 10");

?>
