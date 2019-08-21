<?php
    class database
    {
        public    $pdo;
        protected $db;
        protected $dbTable;
        protected $dbUser   = 'root';
        protected $dbPass   = '';
        // protected $dbPass   = 'mysql@1';
        protected $dbHost   = 'localhost';

        protected $operator;
        protected $service;
        protected $fromDate;
        protected $toDate;
        protected $adv;
        protected $prefix;
        protected $reportType;

        function __construct($operator, $service, $fromDate, $toDate, $adv = '', $reportType = '')
        {
            $this->operator     = $operator;
            $this->service      = $service ;
            $this->adv          = $adv;
            $this->fromDate     = $fromDate ;
            $this->toDate       = $toDate;
            $this->reportType   = $reportType;
            $this->init();
        }

        public function init()
        {
            if($this->reportType == 'cpaReport'){
                if($this->service == 'fb'){
                    $this->setDB('cpa'); 
                    if($this->operator == 'robi' || $this->operator == 'airtel'){
                        $this->dbTable  = 'cpaClick_robi';
                    }
                    else{
                        $this->dbTable  = 'cpaClick_blink';
                    }
                }

                else{ // Cycas Games
                    $this->setDB('sms');
                    $this->dbTable  = 'zcycas_cpa_airtel_cysgame';
                    $this->fromDate = $this->fromDate.' 00:00:00';
                    $this->toDate   = $this->toDate.' 23:59:59';
                }
            }
            else{ // Deactivation and Duration Report
                $this->fromDate = $this->fromDate.' 00:00:00';
                $this->toDate   = $this->toDate.' 23:59:59';
                if($this->operator == 'robi' || $this->operator == 'airtel'){
                    $this->setDB('sms');
                    $this->dbTable  = 'robi_subscribers';
                }
                else{
                    $this->setDB('gpwap');
                    $this->dbTable  = 'subscribers';
                }
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
            $stmt   = $this->pdo->prepare($query);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }

        public function getDurationData()
        {
            if($this->operator == 'robi'){
                $prefix = '88018';
                $query  = "SELECT DISTINCT msisdn , DATE(subs_date) AS subs_date, DATE(unsubs_date) AS unsubs_date FROM " .$this->dbTable. " WHERE msisdn LIKE '". $prefix ."%' AND service = '". $this->service ."' AND subs_date BETWEEN '" . $this->fromDate ."' AND '" . $this->toDate ."' AND status = '0' ORDER BY  subs_date DESC";
            }

            elseif($this->operator == 'airtel'){
                $prefix = '88016';
                $query  = "SELECT DISTINCT msisdn , DATE(subs_date) AS subs_date, DATE(unsubs_date) AS unsubs_date FROM " .$this->dbTable. " WHERE msisdn LIKE '". $prefix ."%' AND service = '". $this->service ."' AND subs_date BETWEEN '" . $this->fromDate ."' AND '" . $this->toDate ."' AND status = '0' ORDER BY  subs_date DESC";
            }

            else{
                $query  = "SELECT DISTINCT msisdn , DATE(subs_date) AS subs_date, DATE(unsubs_date) AS unsubs_date FROM " .$this->dbTable. " WHERE service = '". $this->service ."' AND subs_date BETWEEN '" . $this->fromDate ."' AND '" . $this->toDate ."' AND status = '0' ORDER BY  subs_date DESC";
            }

            $stmt   = $this->pdo->prepare($query);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
            
        }

        public function getDeactivationData()
        {
            if($this->operator == 'robi'){
                $prefix = '88018';
                $query  = "SELECT COUNT(msisdn) as deactivation, DATE(unsubs_date) as date FROM " . $this->dbTable . " WHERE msisdn LIKE '". $prefix ."%' AND service = '" . $this->service . "' AND unsubs_date BETWEEN '" . $this->fromDate ."' AND '" . $this->toDate ."' AND status = '0' GROUP BY date";
            }

            elseif($this->operator == 'airtel'){
                $prefix = '88016';
                $query  = "SELECT COUNT(msisdn) as deactivation, DATE(unsubs_date) as date FROM " . $this->dbTable . " WHERE msisdn LIKE '". $prefix ."%' AND service = '" . $this->service . "' AND unsubs_date BETWEEN '" . $this->fromDate ."' AND '" . $this->toDate ."' AND status = '0' GROUP BY date";
            }

            else{
                $query  = "SELECT COUNT(msisdn) as deactivation, DATE(unsubs_date) as date FROM " . $this->dbTable . " WHERE service = '" . $this->service . "' AND unsubs_date BETWEEN '" . $this->fromDate ."' AND '" . $this->toDate ."' AND status = '0' GROUP BY date";
            }
            $stmt   = $this->pdo->prepare($query);
            // echo $query;
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }
    }
?>