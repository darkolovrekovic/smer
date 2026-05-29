<?php
    class Konekcija{
        public $db = null;
        public $hostname = "darko.lovrekovic.ucim.in.rs";
        public $username = "darko.lovrekovic@darko.lovrekovic.ucim.in.rs";
        public $password = "Bsconom123";
        public $dbname = "smer_test";
        public function otvoriVezu(){
            $this->db = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);
            $this->db->set_charset("utf8mb4");
            if ($this->db->connect_error){
                die("Neuspesna konekcija: " . $this->db->connect_error);
            }
        }
        public function zatvoriVezu(){
            if($this->db != null){
                $this->db->close();
            }
        }
    }
?>