<?php
    require 'vendor/autoload.php';
    use Google\Cloud\BigQuery\BigQueryClient;

    if(isset($_POST["us"]) and isset($_POST["pw"])){
        $ppw=md5($_POST["pw"]); $pus=md5($_POST["us"]);
        if($ppw=='85fb2908ca1cb55c67c4d57eb6e0e46f' and $pus=='85fb2908ca1cb55c67c4d57eb6e0e46f';){
                session_start();
                $_SESSION["integrador"]=md5($li["id"]);
                $_SESSION["no"]=$li["no"];
                $_SESSION["link"]=0;
            }
            echo '<script>location.href="index.php"</script>';
        } 
    }

    // Inicialize o cliente BigQuery
    $bigQuery = new BigQueryClient([
        'projectId' => 'desafio-dataproc-324610'
    ]);

    // Faça uma consulta ao BigQuery
    $query = 'SELECT * FROM `desafio-dataproc-324610.projetointegrador.consumo` LIMIT 10';
    $queryJobConfig = $bigQuery->query($query);
    $queryResults = $bigQuery->runQuery($queryJobConfig);

    $results = '';
    if ($queryResults->isComplete()) {
        $rows = $queryResults->rows();
        foreach ($rows as $row) {
            $results .= print_r($row, true) . "<br>";
        }
    } else {
        $results = 'Query Failed';
    }

    $body='<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="style.css">
            <link rel="icon" type="image/png" href="img/iot.png" />
            <title>CLOUD BUILD</title>
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
        </div>
        </form>
        <div>'.$results.'</div>
    ';
    $body.='
        </body>
    </html>
    ';
    echo $body;
?>
