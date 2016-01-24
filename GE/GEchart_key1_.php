<?php 
// UUID Generator
function uuid(){
  return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff), mt_rand( 0, 0xffff ),
    mt_rand( 0, 0x0fff ) | 0x4000,
    mt_rand( 0, 0x3fff ) | 0x8000,
    mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff), mt_rand( 0, 0xffff ));
} 

// Generate Stub
// Specify the IP address of the SDK
$server = new SoapClient("http://52.27.108.165/axis2/services/FIAPStorage?wsdl"); 
$var = array();

for($t=25; $t>0; $t--){
  
  // select range of point priod.
  $s_start = strval($t*(-1))." hours"; 
  $s_end = strval(($t*(-1))+1)." hours";

  $start = date(DateTime::ATOM,strtotime($s_start));
  $end = date(DateTime::ATOM,strtotime($s_end));

  // Select the Target Data Set
  $key1 = array("id"=>"http://www.gutp.jp/v2/ge/qgas2",
             "attrName"=>"time",
             "gteq"=>$start,
             "lt"  =>$end ); 

  /*$key1=array("id"=>"http://www.gutp.jp/v1/wt/ws",
             "attrName"=>"time",
             "gteq"=>$start,
             "lt"  =>$end ); 

  $key1=array("id"=>"http://www.gutp.jp/v1/wt/ewt",
             "attrName"=>"time",
             "gteq"=>$start,
             "lt"  =>$end ); */
   
  // Iteratively Retrieve Data and Print Out by FETCH protocol 
  $cursor = NULL;
  $data_size = 0;
  $tmp = 0;
  do{ 
    $query = array("type"=>"storage", "id"=>uuid(), "acceptableSize"=>"1000", "key"=>array($key1));
    if($cursor != NULL){
       $query["cursor"] = $cursor;
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

      for($i=0; $i<count($points); $i++){
        $point=null;
        if(count($points)==1){
          $point=$points;
        }else{
          $point=$points[$i];
        }
        if(array_key_exists("value",$point)){
           $id=$point->id;
           $value=$point->value;

           $data_size += intval(count($value));

           for($j=0;$j<count($value);$j++){
             if(count($value)==1){
               $time=$value->time;
               $val=$value->_;
             }else{
               $time=$value[$j]->time;
               $val=$value[$j]->_;
             }
               $tmp += intval($val);
          }

          if(intval(count($value))!=1000){
            $var[25-$t] = sprintf('%.3f',($tmp/$data_size));
          }
        }
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
}

  for($i=0; $i<=23; $i++){
    echo"$var[$i],";
  }

?>

