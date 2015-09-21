<!DOCTYPE html>
<html lang ="jp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- The above 3 mata tgs must be first of all meta tags-->

    <title>Solar power view</title>

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
          <h1>this is solar power monitor</h1>
          <!-- first area -->
          <h2>Eauipment standards</h2>
          <table class="table table-striped">
            <tr>
              <td>定格電圧</td>
              <td> 200V</td>
              <td>定格日射量</td>
              <td> 340kW</td>
            </tr>
            <tr>
              <td>定格出力</td>
              <td> 40kW</td>
              <td>定格総合効率</td>
              <td> 10.8%</td>
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
                  <td id="vpv"></td>
                </tr>
                <tr>
                  <td>電流(A)</td>
                  <td id="ipv"></td>
                </tr>
                <tr>
                  <td>有効電力(kW)</td>
                  <td id="ppv"></td>
                </tr>
                <tr>
                  <td>無効電力(kVAr)</td>
                  <td id="qpv"></td>
                </tr>
                <tr>
                  <td>電力量(wh)</td>
                  <td id="whpv"></td>
                </tr>
                <tr>
                  <td>周波数(Hz)</td>
                  <td id="fpv"></td>
                </tr>
                <tr>
                  <td>力率(PF)</td>
                  <td id="pfpv"></td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <h2>太陽電池</h2>
              <table class="table table-striped">
                <tr>
                  <td>日射量</td>
                  <td id="irpv"></td>
                </tr>
                <tr>
                  <td>パネル温度</td>
                  <td id="ptpv"></td>
                </tr>
                <tr>
                  <td>気温</td>
                  <td id="tpv"></td>
                </tr>
                <tr>
                  <td>直流電圧(Vdc)</td>
                  <td id="vpdc"></td>
                </tr>
                <tr>
                  <td>直流電流(Adc)</td>
                  <td id="ipdc"></td>
                </tr>
                <tr>
                  <td>直流出力</td>
                  <td id="ppdc"></td>
                </tr>
              </table>
              <h2><br><h2>
              <h2>エネルギー効率</h2>
              <table class="table table-striped">
                <tr>
                  <td>太陽電池効率</td>
                  <td id="efpv"></td>
                </tr>
                <tr>
                  <td>PCS変換効率</td>
                  <td id="efpi"></td>
                </tr>
                <tr>
                  <td>総合効率</td>
                  <td id="efpt"></td>
                </tr>
              </table>
            </div>
          </div>
          <!-- end of second area -->
          <!-- hight chart area -->
          <div id="TPVchart" style="width:100%; height:400px;"></div>
          <div id="IRPVchart" style="width:100%; height:400px"></div>
          <div id="PPDCchart" style="width:100%; height:400px;"></div>
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
            url: "PVpanel.php",
            data: data,
            dataType: "text",
            /**
             * Ajax通信が成功した場合に呼び出されるメソッド
             */
            success: function(data, dataType)
            {                  

                data = data.split(",");

                $('#vpv').text(data[0]+"V");
                $('#ipv').text(data[1]+"A");
                $('#ppv').text(data[2]+"kW");
                $('#qpv').text(data[3]+"kVAr");
                $('#whpv').text(data[4]+"Wh");
                $('#fpv').text(data[5]+"Hz");
                $('#pfpv').text(data[6]+" ");
                $('#irpv').text(data[7]+"kW");
                $('#ptpv').text(data[8]+"℃");
                $('#tpv').text(data[9]+"℃");
                $('#vpdc').text(data[10]+"V");
                $('#ipdc').text(data[11]+"A");
                $('#ppdc').text(data[12]+"kW");
                $('#efpv').text(data[13]+"%");
                $('#efpi').text(data[14]+"%");
                $('#efpt').text(data[15]+"%");

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
              alert('Error1 : ' + errorThrown);
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
            url: "PVchart.php",
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
                  if(c==0) {TPVchartShow(content, hours); c++;}else{
                    if(c==1){IRPVchartShow(content, hours); c++;}else{
                      if(c==2){PPDCchartShow(content,hours); c++;}
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
              alert('Error2 : ' + errorThrown);
            }
        });
      }

      function  TPVchartShow(content, hours){
        //alert("WD");
        //console.log(content);
         $('#TPVchart').highcharts({
          title: {
            text: 'Hourly Templature',
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
              text: '気温　(℃)'
          },
          plotLines: [{
            value: 0,
            width: 1,
              olor: '#808080'
          }]
          },
          tooltip: {
            valueSuffix: '℃'
          },
          legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
          },
          series: [{
            name: 'templature',
            data: content
          }]
        });       
      } 

      function  IRPVchartShow(content, hours){
        //alert("ws");
        //console.log(content);
         $('#IRPVchart').highcharts({
          title: {
            text: 'Hourly Amount of Solar Radiation',
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
              text: '日射量(kW)'
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
            name: 'solar radiation',
            data:content
          }]
        });      
      } 

      function  PPDCchartShow(content, hours){
        //alert("ewt");
         $('#PPDCchart').highcharts({
          title: {
            text: 'Hourly Generated energy',
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
            name: 'generated energy',
            data: content
          }]
        });      
      }

      $(document).ready(function() {

        //alert("doc ready");
        panelShow();
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
