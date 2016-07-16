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
    $tipueObj = '';
    
    for($page=1; $page<605; $page++) { //max 605
        $sql = "
            SELECT CONCAT(
                '{',
                    '\"title\": \"Sura ', sura_name, ', Aya <span id=\\\\\"match\\\\\"><\/span>\", ',
                    '\"url\": path + \"sura/Sura ', sura_name, ', Aya ', MIN(aya), '-', MAX(aya), '.html\", ',
                    '\"text\": \"XXXXX\", ',
                    '\"tags\": \"\"',
                '} '
            ) json FROM quran_text WHERE page = $page
        ";
        $rs = mysql_query($sql, $conn) or die(mysql_error());
        $rsNextPage = mysql_fetch_assoc($rs);
        
        $sql = " SELECT * FROM quran_text WHERE page = $page ";
        $rs = mysql_query($sql, $conn) or die(mysql_error());
        $content = '';
        while($rsNextAya = mysql_fetch_assoc($rs)) {
            $content .= $rsNextAya['text'] . '<aya>'.$rsNextAya['aya'].'</aya>' . arabicNumber($rsNextAya['aya']);
        }
        
        $tipueObj .="\n\t" . str_replace("XXXXX", $content, $rsNextPage['json']) . ",";
    }
    
    $myfile = fopen('../lib/tipuesearch/tipuesearch_content.js', 'w') or die("Unable to open file!");
    $tipueObj = "var path = window.location.href.substr(0, location.href.lastIndexOf(\"/\") + 1);\nvar tipuesearch = {\"pages\": [".rtrim($tipueObj, ",")."\n]};";
    fwrite($myfile, $tipueObj);
    fclose($myfile);
    
    function arabicNumber($i) {
        $eastern_arabic = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');
        $s = str_split($i);
        $e = "";
        for($a=0; $a<count($s); $a++) {
            $e .= $eastern_arabic[(int)$s[$a]];
        }
        return '&nbsp;﴿'.$e.'﴾&nbsp;';
    }
    
    $endtime = microtime(true);
	echo "Completed in " . ($endtime - $starttime) . " seconds";
?>