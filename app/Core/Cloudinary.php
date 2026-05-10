<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Clase Cloudinary
 *
 * Sube imágenes a Cloudinary usando la API REST directamente (sin SDK externo).
 * Requiere que las variables de entorno CLOUDINARY_* estén definidas.
 */
class Cloudinary
{
    private string $cloudName;
    private string $apiKey;
    private string $apiSecret;
    private string $folder;

    public function __construct()
    {
        $this->cloudName = (string) getenv('CLOUDINARY_CLOUD_NAME');
        $this->apiKey    = (string) getenv('CLOUDINARY_API_KEY');
        $this->apiSecret = (string) getenv('CLOUDINARY_API_SECRET');
        $this->folder    = (string) (getenv('CLOUDINARY_UPLOAD_FOLDER') ?: 'housegym/maquinas');

        if (!$this->cloudName || !$this->apiKey || !$this->apiSecret) {
            throw new \RuntimeException(
                'Faltan credenciales de Cloudinary. Revisa el archivo .env: ' .
                'CLOUDINARY_CLOUD_NAME, CLOUDINARY_API_KEY, CLOUDINARY_API_SECRET'
            );
        }
    }

    /**
     * Sube un archivo de imagen a Cloudinary.
     *
     * @param string $filePath  Ruta temporal del archivo (e.g. $_FILES['foto']['tmp_name'])
     * @param string $publicId  ID público único para la imagen (sin extensión). Se genera automáticamente si está vacío.
     * @return string           URL segura (HTTPS) de la imagen subida.
     * @throws \RuntimeException Si la subida falla.
     */
    public function upload(string $filePath, string $publicId = ''): string
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \RuntimeException("El archivo de imagen no existe o no es legible: {$filePath}");
        }

        $timestamp = (string) time();

        // Parámetros que se firman (deben estar en orden alfabético)
        $paramsToSign = [
            'folder'    => $this->folder,
            'timestamp' => $timestamp,
        ];

        if ($publicId !== '') {
            $paramsToSign['public_id'] = $publicId;
        }

        ksort($paramsToSign);
        $signature = $this->generateSignature($paramsToSign);

        // Construir el payload multipart
        $postFields = [
            'file'      => new \CURLFile($filePath),
            'timestamp' => $timestamp,
            'api_key'   => $this->apiKey,
            'signature' => $signature,
            'folder'    => $this->folder,
        ];

        if ($publicId !== '') {
            $postFields['public_id'] = $publicId;
        }

        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $postFields,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new \RuntimeException("Error cURL al subir imagen a Cloudinary: {$curlError}");
        }

        $data = json_decode((string) $response, true);

        if ($httpCode !== 200 || !isset($data['secure_url'])) {
            $errorMsg = $data['error']['message'] ?? "HTTP {$httpCode}";
            throw new \RuntimeException("Cloudinary rechazó la imagen: {$errorMsg}");
        }

        return (string) $data['secure_url'];
    }

    /**
     * Elimina una imagen de Cloudinary por su public_id.
     *
     * @param string $publicId  El public_id completo (incluyendo folder), sin extensión.
     * @return bool             True si se eliminó correctamente.
     */
    public function delete(string $publicId): bool
    {
        $timestamp = (string) time();
        $paramsToSign = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];
        ksort($paramsToSign);
        $signature = $this->generateSignature($paramsToSign);

        $postFields = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
            'api_key'   => $this->apiKey,
            'signature' => $signature,
        ];

        $url = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($postFields),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode((string) $response, true);
        return isset($data['result']) && $data['result'] === 'ok';
    }

    /**
     * Extrae el public_id de una URL de Cloudinary.
     * Ejemplo: "https://res.cloudinary.com/demo/image/upload/v123/housegym/maquinas/abc.jpg"
     *       → "housegym/maquinas/abc"
     *
     * @param string $url URL segura de Cloudinary.
     * @return string     Public ID sin extensión.
     */
    public static function extractPublicId(string $url): string
    {
        // Remover la extensión y todo lo que va antes de /upload/vXXX/
        if (preg_match('#/upload/(?:v\d+/)?(.+)\.[a-z]+$#i', $url, $matches)) {
            return $matches[1];
        }
        return '';
    }

    /**
     * Genera la firma HMAC-SHA1 requerida por la API de Cloudinary.
     *
     * @param array $params Parámetros en orden alfabético.
     * @return string       Hash SHA1 en hexadecimal.
     */
    private function generateSignature(array $params): string
    {
        $parts = [];
        foreach ($params as $key => $value) {
            $parts[] = "{$key}={$value}";
        }
        $stringToSign = implode('&', $parts) . $this->apiSecret;
        return sha1($stringToSign);
    }
}
