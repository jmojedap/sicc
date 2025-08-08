<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class Qr_generator {

    protected $options;

    public function __construct($config = [])
    {
        $defaults = [
            'version'    => 5,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'   => QRCode::ECC_L, // Bajo para URLs largas
            'scale'      => 8
        ];

        $this->options = new QROptions(array_merge($defaults, $config));
    }

    /**
     * Genera QR a partir de cualquier texto
     * @return string :: Url de la imagen del código QR generado
     */
    public function generate($text, $folder, $filename = null)
    {
        if (!$filename) {
            $filename = uniqid('qr_') . '.png';
        }

        $filepath = PATH_CONTENT . $folder . '/' . $filename;

        (new QRCode($this->options))->render($text, $filepath);

        return URL_CONTENT . $folder . '/' . $filename;
    }

    /**
     * Genera QR para una URL (opcionalmente validada)
     */
    public function generate_url_qr($url, $folder, $filename = null)
    {
        // Validar que sea URL válida
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception('La URL proporcionada no es válida.');
        }

        // Ajustar algunas opciones para URLs
        $this->options->eccLevel = QRCode::ECC_M; // Un poco más robusto
        $this->options->version = 7; // Permite más contenido
        $this->options->scale = 8;

        return $this->generate($url, $folder, $filename);
    }
}
