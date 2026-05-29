<?php
    header("Content-Type: application/json; charset=UTF-8");
    require_once("../baza/smer.php");

    $id = 0;

    if (isset($_POST["ID"])) $id = (int)$_POST["ID"];

    $odgovor = array();

    if ($id <= 0) {
        $odgovor["ok"] = false;
        $odgovor["error"] = "ID je obavezan.";
    } else {
        $s = new smer();
        $s->idsmer = $id;

        if ($s->deleteJedanSmer()) {
            $odgovor["ok"] = true;
            $odgovor["message"] = "Smer obrisan.";
        } else {
            $odgovor["ok"] = false;
            $odgovor["error"] = "Greška pri brisanju.";
        }
    }

    echo json_encode($odgovor);
?>