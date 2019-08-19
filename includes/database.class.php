<?php
    class database
    {
        public    $pdo;
        protected $db;
        protected $dbTable;
        protected $dbUser   = 'root';
        protected $dbPass   = '';
        protected $dbHost   = 'localhost';

        protected $operator;
        protected $service;
        protected $fromDate;
        protected $toDate;
        protected $adv;
        protected $prefix;

        function __construct($operator, $service, $fromDate, $toDate, $adv)
        {
            $this->operator = $operator;
            $this->service  = $service ;
            $this->adv      = $adv;
            $this->fromDate = $fromDate ;
            $this->toDate   = $toDate;
            $this->init();
        }

        public function init()
        {            
            if($this->service == 'fb'){
                $this->setDB('cpa'); 
                if($this->operator == 'robi' || $this->operator == 'airtel'){
                    $this->dbTable  = 'cpaclick_robi';
                }
                else{
                    $this->dbTable  = 'cpaclick_blink';
                }
            }

            else{ // Cycas Games
                $this->setDB('sms');
                $this->dbTable  = 'zcycas_cpa_airtel_cysgame';
                $this->fromDate = $this->fromDate.' 00:00:00';
                $this->toDate = $this->toDate.' 23:59:59';
            }
        }

        public function setDB( $db )
        {
            $this->db = $db;
            // echo 'Database Set: '. $this->db;
        }

        public function connect()
        {
            try
                {
                    $this->pdo = new PDO('mysql:host='. $this->dbHost .';dbname='.$this->db, $this->dbUser, $this->dbPass);
                    //echo 'Connected!';
                }
            catch (PDOException $e)
                {
                    exit('Error Connecting To DataBase');
                }
        }

        public function close()
        {
            $this->pdo = null;
        }

        public function getCPAData()
        {
            if($this->operator == 'robi'){
                $prefix = '88018';
                $query  = "SELECT COUNT(msisdn) as activation, DATE(d_date) as date FROM " . $this->dbTable . " WHERE msisdn LIKE '". $prefix ."%' AND adv = '" . $this->adv . "' AND d_date BETWEEN '" . $this->fromDate ."' AND '" . $this->toDate ."' GROUP BY DATE(d_date)";
            }

            elseif($this->operator == 'airtel'){
                $prefix = '88016';
                $query  = "SELECT COUNT(msisdn) as activation, DATE(d_date) as date FROM " . $this->dbTable . " WHERE msisdn LIKE '". $prefix ."%' AND adv = '" . $this->adv . "' AND d_date BETWEEN '" . $this->fromDate ."' AND '" . $this->toDate ."' GROUP BY DATE(d_date)";
            }

            else{
                $query  = "SELECT COUNT(msisdn) as activation, DATE(d_date) as date FROM " . $this->dbTable . " WHERE adv = '" . $this->adv . "' AND d_date BETWEEN '" . $this->fromDate ."' AND '" . $this->toDate ."' GROUP BY DATE(d_date)";
            }

            //$query  = "SELECT COUNT(msisdn) as activation, DATE(d_date) as date FROM " . $this->dbTable . " WHERE msisdn LIKE '". $prefix ."%' AND adv = '" . $this->adv . "' AND d_date BETWEEN '" . $this->fromDate ."' AND '" . $this->toDate ."' GROUP BY DATE(d_date)";
            $stmt   = $this->pdo->prepare($query);
            echo $query;
            echo '<br>';
            echo $this->db;
            echo '<br>';
            echo $this->dbTable;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    }
?>