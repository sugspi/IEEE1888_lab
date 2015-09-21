<!DOCTYPE html>
<html lang ="jp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- The above 3 mata tgs must be first of all meta tags-->

    <title>Storage Battery view</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" hrel="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>  
    <!-- highchart -->
    <script src="http://code.highcharts.com/highcharts.js"></script>
  </head>

  <body>
    <div class="container">
      <!-- centering -->
      <div class="row">
        <div class="col-md-1">
        </div>
        <!-- main contents -->
        <div class="col-md-10">
          <h1>this is storage battery monitor</h1>
          <!-- first area -->
          <h2>Eauipment standards</h2>
          <table class="table table-striped">
            <tr>
              <td>定格電圧</td>
              <td> 200V</td>
              <td>放電時効率</td>
              <td> 90%</td>
            </tr>
            <tr>
              <td>定格出力</td>
              <td> 40kW</td>
              <td>充電時効率</td>
              <td> 90%</td>
            </tr>
          </table>
          <!-- end of first area -->
          <!--second area -->
          <div class="row">
            <div class="col-md-6">
              <h2>交流出力</h2>
              <table class="table table-striped">
                <tr>
                  <td>電圧(V)</td>
                  <td id="vbt"></td>
                </tr>
                <tr>
                  <td>電流(A)</td>
                  <td id="ibt"></td>
                </tr>
                <tr>
                  <td>有効電力(kW)</td>
                  <td id="pbt"></td>
                </tr>
                <tr>
                  <td>無効電力(kVAr)</td>
                  <td id="qbt"></td>
                </tr>
                <tr>
                  <td>電力量(wh)</td>
                  <td id="whbt"></td>
                </tr>
                <tr>
                  <td>周波数(Hz)</td>
                  <td id="fbt"></td>
                </tr>
                <tr>
                  <td>力率(PF)</td>
                  <td id="pfbt"></td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <h2>蓄電池</h2>
              <table class="table table-striped">
                <tr>
                  <td>直流電圧(Vdc)</td>
                  <td id="vbdc"></td>
                </tr>
                <tr>
                  <td>直流電流(Adc)</td>
                  <td id="ibdc"></td>
                </tr>
                <tr>
                  <td>直流出力</td>
                  <td id="pbdc"></td>
                </tr>
              </table>
              <h2><br><h2>
              <h2>インバータ効率</h2>
              <table class="table table-striped">
                <tr>
                  <td>放電時効率</td>
                  <td id="nbdc"></td>
                </tr>
                <tr>
                  <td>充電時効率</td>
                  <td id="ndc"></td>
                </tr>
              </table>
            </div>
          </div>
          <!-- end of second area -->
          <!-- hight chart area -->
          <div id="PBDCchart" style="width:100%; height:400px;"></div>
          <div id="VBDCchart" style="width:100%; height:400px"></div>
          <div id="IBDCchart" style="width:100%; height:400px;"></div>
          <!-- hight chart area end-->
        </div>
        <!-- end of main contents -->

        <div class="col-md-1">
        </div>
      </div>
      <!-- centering end -->
    </div>


  <!-- all js -->
    <script>
     function panelShow(){
        /** PHPから返される結果格納用 */
        var result = null;
        var csv_lis;

        //GETメソッドで送るデータを定義します var data = {パラメータ名 : 値};
        var data = {request : "SEND"};  
        /**
        * Ajax通信メソッド
        * @param type  : HTTP通信の種類
        * @param url   : リクエスト送信先のURL
        * @param data  : サーバに送信する値
        */
        
        $.ajax({
            type: "POST",
            url: "BTpanel.php",
            data: data,
            dataType: "text",
            /**
             * Ajax通信が成功した場合に呼び出されるメソッド
             */
            success: function(data, dataType)
            {                  

                data = data.split(",");

                $('#vbt').text(data[0]+"V");
                $('#ibt').text(data[1]+"A");
                $('#pbt').text(data[2]+"kW");
                $('#qbt').text(data[3]+"kVAr");
                $('#whbt').text(data[4]+"Wh");
                $('#fbt').text(data[5]+"Hz");
                $('#pfbt').text(data[6]+" ");
                $('#vbdc').text(data[7]+"V");
                $('#ibdc').text(data[8]+"A");
                $('#pbdc').text(data[9]+"kW");
                $('#nbdc').text(data[10]+"%");
                $('#ndc').text(data[11]+"%");

            },
            /**
             * Ajax通信が失敗した場合に呼び出されるメソッド
             */
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
              //通常はここでtextStatusやerrorThrownの値を見て処理を切り分けるか、単純に通信に失敗した際の処理を記述します。

              this;
              //thisは他のコールバック関数同様にAJAX通信時のオプションを示します。

              //エラーメッセージの表示
              alert('Error : ' + errorThrown);
            }
        });
      }

      function  chartShow(){

        //現在時刻作成
        var date = new Date();
        var hour = date.getHours();
        var hours = new Array(24);

        for(i=1; i<=24; i++){
          if((hour+i ) > 23){
            hours[i-1] = String(hour+i-24);
            }else{
            hours[i-1] = String(hour+i);
          }
        }



        //GETメソッドで送るデータを定義します var data = {パラメータ名 : 値};
        var chart_data = {request : "SEND"}; 
        /**
        * Ajax通信メソッド
        * @param type  : HTTP通信の種類
        * @param url   : リクエスト送信先のURL
        * @param data  : サーバに送信する値
        */

        //alert("before ajax");

        $.ajax({
            type: "POST",
            url: "BTchart.php",
            data: chart_data,
            dataType: "text",
            
            /**
             * Ajax通信が成功した場合に呼び出されるメソッド
             */
            success: function(chart_data, dataType)
            {
              alert("success");
              chart_data = chart_data.split(",");
              
              var content = new Array();
              var c = 0;
              var j = 0;
              for(i=0; i<chart_data.length-1; i++){
                if(chart_data[i] == 'e'){
                  //alert("e"+c);;
                  j=0;
                  if(c==0) {PBDCchartShow(content, hours); c++;}else{
                    if(c==1){VBDCchartShow(content, hours); c++;}else{
                      if(c==2){IBDCchartShow(content,hours); c++;}
                    }
                  }
                  content.length = 0;
                  continue;
                }
                
                content[j] = Number(chart_data[i]);
                j++;
              }
            },
            /**
             * Ajax通信が失敗した場合に呼び出されるメソッド
             */
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
              //通常はここでtextStatusやerrorThrownの値を見て処理を切り分けるか、単純に通信に失敗した際の処理を記述します。

              this;
              //thisは他のコールバック関数同様にAJAX通信時のオプションを示します。

              //エラーメッセージの表示
              alert('Error : ' + errorThrown);
            }
        });
      }

      function  PBDCchartShow(content, hours){
        //alert("WD");
        //console.log(content);
         $('#PBDCchart').highcharts({
          title: {
            text: 'Hourly Charge and Discharge Power',
            x: -20 //center
          },
          subtitle: {
            text: 'test program',
            x: -20
          },
          xAxis: {
            categories: hours,
            title:{
              text: '時刻'
            },
          },
          yAxis: {
            title: {
              text: '充放電電力　(kW)'
          },
          plotLines: [{
            value: 0,
            width: 1,
              olor: '#808080'
          }]
          },
          tooltip: {
            valueSuffix: 'kW'
          },
          legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
          },
          series: [{
            name: 'power',
            data: content
          }]
        });       
      } 

      function  VBDCchartShow(content, hours){
        //alert("ws");
        //console.log(content);
         $('#VBDCchart').highcharts({
          title: {
            text: 'Hourly DC Voltage',
            x: -20 //center
          },
          subtitle: {
            text: 'test program',
            x: -20
          },
          xAxis: {
            categories: hours,
            title:{
              text: '時刻'
            },
          },
          yAxis: {
            title: {
              text: '直流電圧　(V)'
          },
          plotLines: [{
            value: 0,
            width: 1,
              olor: '#808080'
          }]
          },
          tooltip: {
            valueSuffix: 'V'
          },
          legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
          },
          series: [{
            name: 'DC voltage',
            data:content
          }]
        });      
      } 

      function  IBDCchartShow(content, hours){
        //alert("ewt");
         $('#IBDCchart').highcharts({
          title: {
            text: 'Hourly DC current',
            x: -20 //center
          },
          subtitle: {
            text: 'test program',
            x: -20
          },
          xAxis: {
            categories: hours,
            title:{
              text: '時刻'
            },
          },
          yAxis: {
            title: {
              text: '直流電流　(A)'
          },
          plotLines: [{
            value: 0,
            width: 1,
              olor: '#808080'
          }]
          },
          tooltip: {
            valueSuffix: 'A'
          },
          legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
          },
          series: [{
            name: 'DC current',
            data: content
          }]
        });      
      }

      $(document).ready(function() {

        //alert("doc ready");
        //panelShow();
        chartShow();

        //setInterval("panelShow()",5000);
        //setInterval("chartShow()",50000);
      });

    </script>
    <!-- all js end -->

    <!-- JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>

</html>
