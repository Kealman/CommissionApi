<?php

	class CommissionApiException extends Exception
	{
		private $params;

	    public function __construct($message, $code = 0, $params = array(), Exception $previous = null) {
	    	$this->params = $params;
	        parent::__construct($message, $code, $previous);
	    }

	    public function getParams(){
	    	return $this->params;
	    }
	}

	class CommissionApi {


		//Email
		private $login = "<you email>";

		//Password
		private $password = "<you password>";



		private $access_token;

		private $auth_url = "https://my.fbs.com/api/v1/user/login?client_token=commission";

		private $commission_url = "https://my.fbs.com/api/v1/commission/list";


		public function getCommissionOrders($date, $account){

			if(!isset($this->access_token)) $this->getToken();

			$querydata = array(
				"access_token" => $this->access_token,
				"date" => $date,
				"account" => $account
			);

			return $this->sendGetRequest($this->commission_url, $querydata);

		}

		private function getToken()
		 {
			  $postdata = array(
			       'username' => $this->login,
			       'password' => $this->password,
			  );


			  $data = $this->sendPostRequest($this->auth_url, $postdata);

			  $this->access_token = $data->access_token;

		 }
	 


		 private function sendGetRequest($url, $querydata = array())
		 {
	 		  return $this->sendRequest($url, $querydata, "GET");
		 }


		 private function sendPostRequest($url, $postdata) 
		 {
			  return $this->sendRequest($url, $postdata, "POST");
		 }

		 private function sendRequest($url, $data, $method){

		 	  $uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";
			  
		 	  $querydata = http_build_query($data);

			  $ch = curl_init($url);


  			  switch ($method) {
			  	case 'POST':
			  		curl_setopt($ch, CURLOPT_POST, 1);
			  		curl_setopt($ch, CURLOPT_POSTFIELDS, $querydata);
			  		break;
			  	case 'GET':
			  		if($querydata !== "") $url = $url . "?" . $querydata;
			  		break;
			  }

			  curl_setopt($ch, CURLOPT_URL, $url);
			  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			  
			  $result = curl_exec($ch);
			  $info = curl_getinfo($ch);

			  curl_close($ch);
			  
			  return $this->parseResonse($result, $info);

		 }

		 protected function parseResonse($response, $info){

		 	$res_data = json_decode($response);

		 	if(!isset($res_data)) throw new CommissionApiException("Wrong result", $info["http_code"], array('data' => $response));
		 	if(isset($res_data->error)) throw new CommissionApiException($res_data->error, $info["http_code"], isset($res_data->params) ? $res_data->params : null);
	 		return $res_data->result; 

		 }


	}


