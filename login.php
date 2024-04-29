<?php
require 'vendor/autoload.php';
use Google\Cloud\BigQuery\BigQueryClient;

// Inicializando sessão e tratando login
session_start();
if (isset($_POST["us"]) && isset($_POST["pw"])) {
    $ppw = md5($_POST["pw"]);
    $pus = $_POST["us"];
    include("conn.php");
    $sql = mysqli_query($conn, "SELECT * FROM login WHERE us='$pus' AND pw='$ppw'");
    if (mysqli_num_rows($sql) == 1) {
        $li = mysqli_fetch_array($sql);
        $_SESSION["integrador"] = md5($li["id"]);
        $_SESSION["no"] = $li["no"];
        $_SESSION["link"] = 0;
        echo '<script>location.href="index.php";</script>';
    }
}

// Inicializando o cliente BigQuery
$bigQuery = new BigQueryClient(['projectId' => 'desafio-dataproc-324610']);

// Realizando a consulta ao BigQuery
$query = 'SELECT * FROM `desafio-dataproc-324610.projetointegrador.consumo` LIMIT 10';
$queryJobConfig = $bigQuery->query($query);
$queryResults = $bigQuery->runQuery($queryJobConfig);

$tableHTML = '<table border="1"><tr><th>ID</th><th>Mês de Referência</th><th>Data da Leitura Anterior</th><th>Data da Leitura Atual</th><th>Produção (KW/h)</th><th>Consumo (KW/h)</th><th>Energia Injetada</th><th>Energia Excedente</th></tr>';
if ($queryResults->isComplete()) {
    $rows = $queryResults->rows();
    foreach ($rows as $row) {
        $tableHTML .= sprintf(
            '<tr><td>%d</td><td>%s</td><td>%s</td><td>%s</td><td>%d</td><td>%d</td><td>%d</td><td>%d</td></tr>',
            $row['id'],
            $row['me'],
            $row['dl']->format('Y-m-d'),
            $row['da']->format('Y-m-d'),
            $row['pr'],
            $row['co'],
            $row['em'],
            $row['ex']
        );
    }
    $tableHTML .= '</table>';
} else {
    $tableHTML = '<p>Query Failed</p>';
}

// Montando o corpo da página
$body = '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
        <link rel="icon" type="image/png" href="img/iot.png" />
        <title>Projeto Integrador III</title>
    </head>
    <body>
        <script>
            function envialog() {
                document.FenviaLog.submit();
            }
            function login() {
                location.href="index.php";
            }
        </script>
        <div class="dcenter" style="border:none; width:20%; margin-top:30px; margin-bottom:10px; height:160px;">
            <img id="link" class="img_clinical" src="img/iot.png" onclick="login()">
        </div>
        <form name="FenviaLog" action="login.php" method="post">
            <div class="dcenter">
                <p class="p" style="margin-top:55px; color:blue;"><b>login:</b></p>
                <input style="margin-top:50px;" name="us">
                <p class="p" style="margin-top:95px; color:blue;"><b>password:</b></p>
                <input style="margin-top:90px;" type="password" name="pw">
                <button class="btn" style="left:40%; margin-top:130px; width:200px; height:40px;" onclick="envialog()">ACESSAR</button>
                <p class="p" style="margin-top:185px; color:gray;"></p>
            </div>
        </form>
        <div>' . $tableHTML . '</div>
    </body>
</html>';
echo $body;
?>
