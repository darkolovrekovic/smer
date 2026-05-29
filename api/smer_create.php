<?php
    header("Content-Type: application/json; charset=UTF-8");
    require_once("../baza/smer.php");

    $s = new smer();

    $s->naziv_smera = "";
    if (isset($_POST["NAZIV"])) $s->naziv_smera = $_POST["NAZIV"];

    $odgovor = array();

    if ($s->insert()) {
        $odgovor["ok"] = true;
        $odgovor["data"] = array(
            "ID" => $s->idsmer,
            "NAZIV" => $s->naziv_smera
        );
    } else {
        $odgovor["ok"] = false;
        $odgovor["error"] = "Greška pri unosu.";
    }

    echo json_encode($odgovor);
?>