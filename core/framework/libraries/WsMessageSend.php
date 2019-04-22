<?php
	//aspx接口调用
    // class MessageSend
    // {
    // 	const url ="https://dx.ipyy.net/sms.aspx";
    // 	static function send($account,$password,$mobiles,$extno,$content,$sendtime)
    // 	{
    // 		$body=array(
    // 				'action'=>'send',
    // 				'userid'=>'',
    // 				'account'=>$account,
    // 				'password'=>$password,
    // 				'mobile'=>$mobiles,
    // 				'extno'=>$extno,
    // 				'content'=>$content,
    // 				'sendtime'=>$sendtime    				
    // 		);
    // 		$ch=curl_init();
    // 		curl_setopt($ch, CURLOPT_URL, self::url);
    // 		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    // 		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
    // 		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
    // 		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    // 		$result = curl_exec($ch);
    // 		curl_close($ch);
    // 		return $result;
    // 	}
    // }
    
    /*webservice调用
     * 注意以下扩展需要启用（widnows环境，linux等类似）
     * extension=php_soap.dll
     * extension=php_curl.dll
     * extension=php_openssl.dll
     */
    class WsMessageSend{
    	const wsdl="https://dx.ipyy.net/webservice.asmx?wsdl";
    	static function send($username,$password,$mobiles,$content,$extnumber,$plansendtime=null){
            header("Content-type:text/html;charset=utf-8");
    		$client=new SoapClient(self::wsdl);
    		$sms=array(
    				'Msisdns'=>$mobiles,
    				'SMSContent'=>$content,
    				'ExtNumber'=>$extnumber,
    		);
    		if($plansendtime!=null && $plansendtime!=''){
    			$sms['PlanSendTime']=$plansendtime;
    		}
    		// print_r($sms);
    		$body=array(
    				'userName'=>$username,
    				'password'=>$password,
					'sms'=>$sms
    		);
     		$result=$client->__call("SendSms", array($body));
    		//$client->__soapCall("SendSms", array($body));
    		if(is_soap_fault($result))
    		{
    			echo "faultcode:",$result->faultcode,"faultstring:",$result->faultstring;
    			return null;
    		}
    		else 
    		{
    			$data=$result->SendSmsResult;
    			return $data;
    		}
    	}
    }