<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//$gen = md5(uniqid(mt_rand(), false));
$password = 123456;
$hash = hash("sha256", md5($password));

echo $hash;
