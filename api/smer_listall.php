<?php
    $allowed_origins = [
        "https://timofej.asejev.ucim.in.rs",
        "http://localhost",
        "http://127.0.0.1",
    ];

    $origin = $_SERVER['HTTP_ORIGINS'] ?? '';

    foreach ($allowed_origins as $allowed){
        if (str_starts_with($origin, $allowed)) {
            header("Access-Control-Allow-Origin: " . $origin);
            break;
        }
    }

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