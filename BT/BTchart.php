<?php 
// UUID Generator
function uuid(){
  return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff), mt_rand( 0, 0xffff ),
    mt_rand( 0, 0x0fff ) | 0x4000,
    mt_rand( 0, 0x3fff ) | 0x8000,
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff), mt_rand( 0, 0xffff ));
} 

//get time
$now = date(DateTime::ATOM);
$ago = date(DateTime::ATOM,strtotime("- 24 hours"));



// Generate Stub
// Specify the IP address of the SDK
$server = new SoapClient("http://192.168.2.140/axis2/services/FIAPStorage?wsdl"); 
  
// Select the Target Data Set
$key1=array("id"=>"http://www.gutp.jp/v1/bt/pbdc",
           "attrName"=>"time",
           "gteq"=>$ago,
           "lt"  =>$now ); 

$key2=array("id"=>"http://www.gutp.jp/v1/bt/vbdc",
           "attrName"=>"time",
           "gteq"=>$ago,
           "lt"  =>$now ); 

$key3=array("id"=>"http://www.gutp.jp/v1/bt/ibdc",
           "attrName"=>"time",
           "gteq"=>$ago,
           "lt"  =>$now ); 
 
// Iteratively Retrieve Data and Print Out by FETCH protocol 
$cursor=NULL;
do{ 
  $query=array("type"=>"storage", "id"=>uuid(), "acceptableSize"=>"100", "key"=>array($key1, $key2, $key3));
  if($cursor!=NULL){
     $query["cursor"]=$cursor;
  }
  $header=array("query"=>$query);
  $transport=array("header"=>$header);
  $queryRQ=array("transport"=>$transport); 
  $queryRS=$server->query($queryRQ); 
  $transport=$queryRS->transport;
  if(!array_key_exists("header",$transport)){
    echo "Fatal errpr: no header in the transport \n";
    exit(0);
   }
    
  if(array_key_exists("OK",$transport->header)
    && array_key_exists("body",$transport)){

    $points=$transport->body->point;
    for($i=0;$i<count($points);$i++){
      $point=null;
      if(count($points)==1){
        $point=$points;
      }else{
        $point=$points[$i];
      }
      if(array_key_exists("value",$point)){
         $id=$point->id;
         $value=$point->value;
    
         for($j=0;$j<count($value);$j++){
           if(count($value)==1){
             $time=$value->time;
             $val=$value->_;
           }else{
             $time=$value[$j]->time;
             $val=$value[$j]->_;
           }
           echo $val.",";
         }
      }
      echo "e,";
    }
    if(array_key_exists("query",$transport->header)
       &&  array_key_exists("cursor",$transport->header->query)){
      $cursor=$transport->header->query->cursor;
    }else{
      $cursor=NULL;
    }
  }else if(array_key_exists("error",$transport->header)){
    echo $transport->header->error->_;
    exit(0);
  } 
 
}while($cursor!=NULL); 

?>

