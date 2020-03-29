<?php
header('Content-Type: application/json');
include 'simple_html_dom.php';

 
$World_Url = file_get_html("https://www.worldometers.info/coronavirus/");
$CoronaCases    = str_replace(' ', '', $World_Url->find('div[class=maincounter-number]',0)->plaintext);
$Deaths         = str_replace(' ', '', $World_Url->find('div[class=maincounter-number]',1)->plaintext);
$Recovered      = str_replace(' ', '', $World_Url->find('div[class=maincounter-number]',2)->plaintext);

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  


$MA = 'https://moroccostats.herokuapp.com/stats/coronavirus/countries/morocco/'; // path to your JSON file
$MAdata = file_get_contents($MA, false, stream_context_create($arrContextOptions)); // put the contents of the file into a variable
$Morocco = json_decode($MAdata, true); // decode the JSON feed

$regions = 'https://moroccostats.herokuapp.com/stats/coronavirus/countries/morocco/regions'; // path to your JSON file
$data = file_get_contents($regions, false, stream_context_create($arrContextOptions)); // put the contents of the file into a variable
$Region_Name = json_decode($data, true); // decode the JSON feed

$REG = new \stdClass();
$REG->CASASETTAT = $Region_Name['CasaSettat'];
$REG->RABATSALKENITRA = $Region_Name['RabatSalKenitra'];
$REG->FESMEKNES = $Region_Name['Fsmeknes'];
$REG->MERRAKECHSAFI = $Region_Name['MarrakechSafi'];
$REG->TANGERTETOUANELHOCEIMA = $Region_Name['TangerTetouanAlHoceima'];
$REG->ORIENTAL = $Region_Name['Oriental'];
$REG->SOUSSMASSA = $Region_Name['SoussMassa'];
$REG->BENIMELLALKHNIFRA = $Region_Name['BeniMellalKhnifra'];
$REG->DARAATAFILALET = $Region_Name['Daraatafilalet'];
$REG->GUELMIMOUEDNOUN = $Region_Name['GuelmimOuedNoun'];
$REG->LAAYOUNSAKIAELHAMRA = $Region_Name['LayouneSakiaElHamra'];
$REG->DAKHLAOUEDEDDAHAB = $Region_Name['DakhlaOuedEdDahab'];


$CONTACTS = new \stdClass();
$CONTACTS->INSTITUT_PASTEUR = "052262062";
$CONTACTS->SOS_MEDECINS = "052289898";
$CONTACTS->CENTRE_ANTIPOISON = "0801000180";
$CONTACTS->POLICE = "190";
$CONTACTS->AMBULANCE = "150";
$CONTACTS->RENSEIGNMENT = "160";

$post_data = array(
  'REGIONS' => array(   
    $REG,
),
  'CONTACTS' => array(   
    $CONTACTS,
)
);


echo json_encode(
       
        array(
                "STATUT"=>"true",
                "MESSAGE"=>"Data fetched successfully!", 
                "WORLD-CASES"=>$CoronaCases,
                "WORLD-DEATHS"=>$Deaths,
                "WORLD-RECOVERED"=>$Recovered,
                "MA-CASES"=>$Morocco['totalcases'],
                "MA-TODAY-CASES"=>$Morocco['newcases'],
                "MA-DEATHS"=>$Morocco['totaldeaths'],
                "MA-TODAY-DEATHS"=>$Morocco['newdeaths'],
                "MA-RECOVERED"=>$Morocco['recovered'],
                "MA-CRITICAL"=>$Morocco['seriouscases'],
                "MA-TOTAL-PER-ONE-MILLION"=>$Morocco['total_per_one_million'],
                "MA-DEATHS-PER-ONE-MILLION"=>$Morocco['deaths_per_million'],
                "MA-ACTIVE-CASES"=>$Morocco['activecases'],
                "MA-FIRST-CASE"=>"Mars 01, 2020",
                )+ $post_data,JSON_PRETTY_PRINT, JSON_PRETTY_PRINT);


                ?>
