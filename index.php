<?php
session_start();
$ip = $_SERVER["REMOTE_ADDR"];
header('Content-Type: text/html; charset=utf-8');
if (isset($_SESSION["integrador"])){
    $home='
 <!DOCTYPE html>
	<html>
		<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="style.css">
		<link rel="icon" type="image/png" href="img/iot.png" />
		<title>PROJETO INTEGRADOR III</title>
		</head>
	<body> 
        <script>
        function sair(){
            if(confirm("DESEJA REALMENTE SAIR?")){
                location.href="sair.php";
					} else {
						alert("OPERAÇÃO CANCELADA DO SUCESSO!");
					}
                }
        </script>
        <div class="div" style="margin-top:10px; margin-bottom:10px; border:none; border-bottom:2px solid brown; border-top:2px solid brown;">
            <p class="p">Seja bem vinda(o) <b>Projeto INtegrador III</b> ('.$ip.')</p>
            <img class="search" src="img/exit.png" onclick="sair()">
        </div>  
    ';
    switch($_SESSION["link"]){
        case 0: include "home.php";
    }
    $home.='
    </body>
    </html>
    ';
    echo $home;
} else {
    echo '
    <script>
        location.href="login.php";
    </script>
    ';

}
  
?>