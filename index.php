<?php

require './config/db_data.php';
require 'func_db.php';

$arResult = [];

if(!isset($_SESSION["session_username"]))
{
    header("location: login.php");
} else {

//    include("./includes/header.php");
    get_ar_result();
    include "./html/index.html";

//    include("./includes/footer.php");
}





