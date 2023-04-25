<?php

    require_once "common.php";

    $platform = $_GET[ 'platform' ];
    $id = $_GET[ 'id' ];

    $playURL = getPlayUrlByJustLive($platform, $id);

    header( 'location:' . $playURL );
?>