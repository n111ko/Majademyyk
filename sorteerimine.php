<?php
require('zoneconf.php');
function kysiKaupadeAndmed($sorttulp="kinnisvaraNimetus", $otsisona=""){
    global $conn;
    $lubatudtulbad = array("kinnisvaraNimetus", "ettevoteNimetus", "linn", "aadress", "pilt", "suurus", "tubadeArv", "hind", "hind_asc", "hind_desc", "suurus_desc", "tubadeArv_desc");

    if(!in_array($sorttulp, $lubatudtulbad)){
        return "lubamatu tulp";
    }

    $otsisona=addslashes(stripslashes($otsisona));

    $tingimused = "ettevote.ettevote_ID = kinnisvara.ettevote_ID";

    // arv või sõna
    if (!empty($otsisona)) {
        if (is_numeric($otsisona)) {
            $tingimused .= " AND kinnisvara.tubadeArv = $otsisona";
        } else {
            $tingimused .= " AND (kinnisvaraNimetus LIKE '%$otsisona%' OR ettevoteNimetus LIKE '%$otsisona%')";
        }
    }

    $orderBy = $sorttulp;
    if ($sorttulp == "hind_desc") {
        $orderBy = "hind DESC";
    } elseif ($sorttulp == "hind_asc") {
        $orderBy = "hind ASC";
    } elseif ($sorttulp == "suurus_desc") {
        $orderBy = "suurus DESC";
    } elseif ($sorttulp == "tubadeArv_desc") {
        $orderBy = "tubadeArv DESC";
    }

    $kask = $conn->prepare ("SELECT kinnisvara.kinnisvara_ID, kinnisvaraNimetus, ettevoteNimetus, kinnisvara.linn, kinnisvara.aadress, pilt, suurus, tubadeArv, hind
            FROM kinnisvara, ettevote
            WHERE $tingimused
            ORDER BY $orderBy");

    // echo $yhendus->error;
    $kask->bind_result($id, $kinnisvaraNimetus, $ettevoteNimetus, $linn, $aadress, $pilt, $suurus, $tubadeArv, $hind);
    $kask->execute();
    $hoidla=array();
    while($kask->fetch()){
        $maja=new stdClass();
        $maja->kinnisvara_ID=$id;
        $maja->kinnisvaraNimetus=$kinnisvaraNimetus;
        $maja->ettevoteNimetus=$ettevoteNimetus;
        $maja->linn=$linn;
        $maja->aadress=$aadress;
        $maja->pilt=$pilt;
        $maja->suurus=$suurus;
        $maja->tubadeArv=$tubadeArv;
        $maja->hind=$hind;
        array_push($hoidla, $maja);
    }
    return $hoidla;
}