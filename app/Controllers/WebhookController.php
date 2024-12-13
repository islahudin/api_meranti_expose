<?php

namespace App\Controllers;

use App\Models\GuestEntry;
use App\Requests\CustomRequestHandler;
use App\Response\CustomResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Respect\Validation\Validator as v;
use App\Validation\Validator;
// use DbConnect;
use PDO;
use DbHandler;
// $settings = require_once  __DIR__ . "/../../config/settings.php";



class WebhookController
{

    protected $customResponse;


    protected $validator;
    protected $conn;
    protected $dbHandler;

    public function __construct()
    {
        $this->customResponse = new CustomResponse();

        $this->validator = new Validator();
        $this->dbHandler = new DbHandler();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function webhookGit(Request $request, Response $response)
    {

        $arr1 = null;

        // Secret Token dari GitHub (Opsional)
        $secret = 'myProject@551';

        // Ambil payload dari GitHub
        $payload = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';

        // Validasi Signature
        if ($secret && $signature) {
            $hash = 'sha1=' . hash_hmac('sha1', $payload, $secret);
            if (!hash_equals($hash, $signature)) {
                http_response_code(403);
                die('Invalid signature');
            }
        }

        // Jalankan perintah Git
        // shell_exec('cd /www/wwwroot/merantiapi.qordinate.com/api_meranti_expose && git pull origin main');
        shell_exec('cd /www/wwwroot/merantiapi.qordinate.com/api_meranti_expose && git fetch origin main && git reset --hard origin/main');

        // http_response_code(200);

        $arr1[]=array(
            "message_gan"=>"seuccess sekali"
        );

        return $this->customResponse->is200Response($response, $arr1);


    }
}
