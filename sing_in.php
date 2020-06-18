<?php
require "func_db.php";
require "./config/db_data.php";

$data = $_POST;
global $errorPDO;

if(isset($_SESSION['session_username']))
{
    header("Location: index.php");
} else
{

/*
 * Проверка и валдация данных в форме при регистрации нового пользователя
 * login, email происходит проверка на уникальность в базе
 * email проходит валидацию на стороне фронта и сервера
 * */
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = [];

        if (trim($data['login']) == '') {
            $errors['login'] = 'не ввели логин';
        }

        if ($data['password'] == '') {
            $errors['password'] = 'ввеите пароль';
        }

        $input_email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        if (!$input_email) {
            $errors['email'] = 'ввеите email или введен некорректно';
        }

        if (empty($errors)) {
            //производим запись нового пользователя в базу
            connect_db_pdo();
            echo $errors['error_dupl_login_email'];
        } else echo array_shift($errors);
    }

    include "./html/sing_in.html";

}





