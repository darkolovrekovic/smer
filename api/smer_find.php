<?php
    header("Content-Type: application/json; charset=UTF-8");
    require_once("../baza/smer.php");

    $id = 0;

    if (isset($_POST["ID"])) $id = (int)$_POST["ID"];
    
    $odgovor = array();

    if ($id <= 0){
        $odgovor["ok"] = false;
        $odgovor["error"] = "ID je obavezan.";
    } else {
        $data = smer::vratiSmer($id);

        if (!$data){
            $odgovor["ok"] = false;
            $odgovor["error"] = "Nisam pronasao smer.";
        } else {
            $odgovor["ok"] = true;
            $odgovor["data"] = $data;
        }
    }
    echo json_encode($odgovor); 
?>