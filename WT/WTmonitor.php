<!DOCTYPE html>
<html lang ="jp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- The above 3 mata tgs must be first of all meta tags-->

    <title>wind power view</title>

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
          <h1>this is windpower monitor</h1>
          <!-- first area -->
          <h2>Eauipment standards</h2>
          <table class="table table-striped">
            <tr>
              <td>定格電圧</td>
              <td> 202V</td>
              <td>定格風速</td>
              <td> 125m/s</td>
            </tr>
            <tr>
              <td>定格出力</td>
              <td> 3.3W</td>
              <td>定格効率</td>
              <td> 3.4%</td>
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
                  <td id="vwt"></td>
                </tr>
                <tr>
                  <td>電流(A)</td>
                  <td id="iwt"></td>
                </tr>
                <tr>
                  <td>有効電力(kW)</td>
                  <td id="pwt"></td>
                </tr>
                <tr>
                  <td>無効電力(kVAr)</td>
                  <td id="qwt"></td>
                </tr>
                <tr>
                  <td>電力量(wh)</td>
                  <td id="whwt"></td>
                </tr>
                <tr>
                  <td>周波数(Hz)</td>
                  <td id="fwt"></td>
                </tr>
                <tr>
                  <td>力率(PF)</td>
                  <td id="pfwt"></td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <h2>風力</h2>
              <table class="table table-striped">
                <tr>
                  <td>風向</td>
                  <td id="wd"></td>
                </tr>
                <tr>
                  <td>風速</td>
                  <td id="ws"></td>
                </tr>
                <tr>
                  <td>風力エネルギー</td>
                  <td id="ewt"></td>
                </tr>
              </table>
              <h2><br><h2>
              <h2>エネルギー効率</h2>
              <table class="table table-striped">
                <tr>
                  <td>発電効率</td>
                  <td id="efwt"></td>
                </tr>
              </table>
            </div>
          </div>
          <!-- end of second area -->
          <!-- hight chart area -->
          <div id="WDchart" style="width:100%; height:400px;"></div>
          <div id="WSchart" style="width:100%; height:400px"></div>
          <div id="EWTchart" style="width:100%; height:400px;"></div>
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
            url: "WTpanel.php",
            data: data,
            dataType: "text",
            /**
             * Ajax通信が成功した場合に呼び出されるメソッド
             */
            success: function(data, dataType)
            {                  

                data = data.split(",");

                $('#vwt').text(data[0]+"V");
                $('#iwt').text(data[1]+"A");
                $('#pwt').text(data[2]+"kW");
                $('#qwt').text(data[3]+"kVAr");
                $('#whwt').text(data[4]+"Wh");
                $('#fwt').text(data[5]+"Hz");
                $('#pfwt').text(data[6]+" ");
                $('#wd').text(data[7]+"°");
                $('#ws').text(data[8]+"m/s");
                $('#ewt').text(data[9]+"kW");
                $('#efwt').text(data[10]+"%");

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
              alert('Error <pannel>: ' + errorThrown);
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
        var chart_data1 = {request : "SEND"}; 
        var chart_data2 = {request : "SEND"}; 
        var chart_data3 = {request : "SEND"}; 
        /**
        * Ajax通信メソッド
        * @param type  : HTTP通信の種類
        * @param url   : リクエスト送信先のURL
        * @param data  : サーバに送信する値
        */

        //alert("before ajax");

        $.ajax({
            type: "POST",
            url: "WTchart_key1_test.php",
            data: chart_data1,
            dataType: "text",
            
            /**
             * Ajax通信が成功した場合に呼び出されるメソッド
             */
            success: function(chart_data1, dataType)
            {
              chart_data1 = chart_data1.split(",");
              console.log(chart_data1);
              
              var content1 = new Array();
              for(k=0; k<24; k++){
                content1[k] = Number(chart_data1[k]);
              }
              
               WDchartShow(content1, hours);
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
              alert('Error2 : ' + errorThrown);
            }
        });

       $.ajax({
            type: "POST",
            url: "WTchart_key2_test.php",
            data: chart_data2,
            dataType: "text",
            
            /*
             * Ajax通信が成功した場合に呼び出されるメソッド
             */

            success: function(chart_data2, dataType)
            {
              alert("success chart "+i);
              chart_data2 = chart_data2.split(",");
              
              var content2 = new Array();
              for(k=0; k<24; k++){
                content2[k] = Number(chart_data2[k]);
              }
              
              WSchartShow(content2, hours);

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
              alert('Error2 : ' + errorThrown);
            }
        });

        $.ajax({
            type: "POST",
            url: "WTchart_key3_test.php",
            data: chart_data3,
            dataType: "text",
            
            /**
             * Ajax通信が成功した場合に呼び出されるメソッド
             */
            
            success: function(chart_data3, dataType)
            {
              alert("success chart "+i);
              chart_data3 = chart_data3.split(",");
              
              var content3 = new Array();
              for(k=0; k<24; k++){
                content3[k] = Number(chart_data3[k]);
              }
              
              EWTchartShow(content3,hours);

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
              alert('Error2 : ' + errorThrown);
            }
        });
      }

      function  WDchartShow(content, hours){
        console.log(content);
         $('#WDchart').highcharts({
          
          chart:{
           type:'scatter',
           zoomType:'xy'
          },
	  title: {
            text: 'Hourly Wind Direction',
            x: -20 //center
          },
          subtitle: {
            text: 'N:360°,W:270°,S:180°,E:90°,N:0°',
            x: -20
          },
          xAxis: {
            categories: hours,
            title:{
              text: '時間'
            },
          },
          yAxis: {
            title: {
              text: '風向　(°)'
          },
          plotLines: [{
            value: 0,
            width: 1,
              olor: '#808080'
          }]
          },
          tooltip: {
            valueSuffix: '°'
          },
          legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
          },
          series: [{
            name: 'Wind Directon',
            data: content
          }]
        });       
      } 

      function  WSchartShow(content, hours){
        //alert("ws");
        console.log(content);
         $('#WSchart').highcharts({
          title: {
            text: 'Hourly Wind Speed',
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
              text: '風速　(m/s)'
          },
          plotLines: [{
            value: 0,
            width: 1,
              olor: '#808080'
          }]
          },
          tooltip: {
            valueSuffix: 'm/s'
          },
          legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
          },
          series: [{
            name: 'Wind Speed',
            data:content
          }]
        });      
      } 

      function  EWTchartShow(content, hours){
        //alert("ewt");
         $('#EWTchart').highcharts({
          title: {
            text: 'Hourly Ewt',
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
              text: '発電電力　(kW)'
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
            name: 'EWT',
            data: content
          }]
        });      
      }

      $(document).ready(function() {

        //alert("doc ready");
        chartShow();
        panelShow();

        //setInterval("panelShow()",10000);
        //setInterval("chartShow()",3600000);
      });

    </script>
    <!-- all js end -->

    <!-- JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  </body>

</html>
