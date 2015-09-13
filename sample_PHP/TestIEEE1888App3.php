<?php 
// Prepare Historical Times
$cur_time=intval(time()/60)*60;
$past0=date("Y-m-d\TH:i:sP",$cur_time);
$past1=date("Y-m-d\TH:i:sP",$cur_time-1*60);
$past2=date("Y-m-d\TH:i:sP",$cur_time-2*60);
$past3=date("Y-m-d\TH:i:sP",$cur_time-3*60);
$past4=date("Y-m-d\TH:i:sP",$cur_time-4*60);

// Prepare Points and Values
$points = array();

$values= array();
$values[0]=array("time"=>$past4, "_" => "25.7");
$values[1]=array("time"=>$past3, "_" => "25.4");
$values[2]=array("time"=>$past2, "_" => "25.3");
$values[3]=array("time"=>$past1, "_" => "25.4");
$values[4]=array("time"=>$past0, "_" => "25.5");
$points[0]=array("id"=>"http://jo2lxq.hongo.wide.ad.jp/test/Temperature",
				  "value" => $values);

$values= array();
$values[0]=array("time"=>$past4, "_" => "544");
$values[1]=array("time"=>$past3, "_" => "544");
$values[2]=array("time"=>$past2, "_" => "543");
$values[3]=array("time"=>$past1, "_" => "541");
$values[4]=array("time"=>$past0, "_" => "542");
$points[1]=array("id"=>"http://jo2lxq.hongo.wide.ad.jp/test/CO2",
				  "value" => $values);
  
$values= array();
$values[0]=array("time"=>$past4, "_" => "6584.0");
$values[1]=array("time"=>$past3, "_" => "6583.7");
$values[2]=array("time"=>$past2, "_" => "6583.6");
$values[3]=array("time"=>$past1, "_" => "6583.5");
$values[4]=array("time"=>$past0, "_" => "6583.3");
$points[2]=array("id"=>"http://jo2lxq.hongo.wide.ad.jp/test/Power",
				  "value" => $values);

// Summarize into PointSet
$pointSet=array("id"=>"http://jo2lxq.hongo.wide.ad.jp/test/",
				"point" => $points);

// Construct Body and Transport
$body=array("pointSet"=>$pointSet);
$transport=array("body"=>$body); 
$dataRQ=array("transport"=>$transport); 
  
// Call an IEEE1888 Storage server (data method)
// Specify the IP address of the SDK.
$server = new SoapClient("http://localhost/axis2/services/FIAPStorage?wsdl");
$dataRS = $server->data($dataRQ); 
  
// Parse IEEE1888 WRITE Response (Error Handling)
if($dataRS == NULL){
   echo "Error occured -- the result is empty.";
   exit;
}
if(!array_key_exists("transport",$dataRS)){
   echo "Error occured -- the transport in the result is empty.";
   exit;
}
$transport=$dataRS->transport;

if(!array_key_exists("header",$transport)){
   echo "Error occured -- the header in the transport is empty.";
   exit;
}
$header=$transport->header;

if(!array_key_exists("OK",$header)){
   if(!array_key_exists("error",$header)){
      echo "Error occured -- neither OK nor error presented in the header.";
      exit;
   }
   echo "Error:".$header->error->_;
   exit;
} 

echo "Succeeded...";
  
?>

