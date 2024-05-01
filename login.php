<?php
if(isset($_POST["sair"])){
    session_start();
    session_unset();
    session_destroy();
    echo '
    <script>
        location.href="login.php";
    </script>
    ';
}
require 'vendor/autoload.php';
use Google\Cloud\BigQuery\BigQueryClient;
$ip = $_SERVER["REMOTE_ADDR"];
header('Content-Type: text/html; charset=utf-8');
$body='<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="style.css">
            <link rel="icon" type="image/png" href="img/iot.png" />
            <title>PROJETO INTEGRADOR</title>
            <style>
                table, th, td {
                  border: 1px dashed red;
                  border-radius: 10px;
                }
            </style>
        </head>
        <body>
        ';


    if(isset($_POST["us"]) and isset($_POST["pw"])){	
        $ppw=md5($_POST["pw"]); $pus=md5($_POST["us"]);
        if($ppw=='85fb2908ca1cb55c67c4d57eb6e0e46f' and $pus=='85fb2908ca1cb55c67c4d57eb6e0e46f'){
	$body.='
 	<script>
        function sair(){
            if(confirm("DESEJA REALMENTE SAIR?")){
                document.fsair.submit();
					} else {
		alert("OPERAÇÃO CANCELADA DO SUCESSO!");
					}
                }
        </script>
        <div class="div" style="margin-top:10px; margin-bottom:10px; border:none; border-bottom:2px solid brown; border-top:2px solid brown;">
            <p class="p">Seja bem vinda(o) <b>Projeto INtegrador III</b> ('.$ip.')</p>
	    <form name="fsair" method="post" action="login.php">
     	    <input type="hidden" name="sair" value="0">
            <img class="search" src="img/exit.png" onclick="sair()">
        </div></form>
	';
                session_start();
                $_SESSION["integrador"]=md5($li["id"]);
                $_SESSION["no"]=$li["no"];
                $_SESSION["link"]=0;

            //inicio da consulta ao BD
            $bigQuery = new BigQueryClient([
        'projectId' => 'desafio-dataproc-324610'
    ]);

    // Faça uma consulta ao BigQuery
    $query = 'SELECT pr,co FROM `desafio-dataproc-324610.projetointegrador.consumo` LIMIT 10';
    $queryJobConfig = $bigQuery->query($query);
    $queryResults = $bigQuery->runQuery($queryJobConfig);
    $r = '<table width="100%" align="center">';
    $results = '';
    $i=0;
		
	foreach ($queryResults as $row) {
		$r .= '<tr><td><b style="color:red">'.$i++.'Produção </b>==>'.$row["pr"].'</td><td><b style="color:red">'.$i++.'Consumo </b>==>'.$row["co"].'</td></tr>';
	}

    $r.='</table>';
    $body.='<div>'.$r.'</div>';
            //termino da consulta ao BD
            
            }            
        } else {


    $body.='
        <script>
            function envialog(){
                document.FenviaLog.submit();
            }
            function login(){
                location.href="index.php";
            }
        </script>
        <div class="dcenter" style="border:none; width:20%; margin-top:30px; margin-bottom:10px; height:160px;">
            <img id="link" class="img_clinical" src="img/iot.png" onclick="login()">
        </div>
        <form name="FenviaLog" action="login.php" method="post">
        <div class="dcenter">
            <p class="p" style="margin-top:55px; color:blue;"><b>login:<b></p>
            <input style="margin-top:50px;" name="us">
            <p class="p" style="margin-top:95px; color:blue;"><b>password:<b></p>
            <input style="margin-top:90px;;" type="password" name="pw">
            <button class="btn" style="left:40%; margin-top:130px; width:200px; height:40px; " onclick="envialog()">ACESSAR</button>
            <p class="p" style="margin-top:185px; color:gray;">'.$message.'</p>
        </div>
        </form>        
    ';

    }

 

       $body.='
        </body>
    </html>
    '; 
    echo $body;
?>
