<?php
    header("Content-Type: application/json; charset=UTF-8");
    require_once("../baza/smer.php");

    $s = new smer();

    $s->idsmer = 0;
    $s->naziv_smera = "";

    if (isset($_POST["ID"])) $s->idsmer = intval($_POST["ID"]);
    if (isset($_POST["NAZIV"])) $s->naziv_smera = $_POST["NAZIV"];

    $odgovor = array();

    if ($s->idsmer > 0 && trim($s->naziv_smera) !== "" && $s->updateSmer()) {
        $odgovor["ok"] = true;
        $odgovor["data"] = array(
            "ID" => $s->idsmer,
            "NAZIV" => $s->naziv_smera
        );
    } else {
        $odgovor["ok"] = false;
        if ($s->idsmer <= 0) {
            $odgovor["error"] = "Niste uneli ispravan ID.";
        } elseif (trim($s->naziv_smera) === "") {
            $odgovor["error"] = "Naziv smera ne može biti prazan.";
        } else {
            $odgovor["error"] = "Greška u bazi podataka pri izmeni.";
        }
    }

    echo json_encode($odgovor);
?>