<?php
namespace App\Helpers;


class WaHelpers
{
    public static function test() {
        return "yes";
    }

    public static function sendWaOld($noHp, $msg)
    {
        $key = env("FONTEI_KEY");

        if (substr($noHp, 0, 1) == '0') {
            $noHp = '62' . substr($noHp, 1);
        } else if (substr($noHp, 0, 1) == '8') {
            $noHp = '62' . $noHp;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
            'target' => $noHp,
            'message' => $msg,
        ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $key"
            ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
        $error_msg = curl_error($curl);
        }
        curl_close($curl);

        if (isset($error_msg)) {
        echo $error_msg;
        }
        return $response;
    }

    // Woowa
    public static function sendWa($noHp, $msg)
    {
        $key = env("WOOWA_KEY");
        // $url = 'http://116.203.191.58/api/async_send_message';
        $url = 'https://notifapi.com/send_message';


        //if $noHp doesnt have +62 then add +62
        if (substr($noHp, 0, 1) == '0') {
            $noHp = '+62' . substr($noHp, 1);
        } else if (substr($noHp, 0, 1) == '8') {
            $noHp = '+62' . $noHp;
        }


        $data = array(
            "phone_no" => $noHp,
            "key" => $key,
            "message" => $msg,
        );
        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 360);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    public static function sendFile($noHp, $filePath)
    {
        $key = env("WOOWA_KEY");
        $url = 'http://116.203.191.58/api/async_send_file_url';
        $file_path = $filePath;

        //if $noHp doesnt have +62 then add +62
        if (substr($noHp, 0, 1) == '0') {
            $noHp = '+62' . substr($noHp, 1);
        } else if (substr($noHp, 0, 1) == '8') {
            $noHp = '+62' . $noHp;
        }

        if ($file_path != null) {
            $data = array(
                "phone_no" => $noHp,
                "key" => $key,
                "url" => $file_path
            );
        } else {
            $data = array(
                "phone_no" => $noHp,
                "key" => $key
            );
        }
        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 360);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
}
