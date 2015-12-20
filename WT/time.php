<?php
  for($t=24; $t>0; $t--){

    echo "24-$t\n\n";

    if($t<19)
    {
      $box1 = intval(18-$t);
      $box2 = intval(19-$t);

      $start = $box1;
      $end = $box2;
      
    }else{

      $box1 = intval(42-$t);
      $box2 = intval(43-$t);

      $start = $box1;
      $end = $box2;

      /*if((19-$t)<10){
        $start = "2015-09-28T0$box1:00:00+09:00";
      }else{
        $start = "2015-09-28T$box1:00:00+09:00";
      }

      if((20-$t)<10){
        $end = "2015-09-28T0$box2:00:00+09:00";
      }else{
        $start = "2015-09-28T$box2:00:00+09:00";
      }*/
    }

    if($t==19){
      $start = "23";
      $end = "00";
    }


    echo "start is $start\n";
    echo "end is $end\n\n\n";
  }
?>
