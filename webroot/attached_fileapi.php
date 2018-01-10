<?php
require_once("Rest.inc.php"); 
date_default_timezone_set('asia/kolkata');

class API extends REST {
    public $data = "";
	const DB_SERVER = "localhost";
    const DB_USER = "wwwshant_ubd";
    const DB_PASSWORD = "eQ6q75cpAgWN";
    const DB = "wwwshant_ubd";

    private $db = NULL;
    public function __construct() {
        parent::__construct();  // Init parent contructor
        $this->dbConnect();    // Initiate Database connection     
    }
    public function __destruct() {
      $this->db = NULL;
    }
    
    

    private function dbConnect() {
        // Set up the database
		
        try {            
            $this->db = new PDO('mysql:host=' . self::DB_SERVER . ';dbname=' . self::DB, self::DB_USER, self::DB_PASSWORD);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();

           /* $error = array('Type' => 'Error', "Error" => 'Some Error From Server', 'Responce' => "");
            $this->response($this->json($error), 251);*/

        }
    }
 public function processApi() 
	{
        $func = strtolower(trim(str_replace("/", "", $_REQUEST['rquest'])));
	    if ((int) method_exists($this, $func) > 0){
           $this->$func();
		   }
        else{
            $this->response('', 404);  
			// If the method not exist with in this class, response would be "Page not found".
		}	
    }
 
//--------------  nikhil vyas DEVELOP API FUNCTION  START  --------------------  ///
	public function login() 
	{
		global $link;
		include_once("common/global.inc.php");
		if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
					$app_login_date=date('Y-m-d');
					$app_login_time=date('h:i:s a', time());
					$app_login_status=1;
					$app_login_where=0;
		$email_input=0;
		$password_input=0;
			if(isset($this->_request['username']) && isset($this->_request['password'])){
				if(isset($this->_request['username'])){
					@$email = $this->_request['username'];
					$email_check = $this->db->prepare("SELECT * FROM blood_users WHERE email=:email");
					$email_check->bindParam(":email", $email, PDO::PARAM_STR);
					$email_check->execute();
					if ($email_check->rowCount()>0) {
						$email_input=1;  
					}
					else
					{
					$error = array('status' => false, "Error" => "Username you've entered is incorrect.", 'Responce' => '');
					$this->response($this->json($error), 200); 
					}
				}
				if(isset($this->_request['password']))
				{
					@$password = $this->_request['password'];
					 $newpassword=md5($password);
					 $password_input=1;
				}
				if($password_input==1 && $email_input==1)
				{					
					$sql = $this->db->prepare("SELECT * FROM blood_users WHERE email=:email AND password=:password");
					$sql->bindParam(":email", $email, PDO::PARAM_STR);
					$sql->bindParam(":password", $newpassword, PDO::PARAM_STR);
					$sql->execute();
					if($sql->rowCount()==0) {
						$error = array('status' => false, "Error" => "Username and password not matched", 'Responce' => '');
						$this->response($this->json($error), 200);
					}else{
						$sql1 =$this->db->prepare("SELECT * FROM blood_users WHERE email = :email");
						$sql1->bindParam(":email", $email, PDO::PARAM_STR);
						$sql1->execute();
					   
							$row_gp = $sql1->fetch(PDO::FETCH_ASSOC);
								$result = array('id' => $row_gp['id'],
									'name' => $row_gp['name'],
									'email' =>$row_gp['email'],
									'mobile' => $row_gp['mobile'],
									'address' => $row_gp['address'],
									'blood_group' => $row_gp['blood_group']
									);

						$success = array('status' => true, "Error" => 'Username and password matched', 'login'=>$result);
						$this->response($this->json($success), 200);
					}
				}
			}
			else
			{
				$error = array('status' =>false, "Error" => "Username or password is empty", 'Responce' => '');
				$this->response($this->json($error), 400);
			}
		}
	
	public function registration(){
		
		global $link;
		include_once("common/global.inc.php");
		if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
					$app_login_date=date('Y-m-d');
					$app_login_time=date('h:i:s a', time());
$n_k='AIzaSyC86m1I6GreOYT-NOXxbGkHyzD5BcvGjGo';
					
		if(isset($this->_request['name']) && isset($this->_request['email']) && isset($this->_request['mobile']) && isset($this->_request['address']) && isset($this->_request['password']) && isset($this->_request['blood_group'])){
				
					if(isset($this->_request['email'])){
					@$email = $this->_request['email'];
					$email_check = $this->db->prepare("SELECT * FROM blood_users WHERE email=:email");
					$email_check->bindParam(":email", $email, PDO::PARAM_STR);
					$email_check->execute();
			if ($email_check->rowCount()>0) {
			$error = array('status' => false, "Error" => "Email is already exist", 'Responce' => '');
			$this->response($this->json($error), 200); 
			}
			}		
			if(isset($this->_request['mobile'])){
					@$mobile = $this->_request['mobile'];
					$email_check = $this->db->prepare("SELECT * FROM blood_users WHERE mobile=:mobile");
					$email_check->bindParam(":mobile", $mobile, PDO::PARAM_STR);
					$email_check->execute();
			if ($email_check->rowCount()>0) {
			$error = array('status' => false, "Error" => "Mobile is already exist", 'Responce' => '');
			$this->response($this->json($error), 200); 
			}
			}
				@$name = $this->_request['name'];
				@$email = $this->_request['email'];
				@$mobile = $this->_request['mobile'];
				@$address = $this->_request['address'];
				@$password = $this->_request['password'];
				@$blood_group = $this->_request['blood_group'];
				@$mdPassword = md5($password);
					$sql_insert = $this->db->prepare("INSERT into blood_users(name,blood_group,email,mobile,address,password,notification_key) VALUES(:name,:blood_group,:email,:mobile,:address,:password,:notification_key)");
					$sql_insert->bindParam(":name", $name, PDO::PARAM_STR);
$sql_insert->bindParam(":blood_group", $blood_group, PDO::PARAM_STR);
					$sql_insert->bindParam(":email", $email, PDO::PARAM_STR);
					$sql_insert->bindParam(":mobile", $mobile, PDO::PARAM_STR);
					$sql_insert->bindParam(":address", $address, PDO::PARAM_STR);
					$sql_insert->bindParam(":password", $mdPassword, PDO::PARAM_STR);
					$sql_insert->bindParam(":notification_key", $n_k, PDO::PARAM_STR);
					$sql_insert->execute();
		
					$sql1 =$this->db->prepare("SELECT * FROM blood_users ORDER BY id DESC LIMIT 1");
					$sql1->execute();
					   
							$row_gp = $sql1->fetch(PDO::FETCH_ASSOC);
								$result = array('id' => $row_gp['id'],
									'name' => $row_gp['name'],
									'email' =>$row_gp['email'],
									'mobile' => $row_gp['mobile'],
									'address' => $row_gp['address'],
									'blood_group' => $row_gp['blood_group']
									);
									
									//$result1 = array("logins" => $result);
								
								
				$success = array('status'=>true,"Error"=>'Data Insertes Successfully','logins'=>$result);
				$this->response($this->json($success), 200);
		}else{
			$error = array('status'=>false,"Error" => "Some Data is empty", 'Responce' => '');
			$this->response($this->json($error), 200);
		}
	}
    
public function search_blood_group(){
		
		global $link;
		include_once("common/global.inc.php");
		if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
					$app_login_date=date('Y-m-d');
					$app_login_time=date('h:i:s a', time());
		
		if(isset($this->_request['blood_group'])){
				@$blood_group = $this->_request['blood_group'];
				$result = array();
				$sql =$this->db->prepare( "SELECT * FROM blood_donors WHERE blood_group = :blood_group");
				$sql->bindParam(":blood_group", $blood_group, PDO::PARAM_INT);
				$sql->execute();
				$row_gp = $sql->fetchAll(PDO::FETCH_ASSOC);
				
					foreach($row_gp as $data){
					$result[]= array('id' => $data['id'],
								'name' => $data['name'],
								'mobile' =>$data['mobile'],
								'email' => $data['email'],
								'landmark' => $data['landmark'],
								'blood_group' => $data['blood_group']
								);
					}
				$success = array('status'=>true,"Error"=>'Data','donor_detail'=>$result);
				$this->response($this->json($success), 200);
				
		}else{
			$error = array('status'=>false,"Error" => "Some Data is empty", 'Responce' => '');
			$this->response($this->json($error), 200);
		}		
					
					
					
}	


public function blood_donor_detail(){
		
		global $link;
		include_once("common/global.inc.php");
		if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
					$app_login_date=date('Y-m-d');
					$app_login_time=date('h:i:s a', time());

	if(isset($this->_request['auto_id'])){
				@$auto_id = $this->_request['auto_id'];
				
				$sql =$this->db->prepare( "SELECT * FROM blood_donors WHERE id = :auto_id");
				$sql->bindParam(":auto_id", $auto_id, PDO::PARAM_INT);
				$sql->execute();
				$row_gp = $sql->fetch(PDO::FETCH_ASSOC);
						$result = array('id' => $row_gp['id'],
								'name' => $row_gp['name'],
								'blood_group' =>$row_gp['blood_group'],
								'mobile_no' => $row_gp['mobile'],
								'gender' => $row_gp['gender'],
								'Age' => $row_gp['age'],
								'weight' => $row_gp['weight'],
								'email' =>$row_gp['email'],
								'landmark' => $row_gp['landmark']
								
								);
					
				$success = array('status'=>true,"Error"=>'Data','donor_detail'=>$result);
				$this->response($this->json($success), 200);
				
		}else{
			$error = array('status'=>false,"Error" => "Some Data is empty", 'Responce' => '');
			$this->response($this->json($error), 200);
		}		
}
////
public function master_info() {
		
        global $link;
include_once("common/global.inc.php");
if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
			
$qry = "SELECT * FROM blood_benefits where id=1";
			$sql = $this->db->prepare($qry);
            $sql->execute();
            $row_banners = $sql->fetch(PDO::FETCH_ASSOC);
$why =$row_banners['question'];
			
                       
				
$qry1 = "SELECT * FROM blood_benefits where id=2";
			$sql = $this->db->prepare($qry1);
            $sql->execute();
               $row_bannerss = $sql->fetch(PDO::FETCH_ASSOC);
$benefits =$row_bannerss['question'];
				
					

                $result5 = array("why" => $why, "benefits" => $benefits);
if(!empty($result5))
{

               $success = array('status'=>true,"Error"=>'Data','info'=>$result5);
				$this->response($this->json($success), 200);	
}
else{

 $error= array('status'=>false,"Error"=>'No Data','info'=>'');
				$this->response($this->json($error), 200);	
}	
    }
////

public function blood_bank_detail(){
		
		global $link;
		include_once("common/global.inc.php");
		if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
					$app_login_date=date('Y-m-d');
					$app_login_time=date('h:i:s a', time());
				$result = array();	
				$sql =$this->db->prepare( "SELECT * FROM  blood_banks");
				$sql->execute();
				$row_gp = $sql->fetchAll(PDO::FETCH_ASSOC);
				
					foreach($row_gp as $data){


                                         if(!empty($data['image'])){
						$data['image'] = $site_url. "img/" .$data['image'];
						}else{
						$data['image'] = "";
						}
					$result[]= array('id' => $data['id'],
								'bank_name' => $data['bank_name'],
								'address' =>$data['address'],
								'contact_person' => $data['contact_person'],
								'contact_no1' => $data['contact_no1'],
								'contact_no2' => $data['contact_no2'],
								'image' => $data['image']
								);
					}
				$success = array('status'=>true,"Error"=>'Data','blood_bank_detail'=>$result);
				$this->response($this->json($success), 200);		
					
					
}

public function team_detail(){
		
		global $link;
		include_once("common/global.inc.php");
		if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
				$result = array();	
				$sql =$this->db->prepare( "SELECT * FROM  our_team");
				$sql->execute();
				$row_gp = $sql->fetchAll(PDO::FETCH_ASSOC);
				
					foreach($row_gp as $data){


						
						if(!empty($data['image'])){
						$data['image'] = $site_url. "team_members/" .$data['image'];
						}else{
						$data['image'] = "";
						}




					$result[]= array('id' => $data['id'],
								'name' => $data['name'],
								'designation' =>$data['designation'],
								'image' => $data['image']
								);
					}
				$success = array('status'=>true,"Error"=>'Data','our_team'=>$result);
				$this->response($this->json($success), 200);
}
//--------------           END OF nikhil vyas DEVELOP API FUNCTION                       --------------------  ///
	function sendmail($to,$from, $from_name, $subject, $message_web,  $is_gmail=true)
	{
		App::import('Vendor', 'PhpMailer', array('file' => 'phpmailer' . DS . 'class.phpmailer.php')); 
	
			global $error;
			
			$mail = new PHPMailer();
			$mail->IsSMTP();
			$mail->CharSet = 'UTF-8';
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = 'ssl'; 
			$mail->Host = 'smtp.googlemail.com';
			$mail->Port = 465;  
			$mail->Username = 'ankit.sisodiya@spsu.ac.in';  
			$mail->Password = '!QAZSPSU@WSX';
			$mail->SMTPDebug = 1; 
			$mail->From = $from;
			$HTML = true;	 
			$mail->WordWrap = 50; // set word wrap
			$mail->IsHTML($HTML);
			
			$mail->FromName= $from_name;
	
			$mail->Subject = $subject;
			$mail->Body = $message_web;
			 
			$mail->addAddress($to);
	
			if(!$mail->Send()) {
				$error = 'Mail error: '.$mail->ErrorInfo;
				return false;
			} else {
				$error = 'Message sent!';
				return true;
			}
		
	}



public function become_donor(){
		
		global $link;
		include_once("common/global.inc.php");
		if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
					$app_login_date=date('Y-m-d');
					$app_login_time=date('h:i:s a', time());

		if(isset($this->_request['user_id']) && isset($this->_request['name']) && isset($this->_request['gender']) && isset($this->_request['age']) && isset($this->_request['weight']) && isset($this->_request['mobile']) && isset($this->_request['email']) && isset($this->_request['landmark']) && isset($this->_request['blood_group']) && isset($this->_request['previous_blood_donation_date'])){
				@$user_id = $this->_request['user_id'];
				@$name = $this->_request['name'];
				@$gender = $this->_request['gender'];
				@$age = $this->_request['age'];
				@$weight = $this->_request['weight'];
				@$mobile = $this->_request['mobile'];
				@$email = $this->_request['email'];
				@$landmark = $this->_request['landmark'];
				@$blood_group = $this->_request['blood_group'];
				@$previous_blood_donation_date = $this->_request['previous_blood_donation_date'];
                 if(!empty($previous_blood_donation_date)){
					$previous_blood_donation_date = date('Y-m-d',strtotime($previous_blood_donation_date)); 
				 }
				
				
				$sql_insert = $this->db->prepare("INSERT into blood_donors(user_id,name,gender,age,weight,email,mobile,landmark,blood_group,previous_blood_donation_date) VALUES(:user_id,:name,:gender,:age,:weight,:email,:mobile,:landmark,:blood_group,:previous_blood_donation_date)");
					$sql_insert->bindParam(":user_id", $user_id, PDO::PARAM_STR);
					$sql_insert->bindParam(":name", $name, PDO::PARAM_STR);
					$sql_insert->bindParam(":gender", $gender, PDO::PARAM_STR);
					$sql_insert->bindParam(":age", $age, PDO::PARAM_STR);
					$sql_insert->bindParam(":weight", $weight, PDO::PARAM_STR);
					$sql_insert->bindParam(":mobile", $mobile, PDO::PARAM_STR);
					$sql_insert->bindParam(":email", $email, PDO::PARAM_STR);
					$sql_insert->bindParam(":landmark", $landmark, PDO::PARAM_STR);
					$sql_insert->bindParam(":blood_group", $blood_group, PDO::PARAM_STR);
					$sql_insert->bindParam(":previous_blood_donation_date", $previous_blood_donation_date, PDO::PARAM_STR);
					$sql_insert->execute();
		
					$sql1 =$this->db->prepare("SELECT * FROM blood_donors ORDER BY id DESC LIMIT 1");
					$sql1->execute();
					   
							$row_gp = $sql1->fetch(PDO::FETCH_ASSOC);
								$result = array('id' => $row_gp['id'],
									'user_id' => $row_gp['user_id'],
									'name' =>$row_gp['name'],
									'gender' => $row_gp['gender'],
									'age' => $row_gp['age'],
									'weight' => $row_gp['weight'],
									'email' => $row_gp['email'],
									'mobile' => $row_gp['mobile'],
									'landmark' => $row_gp['landmark'],
									'blood_group' => $row_gp['blood_group'],
									'previous_blood_donation_date' => $row_gp['previous_blood_donation_date']
									);
				$success = array('status'=>true,"Error"=>'Data Insertes Successfully','logins'=>$result);
				$this->response($this->json($success), 200);
		
		}else{
			$error = array('status'=>false,"Error" => "Some Data is empty", 'Responce' => '');
			$this->response($this->json($error), 200);
		}
}
///
///
public function push_token_update() {
		global $link;
		include_once("common/global.inc.php");
		if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
		$device_token=$this->_request['device_token'];
		$id=$this->_request['id'];
		
		$sql = $this->db->prepare("SELECT * FROM blood_users WHERE id=:id");
		$sql->bindParam(":id", $id, PDO::PARAM_STR);
			$sql->execute();
			if($sql->rowCount()>0)
			{
			$sql_update_token = $this->db->prepare("UPDATE `blood_users` SET device_token='".$device_token."' WHERE id='".$id."' LIMIT 1;");
			$sql_update_token->execute();
                $success = array('status' => true, "msg" => 'Yes', 'Responce' => '');
                $this->response($this->json($success), 200);
            } else {
                $error = array('status' => false, "Error" => "No", 'Responce' => '');
                $this->response($this->json($error), 200);
            }
	}

//////
public function blood_request(){
		
		include_once("common/global.inc.php");
global $link;
		if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
					$r_date=date('Y-m-d');
					$date = date('Y-m-d',strtotime($r_date));
					$time=date('h:i:s a', time());

		if(isset($this->_request['user_id']) && isset($this->_request['name']) && isset($this->_request['mobile_no']) && isset($this->_request['location']) && isset($this->_request['blood_group'])){

                                @$user_id = $this->_request['user_id'];
				@$name = $this->_request['name'];
				@$mobile = $this->_request['mobile_no'];
				@$location = $this->_request['location'];
@$blood_group= $this->_request['blood_group'];
				 
				
				    $sql_insert = $this->db->prepare("INSERT into blood_requests(user_id,name,mobile,location,blood_group,time,date)VALUES(:user_id,:name,:mobile,:location,:blood_group,:time,:date)");
					$sql_insert->bindParam(":user_id", $user_id, PDO::PARAM_STR);
					$sql_insert->bindParam(":name", $name, PDO::PARAM_STR);
					$sql_insert->bindParam(":mobile", $mobile, PDO::PARAM_STR);
					$sql_insert->bindParam(":location", $location, PDO::PARAM_STR);
$sql_insert->bindParam(":blood_group", $blood_group, PDO::PARAM_STR);
					$sql_insert->bindParam(":time", $time, PDO::PARAM_STR);
					$sql_insert->bindParam(":date", $date, PDO::PARAM_STR);
					$sql_insert->execute();
$r_id = $this->db->lastInsertId();

		
					$sql1 =$this->db->prepare("SELECT * FROM blood_requests where id='".$r_id."'");
		

					$sql1->execute();
					   
							        $row_gp = $sql1->fetch(PDO::FETCH_ASSOC);
									$b_blood_group = $row_gp['blood_group'];
									$b_mobile = $row_gp['mobile'];
									$b_location = $row_gp['location'];
									$b_name = $row_gp['name'];
									$b_date = $row_gp['date'];
									$b_time = $row_gp['time'];
									
								    $result = array('id' => $row_gp['id'],
									'user_id' => $row_gp['user_id'],
									'name' =>$row_gp['name'],
									'mobile' => $row_gp['mobile'],
									'location' => $row_gp['location'],
                                    'blood_group' => $row_gp['blood_group'],
									'date' =>$row_gp['date'],
									'time' => $row_gp['time']
									);
									
									
           $sql5 =$this->db->prepare("SELECT * FROM blood_users where blood_group='".$b_blood_group."'");
			$sql5->execute();

if($sql5->rowCount()>0)
{

$time1=date('Y-m-d G:i:s');
$qry_add_subss= $sql5->fetch(PDO::FETCH_ASSOC);
	$row_count_id1=$qry_add_subss['id'];
		$API_ACCESS_KEY=$qry_add_subss['notification_key'];
		 $device_token=$qry_add_subss['device_token'];


     	$device_token1=rtrim($device_token);
	
	$msg = array
	(
	'title' 	=> 'Blood Donor',
	'Message'	=> 'hello Donor',
       'requester_name'	=> $b_name,
	'location'	=> $b_location,
	'request_date'	=> $b_date,
	'request_time'	=> $b_time,
	'vibrate'	=> 1,
	'sound'		=> 1,
	);



$fields = array
(
	'registration_ids' 	=> array($device_token1),
	'data'			=> array("msg" =>$msg)
);

$headers = array
(
	'Authorization: key=' .$API_ACCESS_KEY,
	'Content-Type: application/json'
);
//print_r($headers);
//exit;

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($fields) );

$result121 = curl_exec($ch);
//$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
curl_close($ch);
}

								
				$success = array('status'=>true,"Error"=>'Your request successfully submitted','requests'=>$result);
				$this->response($this->json($success), 200);
		
		}else{
			$error = array('status'=>false,"Error" => "Some Data is empty", 'Responce' => '');
			$this->response($this->json($error), 200);
		}
}
//////////////////////////
public function fetch_past_blood_request(){
		global $link;
		include_once("common/global.inc.php");
		if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
                    @$user_id = $this->_request['user_id'];

					  $sql1 =$this->db->prepare("SELECT * FROM blood_requests where user_id='".$user_id."' ORDER BY id DESC");
                      $sql1->execute();
			   
					   if($sql1->rowCount()>0)
					   {
					           $row_gp1 = $sql1->fetchAll(PDO::FETCH_ASSOC);
								   foreach($row_gp1 as $row_gp)
								   {
								    $result[] = array('id' => $row_gp['id'],
									'user_id' => $row_gp['user_id'],
									'name' =>$row_gp['name'],
									'mobile' => $row_gp['mobile'],
									'location' => $row_gp['location'],
                                                                        'blood_group' => $row_gp['blood_group'],
									'date' =>$row_gp['date'],
									'time' => $row_gp['time']
									);
								   }
				$success = array('status'=>true,"Error"=>'Your past request data','request_data'=>$result);
				$this->response($this->json($success), 200);
		
		}else{
			$error = array('status'=>false,"Error" => "sorry you did not do any request for blood", 'Responce' => '');
			$this->response($this->json($error), 200);
		}
}
////
public function user_forgot_password() 
	{
		if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
		$mobile_no=$this->_request['mobile_no'];
			$check_mobile = $this->db->prepare("SELECT * FROM blood_users WHERE mobile='".$mobile_no."'");
			$check_mobile->execute();
			if ($check_mobile->rowCount() > 0) {
		      $fetch_s_logins = $check_mobile->fetch(PDO::FETCH_ASSOC);
			  $s_id=$fetch_s_logins['id'];
             $otp=(string)mt_rand(1000,9999);
			 $sql_s=$this->db->prepare("UPDATE `blood_users` SET otp='".$otp."' WHERE id='".$s_id."'");				                
			 $sql_s->execute();

			 
			$working_key='A7a76ea72525fc05bbe9963267b48dd96';
				$sms_sender='JAINTE';	
				//$sms=str_replace(' ', '+', $sms);
$sms=str_replace(' ', '+', 'Your one time OTP is: '.$otp.'');
				

$url= 'http://alerts.sinfini.com/api/web2sms.php?workingkey='.$working_key.'&sender='.$sms_sender.'&to='.$mobile_no.'&message='.$sms.'';
		//$url="http://alerts.sinfini.com/api/web2sms.php?workingkey=A7a76ea72525fc05bbe9963267b48dd96&sender=blogai&to=9636653883&message=Your+appointment+has+been+booked.";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);       
		$data = curl_exec($ch);
		curl_close($ch);



			// $result1 = array("otp" => $otp);
			$success = array('status' => true, "Error" => "Please enter your otp as a password.", 'otp' => $otp);
				$this->response($this->json($success), 200);
			}
			else{
				$error = array('status' => false, "Error" => "Mobile no did not find.", 'otp' => '');
				$this->response($this->json($error), 200);				
				} 	
		}
///////////
		public function change_forgot_password() 
	{
		if ($this->get_request_method() != "POST") {
            $this->response('', 406);
        }
		$otp=$this->_request['otp'];
		$password=$this->_request['password'];
		$mobile_no=$this->_request['mobile_no'];
			$check_mobile = $this->db->prepare("SELECT * FROM blood_users WHERE mobile='".$mobile_no."' AND otp='".$otp."'");
			$check_mobile->execute();
			if ($check_mobile->rowCount() > 0) {
			$fetch_s_logins = $check_mobile->fetch(PDO::FETCH_ASSOC);
			  $s_id=$fetch_s_logins['id'];
			$s_newpassword=md5($password);
			 $sql_s=$this->db->prepare("UPDATE `blood_users` SET password='".$s_newpassword."', otp=0 WHERE id='".$s_id."'");				                
			 $sql_s->execute();
			$success = array('status' => true, "Error" => "Password change successfully.");
				$this->response($this->json($success), 200);
			}
			else{
				$error = array('status' => false, "Error" => "Please Check Your Otp.");
				$this->response($this->json($error), 200);				
				} 			
	}
///////





//------        OTHER API

	function LoanApplication() 
	{
		include_once("common/global.inc.php");
        global $link;
		if(!empty($this->_request['application_no']))
		{
			@$application_no = $this->_request['application_no'];	
 			
			$sql = $this->db->prepare("SELECT * FROM ownership_details where application_no=:application_no");
			$sql->bindParam(":application_no", $application_no, PDO::PARAM_INT);
			$sql->execute(); 
			if ($sql->rowCount()>0) {
			//----  Basic_details
			$sql_basic = $this->db->prepare("SELECT * FROM basic_details where application_no=:application_no");
			$sql_basic->bindParam(":application_no", $application_no, PDO::PARAM_INT);
			$sql_basic->execute(); 
			$row_sql_basic = $sql_basic->fetch(PDO::FETCH_ASSOC);
			$user_id=$row_sql_basic['user_id'];
			$customer_ratings=$row_sql_basic['customer_ratings'];
			$partner=$row_sql_basic['partner'];
			$sell_offline=$row_sql_basic['sell_offline'];
			$credit_period=$row_sql_basic['credit_period'];
 			//------- Logins
			$sql_login = $this->db->prepare("SELECT * FROM logins where id=:user_id");
			$sql_login->bindParam(":user_id", $user_id, PDO::PARAM_INT);
			$sql_login->execute(); 
			$row_sql_login = $sql_login->fetch(PDO::FETCH_ASSOC);
			$email=$row_sql_login['email'];
			$first_name=$row_sql_login['first_name'];
			//**-------
			$sql_partner = $this->db->prepare("SELECT * FROM master_anchor_clients where id=:partner");
			$sql_partner->bindParam(":partner", $partner, PDO::PARAM_INT);
			$sql_partner->execute(); 
			$row_sql_partner = $sql_partner->fetch(PDO::FETCH_ASSOC);
			$partner_name=$row_sql_partner['name'];
			//------Response
 			$row_gp = $sql->fetch(PDO::FETCH_ASSOC);
 				
			$sql_business = $this->db->prepare("SELECT * FROM business_details where user_id=:user_id AND application_no=:application_no");
			$sql_business->bindParam(":user_id", $user_id, PDO::PARAM_INT);
			$sql_business->bindParam(":application_no", $application_no, PDO::PARAM_INT);
			$sql_business->execute(); 
			$row_sql_business = $sql_business->fetch(PDO::FETCH_ASSOC);
			$no_of_employees=$row_sql_business['no_of_employees'];
			$nature_of_business=$row_sql_business['nature_of_business'];
 				
				foreach($row_gp as $key=>$valye)	
					{
						$result = array('partner' =>$partner_name,'user_id' => $row_gp['user_id'],
						'name' =>$first_name,'dob' => $row_gp['dob'],
						'gender' =>$row_gp['gender'],'user_aadhar_no' => $row_gp['user_aadhar_no'],
						'user_pan' => $row_gp['user_pan'],
						'customer_ratings' =>$customer_ratings,
						'contact_uses' =>$email,
						'marital_status' =>$row_gp['marital_status'],
						'education' => $row_gp['education'],
						'no_of_employees' => $no_of_employees,
						'nature_of_business' =>$nature_of_business,
						'sell_offline' =>$sell_offline,
						'credit_period_supplier' =>$credit_period
 						);
					}
 				$result1 = array("ownership_details" => $result );
				$success = array('data' => $result1);
				$this->response($this->json($success), 200); 
			}
			else
			{
				$success = array( "Error" => 'No data found', 'data' => '');
				$this->response($this->json($success), 200);	
			}
 		}
		else
			{
				$success = array("Error" => 'No data found', 'data' => '');
				$this->response($this->json($success), 200);	
			}
	}	
	
	
	function processdefinitions() 
	{
		include_once("common/global.inc.php");
        global $link;
		if(!empty($this->_request['application_no']))
		{
			
			
		}
		
	}




    private function json($data) {

        if (is_array($data)) {
         
            return json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP );
        }
    }


}

// Initiiate Library    
$api = new API;
$api->processApi();
?>