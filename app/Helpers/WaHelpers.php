<?php
namespace App\Helpers;


class WaHelpers
{
    public static function test() {
        return "yes";
    }

    // public static function renderPNG(
    //     array $polygonCoords,
    //     array $roadCoords,
    //     string $roadName,
    //     int $width = 600,
    //     int $height = 600
    // ) {
    //     $padding = 50;

    //     $image = imagecreatetruecolor($width, $height);

    //     // ===== COLORS =====
    //     $bg = imagecolorallocate($image, 242, 242, 242);
    //     $black = imagecolorallocate($image, 0, 0, 0);
    //     $gray = imagecolorallocate($image, 120, 120, 120);
    //     $lightGray = imagecolorallocate($image, 180, 180, 180);

    //     imagefill($image, 0, 0, $bg);

    //     // ===== BOUNDING BOX =====
    //     $all = array_merge($polygonCoords, $roadCoords);
    //     $lats = array_column($all, 1);
    //     $lngs = array_column($all, 0);

    //     $minLat = min($lats);
    //     $maxLat = max($lats);
    //     $minLng = min($lngs);
    //     $maxLng = max($lngs);

    //     // ===== PROJECTION =====
    //     $project = function ($lng, $lat) use (
    //         $minLng, $maxLng, $minLat, $maxLat, $width, $height, $padding
    //     ) {
    //         $x = (($lng - $minLng) / ($maxLng - $minLng))
    //            * ($width - $padding * 2) + $padding;

    //         $y = $height - (
    //             (($lat - $minLat) / ($maxLat - $minLat))
    //             * ($height - $padding * 2) + $padding
    //         );

    //         return [$x, $y];
    //     };

    //     // ===== DRAW ROAD =====
    //     $roadPoints = [];
    //     foreach ($roadCoords as $p) {
    //         $roadPoints[] = $project($p[0], $p[1]);
    //     }

    //     imagesetthickness($image, 8);
    //     for ($i = 0; $i < count($roadPoints) - 1; $i++) {
    //         $x1 = $roadPoints[$i][0];
    //         $y1 = $roadPoints[$i][1];
    //         $x2 = $roadPoints[$i + 1][0];
    //         $y2 = $roadPoints[$i + 1][1];

    //         imageline($image, $x1, $y1, $x2, $y2, $lightGray);
    //     }

    //     imagesetthickness($image, 2);
    //     for ($i = 0; $i < count($roadPoints) - 1; $i++) {
    //         $x1 = $roadPoints[$i][0];
    //         $y1 = $roadPoints[$i][1];
    //         $x2 = $roadPoints[$i + 1][0];
    //         $y2 = $roadPoints[$i + 1][1];

    //         imageline($image, $x1, $y1, $x2, $y2, $gray);
    //     }

    //     // ===== ROAD LABEL ROTATION =====
    //     // ===== ROAD LABEL (MASUK KE DALAM JALAN, TANPA MIRING) =====

    //     // Ambil segmen tengah
    //     $idx = (int)(count($roadPoints) / 2);
    //     $p1 = $roadPoints[$idx - 1];
    //     $p2 = $roadPoints[$idx];

    //     // arah garis
    //     $dx = $p2[0] - $p1[0];
    //     $dy = $p2[1] - $p1[1];

    //     // panjang segmen
    //     $length = sqrt($dx * $dx + $dy * $dy);
    //     if ($length == 0) $length = 1;

    //     // vektor normal (tegak lurus garis)
    //     $nx = -$dy / $length;
    //     $ny =  $dx / $length;

    //     // posisi teks (tengah segmen + offset ke dalam jalan)
    //     $offset = 10; // jarak masuk ke jalan (pixel)

    //     $textX = ($p1[0] + $p2[0]) / 2 + $nx * $offset;
    //     $textY = ($p1[1] + $p2[1]) / 2 + $ny * $offset;

    //     // font
    //     $fontPath = public_path('fonts/DejaVuSans.ttf');
    //     $fontSize = 12;

    //     // TULIS TANPA ROTASI
    //     imagettftext(
    //         $image,
    //         $fontSize,
    //         0,              // ⬅️ NO ROTATION
    //         (int)$textX,
    //         (int)$textY,
    //         $black,
    //         $fontPath,
    //         $roadName
    //     );


    //     // ===== DRAW POLYGON =====
    //     imagesetthickness($image, 2);
    //     for ($i = 0; $i < count($polygonCoords) - 1; $i++) {
    //         [$x1, $y1] = $project($polygonCoords[$i][0], $polygonCoords[$i][1]);
    //         [$x2, $y2] = $project($polygonCoords[$i + 1][0], $polygonCoords[$i + 1][1]);
    //         imageline($image, $x1, $y1, $x2, $y2, $black);
    //     }

    //     // ===== OUTPUT BUFFER =====
    //     ob_start();
    //     imagepng($image);
    //     $png = ob_get_clean();

    //     imagedestroy($image);

    //     return $png;
    // }

    public static function renderPNG(
        array $polygonCoords,
        array $roadCoords,
        string $roadName,
        int $width = 600,
        int $height = 600
    ) {
        $padding = 50;

        $image = imagecreatetruecolor($width, $height);

        // ===== COLORS =====
        $bg = imagecolorallocate($image, 242, 242, 242);
        $black = imagecolorallocate($image, 0, 0, 0);
        $gray = imagecolorallocate($image, 120, 120, 120);
        $lightGray = imagecolorallocate($image, 180, 180, 180);

        imagefill($image, 0, 0, $bg);

        // ===== BOUNDING BOX (AMAN JIKA ROAD KOSONG) =====
        $all = $polygonCoords;
        if (!empty($roadCoords)) {
            $all = array_merge($polygonCoords, $roadCoords);
        }

        $lats = array_column($all, 1);
        $lngs = array_column($all, 0);

        $minLat = min($lats);
        $maxLat = max($lats);
        $minLng = min($lngs);
        $maxLng = max($lngs);

        // Hindari division by zero
        if ($minLat == $maxLat) $maxLat += 0.00001;
        if ($minLng == $maxLng) $maxLng += 0.00001;

        // ===== PROJECTION =====
        $project = function ($lng, $lat) use (
            $minLng, $maxLng, $minLat, $maxLat, $width, $height, $padding
        ) {
            $x = (($lng - $minLng) / ($maxLng - $minLng))
                * ($width - $padding * 2) + $padding;

            $y = $height - (
                (($lat - $minLat) / ($maxLat - $minLat))
                * ($height - $padding * 2) + $padding
            );

            return [$x, $y];
        };

        // ===== DRAW ROAD (HANYA JIKA ADA) =====
        if (count($roadCoords) >= 2) {

            $roadPoints = [];
            foreach ($roadCoords as $p) {
                $roadPoints[] = $project($p[0], $p[1]);
            }

            imagesetthickness($image, 8);
            for ($i = 0; $i < count($roadPoints) - 1; $i++) {
                imageline(
                    $image,
                    $roadPoints[$i][0],
                    $roadPoints[$i][1],
                    $roadPoints[$i + 1][0],
                    $roadPoints[$i + 1][1],
                    $lightGray
                );
            }

            imagesetthickness($image, 2);
            for ($i = 0; $i < count($roadPoints) - 1; $i++) {
                imageline(
                    $image,
                    $roadPoints[$i][0],
                    $roadPoints[$i][1],
                    $roadPoints[$i + 1][0],
                    $roadPoints[$i + 1][1],
                    $gray
                );
            }

            // ===== ROAD LABEL =====
            $idx = (int)(count($roadPoints) / 2);
            $p1 = $roadPoints[$idx - 1];
            $p2 = $roadPoints[$idx];

            $dx = $p2[0] - $p1[0];
            $dy = $p2[1] - $p1[1];
            $length = sqrt($dx * $dx + $dy * $dy);
            if ($length == 0) $length = 1;

            $nx = -$dy / $length;
            $ny =  $dx / $length;

            $offset = 10;
            $textX = ($p1[0] + $p2[0]) / 2 + $nx * $offset;
            $textY = ($p1[1] + $p2[1]) / 2 + $ny * $offset;

            $fontPath = public_path('fonts/DejaVuSans.ttf');
            $fontSize = 12;

            imagettftext(
                $image,
                $fontSize,
                0,
                (int)$textX,
                (int)$textY,
                $black,
                $fontPath,
                $roadName
            );
        }

        // ===== DRAW POLYGON (WAJIB ADA) =====
        imagesetthickness($image, 2);
        for ($i = 0; $i < count($polygonCoords) - 1; $i++) {
            [$x1, $y1] = $project($polygonCoords[$i][0], $polygonCoords[$i][1]);
            [$x2, $y2] = $project($polygonCoords[$i + 1][0], $polygonCoords[$i + 1][1]);
            imageline($image, $x1, $y1, $x2, $y2, $black);
        }

        // ===== OUTPUT =====
        ob_start();
        imagepng($image);
        $png = ob_get_clean();

        imagedestroy($image);

        return $png;
    }

    public static function renderPNGlokasi(
        array $polygonCoords,
        array $sideLabels,
        array $roadCoords,
        string $roadName,
        int $width = 600,
        int $height = 600
    ) {
        $padding = 50;

        $image = imagecreatetruecolor($width, $height);

        // ===== COLORS =====
        $bg = imagecolorallocate($image, 242, 242, 242);
        $black = imagecolorallocate($image, 0, 0, 0);
        $gray = imagecolorallocate($image, 120, 120, 120);
        $lightGray = imagecolorallocate($image, 180, 180, 180);

        imagefill($image, 0, 0, $bg);

        // ===== BOUNDING BOX (AMAN JIKA ROAD KOSONG) =====
        $all = $polygonCoords;
        if (!empty($roadCoords)) {
            $all = array_merge($polygonCoords, $roadCoords);
        }

        $lats = array_column($all, 1);
        $lngs = array_column($all, 0);

        $minLat = min($lats);
        $maxLat = max($lats);
        $minLng = min($lngs);
        $maxLng = max($lngs);

        // Hindari division by zero
        if ($minLat == $maxLat) $maxLat += 0.00001;
        if ($minLng == $maxLng) $maxLng += 0.00001;

        // ===== PROJECTION =====
        $project = function ($lng, $lat) use (
            $minLng, $maxLng, $minLat, $maxLat, $width, $height, $padding
        ) {
            $x = (($lng - $minLng) / ($maxLng - $minLng))
                * ($width - $padding * 2) + $padding;

            $y = $height - (
                (($lat - $minLat) / ($maxLat - $minLat))
                * ($height - $padding * 2) + $padding
            );

            return [$x, $y];
        };

        // ===== DRAW ROAD (HANYA JIKA ADA) =====
        if (count($roadCoords) >= 2) {

            $roadPoints = [];
            foreach ($roadCoords as $p) {
                $roadPoints[] = $project($p[0], $p[1]);
            }

            imagesetthickness($image, 8);
            for ($i = 0; $i < count($roadPoints) - 1; $i++) {
                imageline(
                    $image,
                    $roadPoints[$i][0],
                    $roadPoints[$i][1],
                    $roadPoints[$i + 1][0],
                    $roadPoints[$i + 1][1],
                    $lightGray
                );
            }

            imagesetthickness($image, 2);
            for ($i = 0; $i < count($roadPoints) - 1; $i++) {
                imageline(
                    $image,
                    $roadPoints[$i][0],
                    $roadPoints[$i][1],
                    $roadPoints[$i + 1][0],
                    $roadPoints[$i + 1][1],
                    $gray
                );
            }

            // ===== ROAD LABEL =====
            $idx = (int)(count($roadPoints) / 2);
            $p1 = $roadPoints[$idx - 1];
            $p2 = $roadPoints[$idx];

            $dx = $p2[0] - $p1[0];
            $dy = $p2[1] - $p1[1];
            $length = sqrt($dx * $dx + $dy * $dy);
            if ($length == 0) $length = 1;

            $nx = -$dy / $length;
            $ny =  $dx / $length;

            $offset = 10;
            $textX = ($p1[0] + $p2[0]) / 2 + $nx * $offset;
            $textY = ($p1[1] + $p2[1]) / 2 + $ny * $offset;

            $fontPath = public_path('fonts/DejaVuSans.ttf');
            $fontSize = 12;

            imagettftext(
                $image,
                $fontSize,
                0,
                (int)$textX,
                (int)$textY,
                $black,
                $fontPath,
                $roadName
            );
        }

        // ===== DRAW POLYGON (WAJIB ADA) =====
        // imagesetthickness($image, 2);
        // for ($i = 0; $i < count($polygonCoords) - 1; $i++) {
        //     [$x1, $y1] = $project($polygonCoords[$i][0], $polygonCoords[$i][1]);
        //     [$x2, $y2] = $project($polygonCoords[$i + 1][0], $polygonCoords[$i + 1][1]);
        //     imageline($image, $x1, $y1, $x2, $y2, $black);
        // }

        // ===== DRAW POLYGON + LABEL DARI INPUT =====
        imagesetthickness($image, 2);
        $fontPath = public_path('fonts/DejaVuSans.ttf');
        $fontSize = 12;

        for ($i = 0; $i < count($polygonCoords) - 1; $i++) {

            // koordinat
            [$lng1, $lat1] = $polygonCoords[$i];
            [$lng2, $lat2] = $polygonCoords[$i + 1];

            // proyeksi
            [$x1, $y1] = $project($lng1, $lat1);
            [$x2, $y2] = $project($lng2, $lat2);

            // garis
            imageline($image, $x1, $y1, $x2, $y2, $black);

            // =============================
            // LABEL DARI FORM (10,20,30,40)
            // =============================
            $label = $sideLabels[$i] ?? '';

            if ($label !== '') {

                // titik tengah
                $mx = ($x1 + $x2) / 2;
                $my = ($y1 + $y2) / 2;

                // normal keluar
                $dx = $x2 - $x1;
                $dy = $y2 - $y1;
                $len = sqrt($dx * $dx + $dy * $dy);
                if ($len == 0) $len = 1;

                $nx = -$dy / $len;
                $ny =  $dx / $len;

                $offset = 15;

                $textX = $mx + $nx * $offset;
                $textY = $my + $ny * $offset;

                imagettftext(
                    $image,
                    $fontSize,
                    0,
                    (int)$textX,
                    (int)$textY,
                    $black,
                    $fontPath,
                    $label
                );
            }
        }


        // ===== OUTPUT =====
        ob_start();
        imagepng($image);
        $png = ob_get_clean();

        imagedestroy($image);

        return $png;
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
