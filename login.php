<?php
require "./config/db_data.php";
require "func_db.php";

global $errors;
global $no_user;



if(isset($_SESSION['session_username']) && isset($_SESSION['session_userpass']))
{
    is_authorization_user();  // проверка на наличие в базе
}
include "./html/login.html";

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['login']) && isset($_POST['password']))
    {
        authorization_user();
    }
}




