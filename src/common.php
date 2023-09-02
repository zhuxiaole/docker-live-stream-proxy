<?php
    function getPlayUrlBySeam($platform, $id) {

        if ($platform == "bilibili") {
            $platform = "bili";
        }

        $result = shell_exec('/usr/local/seam/seam -a -l ' . $platform . ' -i ' . $id);

        // if ($platform == "douyin") {
        //     $result = preg_replace('/\{(.*)\}/', '', $result, 1);
        // }

        $data = json_decode($result);

        $playURL = $data->urls[0]->url;

        // else if ($platform == "douyu") {
        //     $reg = '/(https?):\/\/([^\/]+)/i';
        //     preg_match($reg, $playURL, $res);
        //     $patterns[0] = '/'.$res[2].'/';
        //     $replacements[0] = "hdltctwk.douyucdn2.cn";
        //     $playURL = preg_replace($patterns, $replacements, $playURL);
        // }
        return $playURL;
    }

    function getPlayUrlByJustLive($platform, $id) {

        if ($platform == "bili") {
            $platform = "bilibili";
        }

        $bstrURL = 'http://live.yj1211.work/api/live/getRealUrl?platform=' . $platform . '&roomId=' . $id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $bstrURL);                  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; Android 7.1.1; Mi Note 3 Build/NMF26X; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/61.0.3163.98 Mobile Safari/537.36' );
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result);
        $OD = $data->data->OD;
        $HD = $data->data->HD;
        $SD = $data->data->SD;
        $LD = $data->data->LD;

        $playURL = $OD;
        if (empty($playURL)) {
            $playURL = $HD;
        }
        if (empty($playURL)) {
            $playURL = $SD;
        }
        if (empty($playURL)) {
            $playURL = $LD;
        }
        return $playURL;
    }
?>