<?php
$parool = 'admin';
$sool = 'test';
$krypt = crypt($parool, $sool);
echo $krypt;