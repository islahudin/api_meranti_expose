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

class ServerInfoController
{

    protected  $customResponse;

    protected  $guestEntry;

    protected  $validator;
    protected  $conn;
    protected  $dbHandler;

    public function  __construct()
    {
        $this->customResponse = new CustomResponse();

        $this->validator = new Validator();
        // $db = new DbConnect();
        // $this->conn = $db->connect();
        $this->dbHandler = new DbHandler();
        date_default_timezone_set('Asia/Jakarta');
    }


    public function checkServerInfo(Request $request, Response $response, $id)
    {

        $this->validator->validate($request, [
            "v_code" => v::notEmpty(),
            "type_app" => v::notEmpty(),
            "platform" => v::notEmpty()
        ]);

        if ($this->validator->failed()) {
            $responseMessage = $this->validator->errors;
            return $this->customResponse->is400Response($response, $responseMessage);
        }

        $v_code = CustomRequestHandler::getParam($request, "v_code");
        $type_app = CustomRequestHandler::getParam($request, "type_app");
        $platform = CustomRequestHandler::getParam($request, "platform");

        $otp = OTP;

        $created_at = date('Y-m-d H:i:s');


        $sql = "SELECT
        id AS id_server, pesan, keterangan, `status`
        from tbl_server
        ";
        $result = $this->dbHandler->getDataAll($sql);

        $arr1 = null;


        if (($result->rowCount()) > 0
        ) {

            $rowServer = $result->fetch(PDO::FETCH_ASSOC);
            $status_server = $rowServer['status'];

            if ($status_server == 'on') {

                $sql = "SELECT
                id_update, version_code, version_name, pesan_update, status_update, IF(`required`='on',1,0) AS mandatory
                FROM tbl_update_version_apps WHERE version_code <> '$v_code' AND status_update='1' AND platform='$platform' AND type_app='$type_app'";
                $result = $this->dbHandler->getDataAll($sql);

                if (($result->rowCount()) > 0) {
                    $rowUpdate = $result->fetch(PDO::FETCH_ASSOC);

                    $arr1 = array(

                        'update' => "on",
                        'version_code' => "" . $rowUpdate["version_code"] . "",
                        'version_name' => "" . $rowUpdate["version_name"] . "",
                        'message_update' => "" . $rowUpdate["pesan_update"] . "",
                        'status_update' => "" . $rowUpdate["status_update"] . "",
                        'mandatory' => "" . $rowUpdate["mandatory"] . "",
                        'message' => "Update telah tersedia",
                    );
                } else {
                    $arr1 = array(

                        'update' => "off",
                        'version_code' => "",
                        'version_name' => "",
                        'message_update' => "",
                        'status_update' => "",
                        'mandatory' => "",
                        'message' => "update belum tersedia",
                    );
                }
                $tmp = array($arr1, "update_app" => $arr1);

                $arr12 = null;

                $arr12 = array(

                    'server' => $status_server,
                    'otp' =>  $otp,
                    'message_server' =>  "SERVER ON",
                    'desc_server' =>  "server on",
                    'update_app' =>  $arr1,
                );
            } else if ($status_server == 'off') {

                // $response["message_server"] = "" . $rowServer["pesan"] . "";
                // $response["desc_server"] = "" . $rowServer["keterangan"] . "";
                $arr12 = null;

                $arr12 = array(

                    'server' => 'of',
                    'otp' =>  $otp,
                    'message_server' =>  "SERVER OFF",
                    'desc_server' =>  "server off",
                    'update_app' =>  $arr1,
                );
            }
            return $this->customResponse->is200Response($response, $arr12);
        } else {
            // $arr1 = array(
            //     'success' => false,

            //     'message' =>  "Gagal menghubungkan ke server"
            // );

            return $this->customResponse->is404Response($response, "faild");
        }

        // $response["data"] = $tmp;
        // return $this->customResponse->is200Response($response, $arr12);
    }

    public function checkServerInfoGet(Request $request, Response $response, $id)
    {



        $v_code = "1";
        $type_app = "user";
        $platform = "android";

        $otp = OTP;

        $created_at = date('Y-m-d H:i:s');


        $sql = "SELECT
        id AS id_server, pesan, keterangan, `status`
        from tbl_server
        ";
        $result = $this->dbHandler->getDataAll($sql);

        $arr1 = null;


        if (($result->rowCount()) > 0
        ) {

            $rowServer = $result->fetch(PDO::FETCH_ASSOC);
            $status_server = $rowServer['status'];

            if ($status_server == 'on') {

                $sql = "SELECT
                id_update, version_code, version_name, pesan_update, status_update, IF(`required`='on',1,0) AS mandatory
                FROM tbl_update_version_apps WHERE version_code <> '$v_code' AND status_update='1' AND platform='$platform' AND type_app='$type_app'";
                $result = $this->dbHandler->getDataAll($sql);

                if (($result->rowCount()) > 0) {
                    $rowUpdate = $result->fetch(PDO::FETCH_ASSOC);

                    $arr1 = array(

                        'update' => "on",
                        'version_code' => "" . $rowUpdate["version_code"] . "",
                        'version_name' => "" . $rowUpdate["version_name"] . "",
                        'message_update' => "" . $rowUpdate["pesan_update"] . "",
                        'status_update' => "" . $rowUpdate["status_update"] . "",
                        'mandatory' => "" . $rowUpdate["mandatory"] . "",
                        'message' => "Update telah tersedia",
                    );
                } else {
                    $arr1 = array(

                        'update' => "off",
                        'version_code' => "",
                        'version_name' => "",
                        'message_update' => "",
                        'status_update' => "",
                        'mandatory' => "",
                        'message' => "update belum tersedia",
                    );
                }
                $tmp = array($arr1, "update_app" => $arr1);

                $arr12 = null;

                $arr12 = array(

                    'server' => $status_server,
                    'otp' =>  $otp,
                    'message_server' =>  "SERVER ON",
                    'desc_server' =>  "server on",
                    'update_app' =>  $arr1,
                );
            } else if ($status_server == 'off') {

                // $response["message_server"] = "" . $rowServer["pesan"] . "";
                // $response["desc_server"] = "" . $rowServer["keterangan"] . "";
                $arr12 = null;

                $arr12 = array(

                    'server' => 'of',
                    'otp' =>  $otp,
                    'message_server' =>  "SERVER OFF",
                    'desc_server' =>  "server off",
                    'update_app' =>  $arr1,
                );
            }
            return $this->customResponse->is200Response($response, $arr12);
        } else {
            // $arr1 = array(
            //     'success' => false,

            //     'message' =>  "Gagal menghubungkan ke server"
            // );

            return $this->customResponse->is404Response($response, "faild");
        }

        // $response["data"] = $tmp;
        // return $this->customResponse->is200Response($response, $arr12);
    }
}
