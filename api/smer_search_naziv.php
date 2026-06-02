<?php
$allowed_origins = [
    "https://timofej.asejev.ucim.in.rs",
    "http://localhost",
    "http://127.0.0.1",
];
$origin = $_SERVER["HTTP_ORIGIN"] ?? "";
foreach ($allowed_origins as $allowed) {
    if (str_starts_with($origin, $allowed)) {
        header("Access-Control-Allow-Origin: " . $origin);
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type");
        break;
    }
}
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit();
}
header("Content-Type: application/json; charset=UTF-8");
require_once "../baza/smer.php";

$q = trim($_GET["naziv"] ?? "");

if ($q === "") {
    echo json_encode(["ok" => false, "error" => "Parametar q je obavezan."]);
    exit();
}

$s = new smer();
$s->otvorivezu();
$stmt = $s->db->prepare(
    "SELECT idsmer, naziv_smera FROM smer WHERE naziv_smera LIKE ? ORDER BY idsmer ASC",
);
if (!$stmt) {
    $s->zatvorivezu();
    echo json_encode(["ok" => false, "error" => "Greška pri pripremi upita."]);
    exit();
}

$like = "%" . $q . "%";
$stmt->bind_param("s", $like);

if (!$stmt->execute()) {
    $stmt->close();
    $s->zatvorivezu();
    echo json_encode([
        "ok" => false,
        "error" => "Greška pri izvršavanju upita.",
    ]);
    exit();
}

$res = $stmt->get_result();
$data = [];
while ($row = $res->fetch_assoc()) {
    $data[] = [
        "ID" => (int) $row["idsmer"],
        "NAZIV" => $row["naziv_smera"],
    ];
}
$res->free();
$stmt->close();
$s->zatvorivezu();

echo json_encode(["ok" => true, "data" => $data]);
?>
