<?php
	error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
    $hostname_conn = "localhost";
    $database_conn = "quran_uthmani";
    $username_conn = "root";
    $password_conn = "";
    $conn = mysql_connect($hostname_conn, $username_conn, $password_conn) or die("");
    mysql_select_db($database_conn, $conn);
    mysql_query("SET time_zone = 'Asia/Kuala_Lumpur'");
    mysql_query("SET NAMES utf8");

    $starttime = microtime(true);
    
    for($page=1; $page<605; $page++) { //max 605
        $sql = " SELECT *, MIN(aya) minaya, MAX(aya) maxaaya FROM quran_text WHERE page = $page ";
        $rs = mysql_query($sql, $conn) or die(mysql_error());
        
        $rsNextPage = mysql_fetch_assoc($rs);
        $header = '
            <!DOCTYPE HTML>
            <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <title>Sura '.$rsNextPage['sura_name'].', Aya '.$rsNextPage['minaya'].'-'.$rsNextPage['maxaaya'].'</title>
                    <link href="../css/style.css" rel="stylesheet">
                </head>
                <body>
                    <div class="container">
                        <div class="sura">'.$rsNextPage['sura_name_arabic'].'</div>
                        XXXXX
                    </div>
                </body>
            </html>
        ';
        
        $sql = " SELECT * FROM quran_text WHERE page = $page ";
        $rs = mysql_query($sql, $conn) or die(mysql_error());
        $content = '';
        while($rsNextAya = mysql_fetch_assoc($rs)) {
            $content .= '<span class="aya">'.$rsNextAya['text'].'</span><span class="number">'.arabicNumber($rsNextAya['aya']).'</span>';
        }
        
        $myfile = fopen('../sura/Sura '.$rsNextPage['sura_name'].', Aya '.$rsNextPage['minaya'].'-'.$rsNextPage['maxaaya'].'.html', 'w') or die("Unable to open file!");
        fwrite($myfile, str_replace("XXXXX", $content, $header));
        fclose($myfile);
    }
    
    function arabicNumber($i) {
        $eastern_arabic = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');
        $s = str_split($i);
        $e = "";
        for($a=0; $a<count($s); $a++) {
            $e .= $eastern_arabic[(int)$s[$a]];
        }
        return ' ﴿'.$e.'﴾&nbsp; ';
    }
    
    $endtime = microtime(true);
	echo "Completed in " . ($endtime - $starttime) . " seconds";
?>