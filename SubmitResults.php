<?php
session_start ();
@要求 'conn.php' ;
$ encryptString = file_get_contents ( "php://input" );
$解密= ''；
$键       =“MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBANPMbBfoVUpzusOLIXcf6MqkGVEJXiM6InglHfepk9VfHxqFbput0EX0fW90cEDI7oB5gG6YojK6dc / 3HO + zWol1E2E2hXAcLAYO7tMD5Tgzsb0UCsMbRjqTgttLQqz3N5EEyJaRbnfJCU + yGG07FcK5lk4wuqTW8S9MI4NhipflAgMBAAECgYEAl4bN4sDWnGB1wsZ8V8SdgLSsZBymm99Qn9I2QWSyHlpiX1ANFRXiRtonD6EnWkIm2AWVTAqpKE / cT8AElL0lTJpZdUxsb7Y6nZvbFEmkpFA183f9pzkFjBAxW21RQJMW5MzSnUhYXVZr7AgUaxMDy7M2RMFZ / 5XbwKwuNGaT5qECQQD5jCvnlpVmq5tTmIGRy + o18WtQdZRvEvkRAhRw8qAowZtBhCO + ycMtQKCwVDya8aDUItzrIBrzGv2eOfBndZqpAkEA2UZg / nGwpcDd7EVU3XltU5t3cX3wLhUZp1bDv3OZql44h0V2p + p1Oa2qVrF2JmbTu1gWn2YsOFlktrbogKP03QJBAIrXaUoVpxQToH0XWeeza6ENrCZ89NQD212SKatZ4rAqX + ZIzdaFzTjtPzo78 + hFTbUZnI6ZM0VVHAyfsdjuPtkCQDAJED6QsgYjOq0Wsul4BASc9W5A8o2tmotVcldsXke9JvA5Gj + LZTlIPMWH3GAnEZ50niPFefdHRC3lCEgQd30CQQDbEqFoSCM4sEHih9h8b3V88X7X / sAbWk + rDnGy6TITplPZrLsBWu3D14VMpiCcNQ1ms6RKZxUFwNZXYynQNrhp”;
$ key_eol    = ( string )内爆( "\n" , str_split (( string ) $ key , 64 ));
$ privateKey = ( string ) "-----BEGIN PRIVATE KEY-----\n"。$ key_eol。"\n-----END 私钥-----" ;
@openssl_private_decrypt ( base64_decode ( $ encryptString ), $ decrypted , $ privateKey ) ;
$ arr = explode ( '|_|' , $解密);

$ str = "/\ |\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+| \{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\ \|\|/" ;
$ score = preg_replace ( $ str , "" , $ arr [ 0 ]);
$名称= preg_replace ( $ str , "" , $ arr [ 1 ]);
$ t = preg_replace ( $ str , "" , $ arr [ 2 ]);
$ system = preg_replace ( $ str , "" , $ arr [ 3 ]);
$ area = preg_replace ( $ str , "" , $ arr [ 4 ]);
$ message = preg_replace ( $ str , "" , $ arr [ 5 ]);

if ((! empty ( $ name )) &&( strlen ( $ name )<= 30 )&&( strlen ( $ system )<= 30 )&&( strlen ( $ area )<= 30 )&&( strlen ( $ message ) <= 150 ) && ( is_numeric ( $ score )) && ( $ score < 300 ) &&( $ t == $ _SESSION ['t' ])) {
    $ score_sql = "SELECT score,attempts FROM " . $排名。“名字在哪里=？” ;
    $ score_stmt = $链接->准备（ $ score_sql）；
    $ score_stmt -> bind_param ( "s" , $ name );
    $ score_stmt -> bind_result（ $最高， $尝试）；
    $ score_stmt ->执行（）；
    $ data = $ score_stmt -> fetch ();
    $ score_stmt ->关闭（）；
    如果（！空（$数据））{
        $尝试+= 1 ;
        如果（$分数> $最高）{
            $ update_sql = "更新"。$排名。" SET score=?,time=NOW(),system=?,area=?,message=?,attempts=?WHERE name=?” ;
            $ update_stmt = $链接->准备（ $ update_sql）；
            $ update_stmt -> bind_param ( 'isssis' , $ score , $ system , $ area , $ message , $ attempt , $ name );
            $ update_stmt ->执行（）；
            $ update_stmt ->关闭（）；
        }其他{
            $ count_sql = "更新"。$排名。“ SET 尝试=？WHERE 名称=？” ;
            $ count_stmt = $链接->准备（ $ count_sql）；
            $ count_stmt -> bind_param ( 'is' , $尝试, $ name );
            $ count_stmt ->执行（）；
            $ count_stmt ->关闭（）；
        }
    }其他{
        $尝试= 1 ;
        $ insert_sql = "插入"。$排名。“（分数，时间，系统，区域，消息，尝试，名称）值（？，现在（），？，？，？，？，？）” ;
        $ insert_stmt = $链接->准备（ $ insert_sql）；
        $ insert_stmt -> bind_param ( 'isssis' , $ score , $ system , $ area , $ message , $ attempt , $ name );
        $ insert_stmt ->执行（）；
        $ insert_stmt ->关闭（）；
    }
    $链接->关闭（）；
}