<?php
// Cargamos el autoloader de PHPMailer.
include_once __DIR__ . '/../PHPMailer/PHPMailerAutoload.php';

/**
 * Class LibreriaMailer
 */
class LibreriaMailer
{
    /** @var PHPMailer */
    public $mailer = null;
    private $data = null;

    /**
     * LibreriaMailer constructor.
     *
     * @param string $path Ruta a archivo de configuración.
     * @param bool $debug
     *
     * @throws \Exception
     */
    public function __construct($path, $debug = false)
    {
        if (!file_exists($path)) {
            throw new Exception("Ruta de archivo de configuración no existe.");
        }

        // Leemos los parametros de configuración.
        $this->data = json_decode(file_get_contents($path));

        // Creamos un nuevo PHPMailer interno.
        $this->mailer = new PHPMailer();

        // Inicializamos el PHPMailer.
        $this->init();
    }

    /**
     * Metodo que inicializa los parametros del mailer.
     */
    protected function init()
    {
        $this->smtpConfig();
        $mail = $this->mailer;

        $var = $this->getFilteredPost();

        $this->setAsunto("El asunto fulano");
        $mail->addAddress($var['email']);
        $this->setMensaje($var['email'], $var['mensaje']);
        $mail->setFrom($this->data->smtp_data->Username);
    }

    /**
     * Settea el mensaje a mandar.
     *
     * @param $remitente
     * @param $mensaje
     */
    public function setMensaje($remitente, $mensaje)
    {
        $this->mailer->Body =
          "El remitente es {$remitente}, el mensaje es: {$mensaje}";
    }

    /**
     * Configuración de parametros de SMTP.
     */
    protected function smtpConfig()
    {
        $mail = $this->mailer;

        if (!isset($this->data->smtp_data)) {
            throw new Exception("No hay datos de configuración para PHPMailer.");
        }

        $data = $this->data->smtp_data;

        $mail->Host       = $data->Host;
        $mail->SMTPAuth   = $data->SMTPAuth;
        $mail->Username   = $data->Username;
        $mail->Password   = $data->Password;
        $mail->SMTPSecure = $data->SMTPSecure;
        $mail->Port       = $data->Port;

        if ($data->isSMTP) {
            $mail->isSMTP();
        }

        $mail->isHTML($data->isHTML);
    }

    /**
     * Esta funcion limpia las variables ingresadas que buscamos por $_POST.
     * @return mixed
     */
    protected function getFilteredPost()
    {
        $args = array(
          'email'   => array(
            'filter' => FILTER_SANITIZE_EMAIL,
          ),
          'mensaje' => array(
            'filter' => FILTER_SANITIZE_STRING,
          ),
        );

        return filter_input_array(INPUT_POST, $args);
    }

    /**
     * Settea el asunto del mensaje.
     *
     * @param $asunto
     */
    public function setAsunto($asunto)
    {
        $this->mailer->Subject = $asunto;
    }

    /**
     * Manda el mensaje. Retorna un booleano que indica si se mandó o no.
     * @return bool
     */
    public function send()
    {
        return $this->mailer->send();
    }

    /**
     * Retorna el error generado al mandar el mensaje.
     * @return string
     */
    public function getError()
    {
        return "Error: " . $this->mailer->ErrorInfo;
    }
}