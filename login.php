<?php
session_start();
if(isset($_POST["sair"])){    
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
	     <script>
        function sair(){
            if(confirm("DESEJA REALMENTE SAIR?")){
                document.fsair.submit();
					} else {
		alert("OPERAÇÃO CANCELADA DO SUCESSO!");
					}
                }
        </script>
        </head>
        <body>
        ';


    if(isset($_POST["us"]) and isset($_POST["pw"])){	
        $ppw=md5($_POST["pw"]); $pus=md5($_POST["us"]);
        if($ppw=='85fb2908ca1cb55c67c4d57eb6e0e46f' and $pus=='85fb2908ca1cb55c67c4d57eb6e0e46f'){
		$_SESSION["integrador"]=md5($li["id"]);
                $_SESSION["no"]=$li["no"];
                $_SESSION["link"]=0;
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

if(isset($_SESSION["integrador"])){
	$body.='
        <div class="div" style="margin-top:10px; margin-bottom:10px; border:none; border-bottom:2px solid brown; border-top:2px solid brown;">
            <p class="p">Seja bem vinda(o) <b>Projeto INtegrador III</b> ('.$ip.')</p>
	    <form name="fsair" method="post" action="login.php">
     	    <input type="hidden" name="sair" value="0">
            <img class="search" src="img/exit.png" onclick="sair()" title="SAIR">
        </div></form>
	';
                

            //inicio da consulta ao BD
            $bigQuery = new BigQueryClient([
        'projectId' => 'desafio-dataproc-324610'
    ]);

    // Faça uma consulta ao BigQuery
    $query = 'SELECT pr,co FROM `desafio-dataproc-324610.projetointegrador.consumo` LIMIT 10';
    $queryJobConfig = $bigQuery->query($query);
    $queryResults = $bigQuery->runQuery($queryJobConfig);

$body.='
        <div class="div" style="height:40px; text-align:center; border:none; margin-top:10px; margin-bottom:20px">
            <p class="p" style="width:98%; font-size:30px; font-weight:bolder; color:blue;">simulador de cálculo - energia solar</p>
        </div>
        <fieldset style="padding:30px">
        <script>
            function envia(){
                    document.fenvia.submit();
                }
        </script>
        <form name="fenvia" action="index.php" method="post">
        <div class="div" style="border:none;">
            <p class="p">investimento inicial (R$) </p>
            <input type="number" name="invest" class="input" value="0" style="margin-top:0; left:150px;">
        </div>
        <div class="div" style="border:none">
            <p class="p">valor kw/h (R$) </p>
            <input type="number" name="valor" class="input" value="0" style="margin-top:0; left:150px;">
        </div>
        <div class="div" style="border:none">
            <p class="p">capacidade total de produção kw/h </p>
            <input type="number" name="capaci" class="input" value="0" style="margin-top:0; left:150px;" disabled>
        </div>
        <div class="div" style="border:none">
            <button class="btn" style="margin-top:0" onclick="envia()">CALCULAR</button>
        </div>
        <form>
        </fieldset>
        <div class="div" style="margin-top:20px; height:30px; border:none">
            <p class="p" style="width:98%; font-size:24px; font-weight:bolder; color:blue;">
                banco de dados
            </p>
        </div>
        <fieldset>
        <style>
            .left{
                    position:relative;
                    float:left;
                    top:0;
                    margin-top:1px;
                    margin-right:2px;
                    border:1px solid brown;
                    height:22px;
                    width:15%;
                    padding-top:3px;
                    text-align:center;
                }
        </style>
    ';

    if(isset($_POST["invest"])){
        $invest=$_POST["invest"];
        $valor_=$_POST["valor"];
    } else {
        $invest=0.000000000000000000000000000000000000000000000000000000000000000000000000000000000001;
        $valor_=0.000000000000000000000000000000000000000000000000000000000000000000000000000000000001;
    }
$consumo=0; $producao=0;    $valor=0;   $i=0;



		
    $r = '<table width="100%" align="center">';
    $results = '';
    $i=0;
		
	foreach ($queryResults as $row) {
		$i++;
        $consumo=$consumo+$row["co"];    $producao=$producao+$row["pr"];  $valor=($producao*$valor_); 
        $body.='<div class="div" style="border:none">
            <div class="left">
                ref. '.date("m/Y",strtotime($row["me"])).'</div>
            <div class="left">produção '.$row["pr"].' kw/h</div>
            <div class="left">acumulado '.$producao.' kw/h</div>
            <div class="left">consumo '.$row["co"].' kw/h</div>                        
            <div class="left">acumulado '.$consumo.' kw/h</div>
            <div class="left">acumulado R$ '.number_format($valor,2,',','.').'</div>
        </div>';
	}

$body.='
        </fieldset>
    ';
    $m=$valor/$i; 
    $i=$invest/$m;
    $h=number_format($i,0);          
    if($i>$h and $i>1){$h++;}
    $a = number_format($h/12,0);
    $r = $h%12;
    switch($a){
        case 0: $ano='ano'; break;
        case 1: $ano='ano'; break;
        default: $ano='anos'; break;
    }
    switch($r){
        case 0: $mes='mês'; break;
        case 1: $mes='mês'; break;
        default: $mes='meses'; break;
    }
    $body.='
    <div class="div" style="background-color:#affdd4;">
        <p class="p" style="color:green"><b>O retorno do investimento inicial ocorrerá em <i style="color:red;">'.$a.' '.$ano.' e '.$r.' '.$mes.'</i></b></p>
    </div>
    ';

}
		
            //termino da consulta ao BD
            
            

 

       $body.='
        </body>
    </html>
    '; 
    echo $body;
?>
