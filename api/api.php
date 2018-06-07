<?php
   require_once("../rest.inc.php");


 class api extends REST
    {
        public $data = "";
        const DB_SERVER = "localhost";
        const DB_USER = "zairepro_dbuser";
        const DB_PASSWORD = "4dyn0rtech!";
        const DB = "zairepro_Projectory";
        const str = 'zprojects';

        private $db = NULL;
        
        public function __construct()
        {
            parent::__construct();// Init parent contructor
            $this->dbConnect();// Initiate Database connection
        }
        
        //Database connection
        public function dbConnect()
        {
            $this->db = mysql_connect(self::DB_SERVER,self::DB_USER,self::DB_PASSWORD);
            if($this->db)
                mysql_select_db(self::DB,$this->db);
        }
        
        //Public method for access api.
        //This method dynmically call the method based on the query string
        public function processApi()
        {
            $key_check = md5(self::str);
            $check = explode("|",$_REQUEST['rquest']);
            $page = $check[0];
            $key = $check[1];
            $Token = $check[2]; // nnnn
            
         if($key === $key_check)
            {
            	$func = strtolower(trim(str_replace("/","",$page)));
            	if((int)method_exists($this,$func) > 0)
                	$this->$func();
            	else
                	$this->response('',404);
            	// If the method not exist with in this class, response would be "Page not found".
            }
        
            else
            {
            	$this->response('',404);
            }
        }
        
        private function projects()
        {
            // Cross validation if the request method is GET else it will return "Not Acceptable" status
            if($this->get_request_method() != "GET")
            {
                $this->response('',406);
            }
            // Get DateTime
        $timezone = new DateTimeZone("Asia/Kolkata" );
        $date = new DateTime();
        $date->setTimezone($timezone );
        $l_PR_Date = $date->format( 'Ymd' );
            
$sql = mysql_query("SELECT PR.PR_id, PR.PR_Name,PR.PR_Desc,MO.MO_Amount as PR_Price FROM Projects as PR,Model as MO where PR.MO_id=MO.MO_id AND PR.PR_Status='C' and PR.PR_ExpiryDate >=".$l_PR_Date."", $this->db);

            if(mysql_num_rows($sql) > 0)
            {
                $result = array();
                $i=0;
                while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC))
                {
                $result[$i]['Project_id']=$rlt['PR_id'];
                $result[$i]['Project_Name']=$rlt['PR_Name'];
                $result[$i]['Project_Desc']=$rlt['PR_Desc'];
                $result[$i]['Project_Price']=$rlt['PR_Price'];
                  
                  $sql2 =mysql_query("SELECT SD.SD_Name FROM Project_SubDomains AS PS, SubDomain AS SD WHERE PS.SD_id = SD.SD_id AND PS.PR_id =".$rlt['PR_id']."", $this->db);
                // $technologies="";
                 $j=0;
			while($rlt2 = mysql_fetch_array($sql2,MYSQL_ASSOC))
                	{
                	$result[$i]['Project_Technology'][$j]=$rlt2['SD_Name'];
                		         //       $technologies=$technologies.",".$rlt2[0];
                      $j++;
                	}
              //$result[] = $rlt;
/*((PR_id->1, PR_Name->Name1, Technologies->PHP,HTML,Mysql,),(PR_id->2, PR_Name->Name2, Technologies->.NET,HTML,Mysql))*/

                   $i++; 
                }
               // print_r($result)   ;  
                // If success everythig is good send header as "OK" and return list of users in JSON format
                $this->response($this->json($result), 200);
            }
            $this->response('',204); // If no records "No Content" status
        }
        
      private function users()
        {
            // Cross validation if the request method is GET else it will return "Not Acceptable" status


            if($this->get_request_method() != "GET")
            {
                $this->response('',406);
            }
            $sql = mysql_query("SELECT UR_id, UR_Emailid, UR_EmailidDomain, UR_FirstName, UR_LastName FROM Users where UR_Tokenid = ''", $this->db);
            if(mysql_num_rows($sql) > 0)
            {
                $result = array();
                while($rlt = mysql_fetch_array($sql,MYSQL_ASSOC))
                {
                    $result[] = $rlt;
                }
                // If success everythig is good send header as "OK" and return list of users in JSON format
                $this->response($this->json($result), 200);
            }
            $this->response('',204); // If no records "No Content" status
       
    

 } 
  /* 
private function updateUsers()
   {

       if($this->get_request_method() != "PUT")
       {
           $this->response('',406);
       }
       $token_id = $this->_request['tokenid'];
       $UR_id = $this->_request['URid'];

       if(isset($token_id))
       {  
          mysql_query("Update users set UR_Tokenid=".$token_id." where UR_id = ".$UR_id."");
          $success = array('status' => "Success", "msg" => "Successfully one record deleted.");
          $this->response($this->json($success),200);
       }
       else
       {
         $this->response('',204); // If no records "No Content" status
       }
}  */
        
        public function json($data)
        {
            /*print_r($data);
            echo "<br/><br/>";
            $encoded = json_encode($data);
            print_r($encoded);
            
            $decoded = json_decode($encoded,true);
                        echo "<br/><br/>";
            print_r($decoded);
            return $data;*/
            
            if(is_array($data))
            {
                return json_encode($data);
            }
        }
    }
    
    // Initiiate Library
    $api = new API;
    $api->processApi();
   // print_r($result)   ; 
    ?>