<?php
	//importando os namespaces
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;


	/**
	 * Classe de mensagens
	 */
	class Mensagens {
		private $para;
		private $assunto;
		private $mensagem;
		public $status;

	    public function __construct($para, $assunto, $mensagem) {
	    	$this->para = $para;
			$this->assunto = $assunto;
			$this->mensagem = $mensagem;
			$status = ['codigo_status' => null, 'descricao_status' => ''];
	    }

	    public function __get($att) {
	    	return $this->$att;
	    }

	    public function __set($att, $val) {
	    	$this->$att = $val;
	    }

	    public function mensagemValida() {
	    	if(empty($this->para) || empty($this->assunto) || empty($this->mensagem))
	    		return false;
	    	return true;
	    }
	}

	//instânciando um obj Mensagens
	$mensagem = new Mensagens($_POST['para'], $_POST['assunto'], $_POST['mensagem']);

	//validando os dados
	if(!$mensagem->mensagemValida()) {
		echo 'Mansagem inválida !!!';
		header('location: index.php');
	}

	$mail = new PHPMailer(true);
	try {
	    //Server settings
	    $mail->SMTPDebug = false;                                 // Enable verbose debug output
	    $mail->isSMTP();                                      // Set mailer to use SMTP
	    $mail->Host = 'smtp.gmail.com';  					  // Specify main and backup SMTP servers
	    $mail->SMTPAuth = true;                               // Enable SMTP authentication
	    $mail->Username = 'teste@teste';          // SMTP username
	    $mail->Password = 'senha123';                      // SMTP password
	    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	    $mail->Port = 587;                                    // TCP port to connect to

	    //Recipients
	    $mail->setFrom('viniciussouzav@gmail.com', 'GODnicius');
	    $mail->addAddress($mensagem->__get('para'));     // Add a recipient
	    //$mail->addAddress('ellen@example.com');               				   // Name is optional
	   // $mail->addReplyTo('info@example.com', 'Information'); 				   //contato pradão (em caso de resposta)
	    //$mail->addCC('cc@example.com'); 										   //add em cópia
	   // $mail->addBCC('bcc@example.com');      								   //cópia oculta

	    //Attachments
	    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

	    //Content
	    $mail->isHTML(true);                                  // Set email format to HTML
	    $mail->Subject = $mensagem->__get('assunto');
	    $mail->Body    = $mensagem->__get('mensagem');
	    $mail->AltBody = 'Utilizar um cliente que leia HTML !!!';

	    $mail->send();
	    $mensagem->status['codigo_status'] = 1;
	    $mensagem->status['descricao_status'] = 'Mensagem enviada com sucesso !';
	    
	} catch (Exception $e) {
		$mensagem->status['codigo_status'] = 0;
	    $mensagem->status['descricao_status'] = 'Não foi possível enviar o e-mail, tente mais tarde !!! Erro: ' . $mail->ErrorInfo;

	    //armazenar o erro, para poder analisa-lo mais tarde !
	}		  
?>

<html>
	<head>
		<meta charset="utf-8" />
		<title>App Mail Send</title>

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body>

		<div class="container">
			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

			<div class="row">
				<div class="col-md-12">
					<? if($mensagem->status['codigo_status'] == 1) {?>
						<div class="container">
							<h1 class="display-4 text-success">Sucesso</h1>
							<p><?= $mensagem->status['descricao_status']; ?></p>
							<a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
						</div>
					<?} else { ?>
						<div class="container">
							<h1 class="display-4 text-danger">Ops !</h1>
							<p><?= $mensagem->status['descricao_status']; ?></p>
							<a href="index.php" class="btn btn-danger btn-lg mt-5 text-white">Voltar</a>
						</div>
					<?}?>
				</div>
			</div>
			
		</div>

	</body>
</html>