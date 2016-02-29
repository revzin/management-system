<html>
<head>
<title> DA </title>
</head>
<body>

<?php

LogToApache($Message) {
        $stderr = fopen('php://stdout', 'w'); 
        fwrite($stderr,$Message); 
        fclose($stderr); 
}

error_reporting( E_ALL );

LogToApache("govno");

?>

ss

</body>
</html>