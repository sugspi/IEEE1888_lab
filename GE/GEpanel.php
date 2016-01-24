<?php 
// UUID Generator
function uuid(){
  return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff), mt_rand( 0, 0xffff ),
    mt_rand( 0, 0x0fff ) | 0x4000,
    mt_rand( 0, 0x3fff ) | 0x8000,
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff), mt_rand( 0, 0xffff ));
} 
  
// Prepare Keys
$keys = array();
$keys[0] = array("id"=>"http://www.gutp.jp/v2/ge/vge", "attrName"=>"time", "select"=>"maximum");
$keys[1] = array("id"=>"http://www.gutp.jp/v2/ge/ige", "attrName"=>"time", "select"=>"maximum"); 
$keys[2] = array("id"=>"http://www.gutp.jp/v2/ge/pge", "attrName"=>"time", "select"=>"maximum"); 
$keys[3] = array("id"=>"http://www.gutp.jp/v2/ge/qge", "attrName"=>"time", "select"=>"maximum"); 
$keys[4] = array("id"=>"http://www.gutp.jp/v2/ge/whge", "attrName"=>"time", "select"=>"maximum"); 
$keys[5] = array("id"=>"http://www.gutp.jp/v2/ge/fge", "attrName"=>"time", "select"=>"maximum"); 
$keys[6] = array("id"=>"http://www.gutp.jp/v2/ge/pfge", "attrName"=>"time", "select"=>"maximum"); 
$keys[7] = array("id"=>"http://www.gutp.jp/v2/ge/pv", "attrName"=>"time", "select"=>"maximum"); 
$keys[8] = array("id"=>"http://www.gutp.jp/v2/ge/ppmp", "attrName"=>"time", "select"=>"maximum"); 
$keys[9] = array("id"=>"http://www.gutp.jp/v2/ge/qgas", "attrName"=>"time", "select"=>"maximum"); 
$keys[10] = array("id"=>"http://www.gutp.jp/v2/ge/qgas2", "attrName"=>"time", "select"=>"maximum");
$keys[11] = array("id"=>"http://www.gutp.jp/v2/ge/tgec", "attrName"=>"time", "select"=>"maximum"); 
$keys[12] = array("id"=>"http://www.gutp.jp/v2/ge/tgeh", "attrName"=>"time", "select"=>"maximum"); 
$keys[13] = array("id"=>"http://www.gutp.jp/v2/ge/qge1", "attrName"=>"time", "select"=>"maximum"); 
$keys[14] = array("id"=>"http://www.gutp.jp/v2/ge/qge2", "attrName"=>"time", "select"=>"maximum"); 
$keys[15] = array("id"=>"http://www.gutp.jp/v2/ge/tw2c", "attrName"=>"time", "select"=>"maximum"); 
$keys[16] = array("id"=>"http://www.gutp.jp/v2/ge/tw2h", "attrName"=>"time", "select"=>"maximum"); 
$keys[17] = array("id"=>"http://www.gutp.jp/v2/ge/qw2", "attrName"=>"time", "select"=>"maximum"); 
$keys[18] = array("id"=>"http://www.gutp.jp/v2/ge/qw22", "attrName"=>"time", "select"=>"maximum"); 
$keys[19] = array("id"=>"http://www.gutp.jp/v2/ge/tb2c", "attrName"=>"time", "select"=>"maximum"); 
$keys[20] = array("id"=>"http://www.gutp.jp/v2/ge/tb2h", "attrName"=>"time", "select"=>"maximum");
$keys[21] = array("id"=>"http://www.gutp.jp/v2/ge/qb2", "attrName"=>"time", "select"=>"maximum"); 
$keys[22] = array("id"=>"http://www.gutp.jp/v2/ge/qb22", "attrName"=>"time", "select"=>"maximum"); 
$keys[23] = array("id"=>"http://www.gutp.jp/v2/ge/ta2c", "attrName"=>"time", "select"=>"maximum"); 
$keys[24] = array("id"=>"http://www.gutp.jp/v2/ge/ta2h", "attrName"=>"time", "select"=>"maximum"); 
$keys[25] = array("id"=>"http://www.gutp.jp/v2/ge/qa2", "attrName"=>"time", "select"=>"maximum"); 
$keys[26] = array("id"=>"http://www.gutp.jp/v2/ge/qa22", "attrName"=>"time", "select"=>"maximum"); 
$keys[27] = array("id"=>"http://www.gutp.jp/v2/ge/efgg", "attrName"=>"time", "select"=>"maximum"); 
$keys[28] = array("id"=>"http://www.gutp.jp/v2/ge/efgh", "attrName"=>"time", "select"=>"maximum"); 
$keys[29] = array("id"=>"http://www.gutp.jp/v2/ge/efgt", "attrName"=>"time", "select"=>"maximum"); 
  
// Generate Query, Header, and Transport for query
$query=array("type"=>"storage", "id"=>uuid(), "key"=>$keys);
$header=array("query"=>$query);
$transport=array("header"=>$header); 
$queryRQ=array("transport"=>$transport); 
  
// Call an IEEE1888 Storage server
// Specify the IP address of the SDK.
$server = new SoapClient("http://52.27.198.165/axis2/services/FIAPStorage?wsdl");
$queryRS = $server->query($queryRQ); 
  
// Parse IEEE1888 FETCH-Response 1 (Error Handling)
if($queryRS == NULL){
   echo "Error occured -- the result is empty.";
   exit;
}
if(!array_key_exists("transport",$queryRS)){
   echo "Error occured -- the transport in the result is empty.";
   exit;
}
$transport=$queryRS->transport;

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
  
// Parse IEEE1888 FETCH-Response 2 (Data Parsing, and Print out)
if(array_key_exists("body",$transport)){
  $body=$transport->body;
  if(array_key_exists("point",$body)){
    $points = $body->point;
    for($i=0;$i<count($points);$i++){
      if(count($points)==1){
        $point=$points;
      }else{
        $point=$points[$i];
            } 
      if(array_key_exists("value",$point)){
        $id=$point->id;
        $value=$point->value;

        $time=$value->time;
        $val=$value->_;

        header("Content-type: text/plain; charset=UTF-8");// is here ok?
        echo $val.",";
            }
       }
   }
}
?>

