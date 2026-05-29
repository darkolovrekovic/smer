<?php
    // require_once(__DIR__ . '/konekcija.php');
    require_once($_SERVER['DOCUMENT_ROOT']."/../elitni.dnevnik/konekcija.php");
        class smer extends Konekcija{
            public $idsmer = null;
            public $naziv_smera = null;

        public function insert() {
            $this->otvoriVezu();
            $stmt = $this->db->prepare("INSERT INTO smer (naziv_smera) VALUES (?)");
            if (!$stmt) {
                return false;
            }
            $stmt->bind_param("s", $this->naziv_smera);
            $ok = $stmt->execute();
            if ($ok) {
                $this->idsmer = $this->db->insert_id;
            }
            $stmt->close();
            $this->zatvoriVezu();
            return $ok;
        }

            public function updateSmer(){
                $this->otvoriVezu();
                $stmt = $this->db->prepare("UPDATE smer SET naziv_smera = ? WHERE idsmer = ?");
                $stmt->bind_param("si", $this->naziv_smera, $this->idsmer); 
                $ok = $stmt->execute();
                $stmt->close();
                $this->zatvoriVezu();
                return $ok;
            }

            public function deleteJedanSmer(){
                $this->otvoriVezu();
                $stmt = $this->db->prepare("DELETE FROM smer WHERE idsmer = ?");
                if (!$stmt) return false;
                $stmt->bind_param("i", $this->idsmer);
                $ok = $stmt->execute();
                $stmt->close();
                $this->zatvoriVezu();
                return $ok;
            }

            public static function vratiSmer($id){
        $smer = new smer();
        $smer->otvoriVezu();
        $stmt = $smer->db->prepare("SELECT idsmer, naziv_smera FROM smer WHERE idsmer = ?");
        
        if (!$stmt){ 
            $smer->zatvoriVezu(); 
            return null; 
        }
    
            $stmt->bind_param("i", $id);
            
            if (!$stmt->execute()){ 
                $stmt->close(); 
                $smer->zatvoriVezu();
                return null; 
            }
            
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            
            $stmt->close();
            $smer->zatvoriVezu();
            
            if (!$row) return null;
            
            return [
                "ID" => (int)$row["idsmer"],
                "NAZIV" => $row["naziv_smera"],
            ];
        }

        public static function listBy($ucenik, $predmet, $godina) {

            $db = new Konekcija();
            $db->otvoriVezu();

            $sql = "SELECT idocena, ocena, datum, komentar
                    FROM ocena
                    WHERE ucenik_iducenik = ?
                    AND predmeti_idpredmeti = ?
                    AND skolska_godina_idskolska_godina = ?
                    ORDER BY timestamp DESC, idocena DESC";

            $stmt = $db->db->prepare($sql);
            if (!$stmt) { 
                $db->zatvoriVezu(); 
                return []; 
            }

            $stmt->bind_param("iii", $ucenik, $predmet, $godina);
            $stmt->execute();

            $res = $stmt->get_result();
            $rows = [];
            if ($res) {
                while ($r = $res->fetch_assoc()) {
                    $rows[] = $r;
                }
            }

            $stmt->close();
            $db->zatvoriVezu();
            return $rows;
        }
    }
?>