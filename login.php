<?php

    if(isset($_POST["us"]) and isset($_POST["pw"])){
        $ppw=md5($_POST["pw"]); $pus=$_POST["us"];
        include("conn.php");
        $sql=mysqli_query($conn,"select * from login where us='$pus' and pw='$ppw'");
        if(mysqli_num_rows($sql)==1){
            while($li=mysqli_fetch_array($sql)){
                session_start();
                $_SESSION["integrador"]=md5($li["id"]);
                $_SESSION["no"]=$li["no"];
                $_SESSION["link"]=0;
            }
            echo 
            '<script>location.href="index.php"</script>';
        } 
    }

	$body='
	<!DOCTYPE html>
	<html>
		<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="style.css">
		<link rel="icon" type="image/png" href="img/iot.png" />
		<title>Projeto Intergrador III</title>
		</head>
		<body>';

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
			</form>
		</div>
    ';
    $body.='
        </body>
    </html>
    ';
    echo $body;
?>