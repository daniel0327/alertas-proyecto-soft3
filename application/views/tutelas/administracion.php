<?php
require("phpmailer/class.phpmailer.php");
require("phpmailer/class.smtp.php");
  
  
  //DB configuration Constants
  define('_HOST_NAME_', 'localhost');
  define('_USER_NAME_', 'root');
  define('_DB_PASSWORD', '');
  define('_DATABASE_NAME_', 'alarma');
  
  
  //PDO Database Connection
  try {
    $databaseConnection = new PDO('mysql:host='._HOST_NAME_.';dbname='._DATABASE_NAME_, _USER_NAME_, _DB_PASSWORD);
    $databaseConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    echo 'ERROR: ' . $e->getMessage();
  }
  
  if(isset($_POST['codigoCorreo']) && !empty($_POST['codigoCorreo']))
  {
    $radicadoInterno = trim($_POST['codigoCorreo']);

    $records = $databaseConnection->prepare('SELECT * FROM tutela WHERE Radicado_Interno = :radicadoInterno');
    $records->bindParam(':radicadoInterno', $radicadoInterno);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);
    


    $mail = new PHPMailer();
    $mail->CharSet = "utf-8";
    //indico a la clase que use SMTP
    $mail->IsSMTP();
    //permite modo debug para ver mensajes de las cosas que van ocurriendo
    $mail->SMTPDebug =0;
    //Debo de hacer autenticación SMTP
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "ssl";
    //indico el servidor de Gmail para SMTP
    $mail->Host = "smtp.gmail.com";
    //indico el puerto que usa Gmail
    $mail->Port = 465;
    //indico un usuario / clave de un usuario de gmail
    $mail->Username = "sistealarmasuq@gmail.com";
    $mail->Password = "sistealarmas2016";
    $mail->SetFrom('sistealarmasuq@gmail.com', 'Sistealarmas Uniquindío');
    $mail->AddReplyTo("sistealarmasuq@gmail.com","sistealarmas UQ");
    $mail->Subject = "Notificación de derechos de petición y tutelas";
    $mail->MsgHTML("Buenas, el presente correo es para notificarle que usted tiene asignada una tutela que vence el "
      .$results['Fecha_Vencimiento']." solicitida por el señor(a) ".$results['Solicitante']."<br>Att: Ruth Franco. Secretaria de Rectoría");
    //indico destinatario
    $address = $results['Correo_Responsable'];
    $mail->AddAddress($address, $results['Responsable']);
    if(!$mail->Send()) {
      echo"<script>alert('Error al enviar: Revise el codigo de radicado interno ingresado y su conexíon a internet')</script>";
    } else {
    echo"<script>alert('Notificación enviada')</script>";
    }
 
  }

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Tutelas</title>
<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<style type='text/css'>
body
{
  font-family: Arial;
  font-size: 14px;
  margin: 8px;
  padding: 10px;
}
a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}
a:hover
{
  text-decoration: underline;
}

header
{
  background: #20912D;
  -webkit-border-radius: 40px;
}
footer
{
  background: #20912D;
  -webkit-border-radius: 40px;
  height: 90px;
}

p
{
  font-size: 25px;
  padding-top: 32px;
}
#logo{
      display: block;
        margin: 15px auto;
        height: 165px;
        padding-top: 10px;
        padding-bottom: 10px;
      }
</style>
<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.css" />
</head>
<body>

<header>
  <img src="http://localhost/alertas-proyecto-soft3/assets/images/uniquindio.PNG" id="logo">
</header>

	<h1>Administración de Tutelas</h1>
    <div>
	
		<?php echo $output; ?>
		 
		 
		 
		 
		 <style type="text/css">
  .boton{
        font-size:10px;
        font-family:Verdana,Helvetica;
        font-weight:bold;
        color:white;
        background:black;
        border:0px;
        width:130px;
        height:25px;
       }


</style>
<div align="">
  <form name="form1" action="http://localhost/alertas-proyecto-soft3/tecnico"  >
    <input type="submit" value="MENU PRINCIPAL" class="boton">
  </form>
</div>
    <h3>Enviar Notificación a un responsable</h3>
    <div class="correo">
      <form action="" method="post">      
        <input type="text" placeholder="radicado interno" name="codigoCorreo">
        <input type="submit" name='submit' value="Notificar" class='boton'>
      </form>
    </div>
    </div>
    <footer>
      <p align="center"> Pertinente Creativa Integradora</p>
    </footer>     
</body>
</html>
