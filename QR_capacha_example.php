<!DOCTYPE html>
<?php
    session_start();
//    if(isset($_SESSION['captchId'])){
//        echo $_SESSION['captchId']; // session have capatcha code
//    }
    $language = 'en'; //language en for english or ar arabic
    $length = 4; //the length of characters

    $captcha = "source/captcha.php?lng=$language&len=$length";
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <div id="captchaShow">
            <img src='<?php echo $captcha; ?>'>
        </div>
        <button onclick="loadCaptch()">Change Captcha</button><br><br><br>
        <textarea id="qrData" style="width: 350px; height: 150px">Name: Khaled Hassan 
Tel: +2 01200000000</textarea><br>
        <button onclick="getQRcode()">Get QR Code</button>
        <div id="qrShow"></div>
    </body>
</html>
<script>
function loadCaptch(){
    var random = Math.random(1000);
    var img = document.createElement("img");
    img.setAttribute('src', '<?php echo $captcha; ?>&r' + random);
    var parent = document.getElementById("captchaShow");

    parent.innerHTML = '';
    parent.appendChild(img);
}
function getQRcode(){
    var img = document.createElement("img");
    img.setAttribute('src', encodeURI('source/qrCode.php?data=' + document.getElementById('qrData').value));
    var parent = document.getElementById("qrShow");
    parent.innerHTML='';
    parent.appendChild(img);
}
</script>