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
$server = new SoapClient("http://192.168.2.140/axis2/services/FIAPStorage?wsdl"); 
$var = array();

for($t=24; $t>0; $t--){
  
  /* select range of point priod.
  $s_start = strval($t*(-1))." hours"; 
  $s_end = strval(($t*(-1))+1)." hours";

  $start = date(DateTime::ATOM,strtotime($s_start));
  $end = date(DateTime::ATOM,strtotime($s_end));*/


  if($t>18)
  {
    $box1 = intval(42-$t);
    $box2 = intval(43-$t);

    $start = "2015-09-26T$box1:00:00+09:00";
    $end = "2015-09-26T$box2:00:00+09:00";
    
  }else{

    $box1 = intval(18-$t);
    $box2 = intval(19-$t);

    if($t>8){
      $start = "2015-09-27T0$box1:00:00+09:00";
    }else{
      $start = "2015-09-27T$box1:00:00+09:00";
    }

    if($t>9){
      $end = "2015-09-27T0$box2:00:00+09:00";
    }else{
      $end = "2015-09-27T$box2:00:00+09:00";
    }
  }

  if($t==19){
    $start = "2015-09-26T23:00:00+09:00";
    $end = "2015-09-27T00:00:00+09:00";
  }


  /*echo "24 - t is $t\n";
  echo "start is $start\n";
  echo "end is $end\n\n\n";*/

  // Select the Target Data Set
 /*$key1 = array("id"=>"http://www.gutp.jp/v1/wt/wd",
             "attrName"=>"time",
             "gteq"=>$start,
             "lt"  =>$end ); 

  $key1=array("id"=>"http://www.gutp.jp/v1/wt/ws",
             "attrName"=>"time",
             "gteq"=>$start,
             "lt"  =>$end ); */

  $key1=array("id"=>"http://www.gutp.jp/v1/wt/ewt",
             "attrName"=>"time",
             "gteq"=>$start,
             "lt"  =>$end ); 
   
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
            $var[24-$t] = sprintf('%.3f',($tmp/$data_size));
            $dump = $var[24-$t];
            //echo "$dump\n";
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

  for($i=0; $i<24; $i++){
    echo"$var[$i],";
  }

?>

