<!DOCTYPE html>
<html lang ="jp">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <!-- The above 3 mata tgs must be first of all meta tags-->

    <title>gas engineview</title>

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
          <h1>this is gas engine monitor</h1>
          <!-- first area -->
          <h2>Eauipment standards</h2>
          <table class="table table-striped">
            <tr>
              <td>定格電圧</td>
              <td> 200V</td>
              <td>熱供給量</td>
              <td> 52.5kW</td>
            </tr>
            <tr>
              <td>定格出力</td>
              <td> 35kW</td>
              <td>最高総合効率</td>
              <td> 85%</td>
            </tr>
          </table>
          <!-- end of first area -->
          <!--second area -->
          <div class="row">
            <div class="col-md-6">
              <!-- 交流出力-->
              <h2>交流出力</h2>
              <table class="table table-striped">
                <tr>
                  <td>電圧(V)</td>
                  <td id="vge"></td>
                </tr>
                <tr>
                  <td>電流(A)</td>
                  <td id="ige"></td>
                </tr>
                <tr>
                  <td>有効電力(kW)</td>
                  <td id="pge"></td>
                </tr>
                <tr>
                  <td>無効電力(kVAr)</td>
                  <td id="qge"></td>
                </tr>
                <tr>
                  <td>電力量(wh)</td>
                  <td id="whge"></td>
                </tr>
                <tr>
                  <td>周波数(Hz)</td>
                  <td id="fge"></td>
                </tr>
                <tr>
                  <td>力率(PF)</td>
                  <td id="pfge"></td>
                </tr>
              </table>
              <!--交流出力end -->

              <!-- 有効電力内訳-->
              <h2>有効電力内訳</h2>
              <table class="table table-striped">
                <tr>
                  <td>発電電力</td>
                  <td id="pv"></td>
                </tr>
                <tr>
                  <td>ポンプ消費電力</td>
                  <td id="ppmp"></td>
                </tr>
              </table>
              <!-- 有効電力内訳end-->

              <!-- ガス消費　-->
              <h2>ガス消費</h2>
              <table class="table table-striped">
                <tr>
                  <td>ガス流量(Fg)</td>
                  <td id="qgas"></td>
                </tr>
                <tr>
                  <td>ガス消費熱量</td>
                  <td id="qgas2"></td>
                </tr>
              </table>
              <!-- ガス消費end-->

              <!-- エネルギー効率　-->
              <h2>エネルギー効率</h2>
              <table class="table table-striped">
                <tr>
                  <td>発電効率</td>
                  <td id="efgg"></td>
                </tr>
                <tr>
                  <td>排熱回収効率</td>
                  <td id="efgh"></td>
                </tr>
                <tr>
                  <td>総合効率</td>
                  <td id="efgt"></td>
                </tr>
              </table>
              <!-- エネルギー効率endend-->
            </div>

            <div class="col-md-6">
              <h2>ガスエンジン冷却循環</h2>
              <table class="table table-striped">
                <tr>
                  <td>入り口温度(Tc)</td>
                  <td id="tgec"></td>
                </tr>
                <tr>
                  <td>出口温度(Th)</td>
                  <td id="tgeh"></td>
                </tr>
                <tr>
                  <td>循環流量(Fw)</td>
                  <td id="qge1"></td>
                </tr>
                <tr>
                  <td>供給熱量</td>
                  <td id="qge2"></td>
                </tr>
              </table>

              <h2>男子浴槽循環</h2>
              <table class="table table-striped">
                <tr>
                  <td>入り口温度(Tc)</td>
                  <td id="tb2c"></td>
                </tr>
                <tr>
                  <td>出口温度(Th)</td>
                  <td id="tb2h"></td>
                </tr>
                <tr>
                  <td>循環流量(Fw)</td>
                  <td id="qb2"></td>
                </tr>
                <tr>
                  <td>供給熱量</td>
                  <td id="qb22"></td>
                </tr>
              </table>
              <h2><br><h2>

              <h2>男子浴室給湯循環</h2>
              <table class="table table-striped">
                <tr>
                  <td>入り口温度(Tc)</td>
                  <td id="ta2c"></td>
                </tr>
                <tr>
                  <td>出口温度(Th)</td>
                  <td id="ta2h"></td>
                </tr>
                <tr>
                  <td>循環流量(Fw)</td>
                  <td id="qa2"></td>
                </tr>
                <tr>
                  <td>供給熱量</td>
                  <td id="qa22"></td>
                </tr>
              </table>

              <h2>若葉量給湯循環</h2>
              <table class="table table-striped">
                <tr>
                  <td>入り口温度(Tc)</td>
                  <td id="tw2c"></td>
                </tr>
                <tr>
                  <td>出口温度(Th)</td>
                  <td id="tw2h"></td>
                </tr>
                <tr>
                  <td>循環流量(Fw)</td>
                  <td id="qw2"></td>
                </tr>
                <tr>
                  <td>供給熱量</td>
                  <td id="qw22"></td>
                </tr>
              </table>

            </div>
          </div>
          <!-- end of second area -->
          <!-- hight chart area -->
          <div id="QGASchart" style="width:100%; height:400px;"></div>
          <div id="PVSchart" style="width:100%; height:400px"></div>
          <div id="QGE2chart" style="width:100%; height:400px;"></div>
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
            url: "GEpanel.php",
            data: data,
            dataType: "text",
            /**
             * Ajax通信が成功した場合に呼び出されるメソッド
             */
            success: function(data, dataType)
            {                  

                data = data.split(",");

                $('#vge').text(data[0]+"V");
                $('#ige').text(data[1]+"A");
                $('#pge').text(data[2]+"kW");
                $('#qge').text(data[3]+"kVAr");
                $('#whge').text(data[4]+"Wh");
                $('#fge').text(data[5]+"Hz");
                $('#pfge').text(data[6]+" ");
                $('#pv').text(data[7]+"kW");
                $('#ppmp').text(data[8]+"kW");
                $('#qgas').text(data[9]+"m^3/h");
                $('#qgas2').text(data[10]+"kW");
                $('#tgec').text(data[11]+"℃");
                $('#tgeh').text(data[12]+"℃");
                $('#qge1').text(data[13]+"L/min");
                $('#age2').text(data[14]+"kW");
                $('#tw2c').text(data[15]+"℃");
                $('#tw2h').text(data[16]+"℃");
                $('#qw2').text(data[17]+"L/min");
                $('#qw22').text(data[18]+"℃");
                $('#tb2c').text(data[19]+"℃");
                $('#tb2h').text(data[20]+"kW");
                $('#qb2').text(data[21]+"L/min");
                $('#qb22').text(data[22]+"℃");
                $('#ta2c').text(data[23]+"℃");
                $('#ta2h').text(data[24]+"kW");
                $('#qa2').text(data[25]+"L/min");
                $('#qa22').text(data[26]+"kW");
                $('#efgg').text(data[27]+"%");
                $('#efgh').text(data[28]+"%");
                $('#efgt').text(data[29]+"%")

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
            url: "GEchart.php",
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
                  if(c==0) {QGASchartShow(content, hours); c++;}else{
                    if(c==1){PVSchartShow(content, hours); c++;}else{
                      if(c==2){QGE2chartShow(content,hours); c++;}
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

      function  QGASchartShow(content, hours){
        //alert("WD");
        //console.log(content);
         $('#QGASchart').highcharts({
          title: {
            text: 'Hourly Gas Consumption',
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
              text: 'ガス消費　(kW)'
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
            name: 'gas consumption',
            data: content
          }]
        });       
      } 

      function  PVSchartShow(content, hours){
        //alert("ws");
        //console.log(content);
         $('#PVSchart').highcharts({
          title: {
            text: 'Generated Energy',
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
            data:content
          }]
        });      
      } 

      function  QGE2chartShow(content, hours){
        //alert("ewt");
         $('#QGE2chart').highcharts({
          title: {
            text: 'Hourly Exhausted heat recovery',
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
              text: '排熱回収　(kW)'
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
            name: 'Exhausted heat recovery',
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
