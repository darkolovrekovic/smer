<?php
    header("Content-Type: application/json; charset=UTF-8");
    require_once("../baza/smer.php");

    $s = new smer();
    $s->otvorivezu();

    $stmt = $s->db->prepare("SELECT idsmer, naziv_smera FROM smer ORDER BY idsmer ASC");

    if (!$stmt) {
        $s->zatvorivezu();
        echo json_encode(["ok" => false, "error" => "Greška pri pripremi upita."]);
        exit;
    }

    if (!$stmt->execute()) {
        $stmt->close();
        $s->zatvorivezu();
        echo json_encode(["ok" => false, "error" => "Greška pri izvršavanju upita."]);
        exit;
    }

    $res = $stmt->get_result();
    $data = [];

    while ($row = $res->fetch_assoc()) {
        $data[] = [
            "ID" => (int)$row["idsmer"],
            "NAZIV" => $row["naziv_smera"]
        ];
    }

    $stmt->close();
    $s->zatvorivezu();

    echo json_encode([
        "ok" => true, 
        "data" => $data
    ]);
?>