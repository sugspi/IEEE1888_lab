---------------------------------------------------------
　IEEE1888 C言語版 プロトコルスタック・ソフトウェア
  　　　　                                 作者: 落合秀也
          　　　　                    バージョン: v201310
---------------------------------------------------------

１．はじめに
　このパッケージには、C言語版IEEE1888プロトコルスタックが
　ソースコードで含まれています。

　ファイル構成は以下の通りです。
　- ieee1888.h 　　　　　　　　メインのヘッダ・ファイル
　- ieee1888_client.c　　　　　IEEE1888のquery/dataメソッドのクライアント通信スタブ
　- ieee1888_object_factory.c　IEEE1888オブジェクト(transportやbody等)の生成工場
　- ieee1888_sample_gw.c 　　　GWのサンプル実装
　- ieee1888_sample_app.c　　　APPのサンプル実装
　- ieee1888_server.c　　　　　IEEE1888のquery/dataメソッドのサーバ通信スタブ
　- ieee1888_util.c　　　　　　便利な機能(オブジェクトのダンプ表示等)の集合
　- ieee1888_XMLgenerator.h　　XMLシリアル化のためのヘッダ・ファイル
　- ieee1888_XMLgenerator.c　　IEEE1888オブジェクトのXMLシリアル化機能(生成器)実装
　- ieee1888_XMLparser.h 　　　XMLデシリアル化のためのヘッダ・ファイル
　- ieee1888_XMLparser.c 　　　IEEE1888オブジェクトのXMLデシリアル化機能(解析機)実装
　- ieee1888_datapool.h 　　　 再送信待ちデータの保管機能実装のヘッダ・ファイル
　- ieee1888_datapool.c 　　　 再送信待ちデータの保管機能実装
　- Makefile 　　　　　　　　　makeビルド実行設定ファイル
　- readme.txt 　　　　　　　　このファイル

２．ビルド方法
　Linux環境にて、
　$ make
　を実行します。

　以上で、
　　ieee1888_sample_app および ieee1888_sample_gw 
　の実行ファイルが生成されます。
　これでビルドは完了です。

３．改良の方法
　ieee1888_sample_gw.c は、FETCH, WRITEサーバのサンプルとなっています。
　ieee1888_sample_app.c は、FETCH, WRITEクライアントのサンプルとなっています。
　これらをひな型にし、新たなアプリケーション(GW, APP等)を実装することができます。

　それ以外のファイルは、ライブラリとして使用するので、編集する必要はありません。
　詳細は、「IEEE1888プロトコル教科書 (インプレスジャパン社)」を参照してください。


４．免責事項
　本ソフトウェアを使用して生じた事象については、
　作者およびその関連組織は責任を負いかねますので、
　予めご了承ください。

５．ライセンス
　BSDライセンスです。
　個人・商用を問わず、ご利用いただけます。

６．更新内容
　・ v201310版 <- v201212版の更新
　　- IPv6 only環境でも動作するように改良 (special thanks to Hiroyuki Ikegami)
　　- ieee1888_client と ieee1888_serverでの接続失敗問題を解消 (special thanks to Motomasa Tanaka (motomasa-tanaka@mayekawa.co.jp))
　　- ソースコードにライセンス文を追加
　　- ieee1888_server gccビルド時に出た warning: format not a literal ... を解消

７．バグの報告
　落合秀也 ochiai@vdec.u-tokyo.ac.jp へご連絡ください。