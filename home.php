<?php
    $_SESSION["link"]=0;
    $home.='
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

    include "conn.php";
    $sql=mysqli_query($conn,"select * from consumo order by me");
    $consumo=0; $producao=0;    $valor=0;   $i=0;
    while($li=mysqli_fetch_array($sql)){
        $i++;
        $consumo=$consumo+$li["co"];    $producao=$producao+$li["pr"];  $valor=($producao*$valor_); 
        $home.='<div class="div" style="border:none">
            <div class="left">
                ref. '.date("m/Y",strtotime($li["me"])).'</div>
            <div class="left">produção '.$li["pr"].' kw/h</div>
            <div class="left">acumulado '.$producao.' kw/h</div>
            <div class="left">consumo '.$li["co"].' kw/h</div>                        
            <div class="left">acumulado '.$consumo.' kw/h</div>
            <div class="left">acumulado R$ '.number_format($valor,2,',','.').'</div>
        </div>';
        
    }
    
    $home.='
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
    $home.='
    <div class="div" style="background-color:#affdd4;">
        <p class="p" style="color:green"><b>O retorno do investimento inicial ocorrerá em <i style="color:red;">'.$a.' '.$ano.' e '.$r.' '.$mes.'</i></b></p>
    </div>
    ';
?>