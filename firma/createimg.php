<HTML>
<HEAD>

<?php
$hex = hex2bin("$_REQUEST[SigField]");
//echo $hex;
$File = 'test.bmp'; 
$Handle = fopen($File, 'w'); 
fwrite($Handle, $hex); 
fclose($Handle); 
?>

</HEAD>
<BODY>

Image written to working directory: test.bmp

</BODY>
</HTML>