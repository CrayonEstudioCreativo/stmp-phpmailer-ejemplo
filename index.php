<?php
include_once __DIR__ . '/src/LibreriaMailer.php';

if ($_POST) {
    $mailer = new LibreriaMailer(__DIR__ . "/mail_configuration.json");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ejemplo minimo de envío de mensajes con SwiftMailer</title>
    <style>
        body {
            padding: 25px 0;
        }

        button[type=submit] {
            margin-top: 15px;
        }
    </style>
    <link rel="stylesheet"
          href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="jumbotron">
        <h1>Ejemplo mínimo de envío con <strong>PHPMailer</strong></h1>
        <p>
            Para ver documentación visita
            <a href="https://github.com/PHPMailer/PHPMailer">PHPMailer</a>
        </p>
    </div>

    <?php if (isset($mailer)): ?>
        <?php if ($mailer->send()): ?>
            <div class="alert alert-success">Correo enviado</div>
        <?php else: ?>
            <div class="alert alert-danger">Falló al enviar correo.
                <?= $mailer->getError() ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <form method="post">
                <div class="form-group">
                    <input name="email" type="email" class="form-control"
                           placeholder="E-mail">
                </div>
                <textarea name="mensaje" class="form-control" rows="3"
                          placeholder="Mensaje"></textarea>
                <button type="submit" class="btn btn-default">Enviar</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
