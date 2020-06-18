<?php
require "./config/db_data.php";
$no_user = [];

/*
 * Функция устанавливает соединение с базой данных
 * сохраняет нового пользователя
 * устанавливает значение сессии
 * редирект на траницу авторизации
 * сбор ошибок от PDO в массив $errorPDO
 * */
function connect_db_pdo()
{
    global $errors;
    global $data;
    global $dsn, $user, $password, $opt;

    try {
        $pdo = new PDO($dsn, $user, $password, $opt);
        $sql = "INSERT INTO `tbl_users`(user_login, user_password, user_email) VALUES (:user_login, :user_password, :user_email)";
        $stmt = $pdo->prepare($sql);
        $params = [':user_login' => htmlspecialchars($data['login']), ':user_password' => md5(htmlspecialchars($data['password'])), ':user_email' => htmlspecialchars($data['email'])];
        $stmt->execute($params);

        $_SESSION['session_username'] = htmlspecialchars($data['login']);
        $_SESSION['session_userpass'] = md5($data['password']);

        header('Location: login.php');
    } catch (Exception $e) {
        $errorPDO = $stmt->errorInfo();
        $error_code_pdo = $errorPDO[0];    // код ошибки
        $error_code_msg = $errorPDO[2];     // сообщение ошибки
        if(intval($error_code_pdo) == 23000)      // проверка ошибки на уникальность значений login и email
        {
            $errors['error_dupl_login_email'] = 'Указанный логин или емейл уже зарегестрирован';
            return $errorPDO;
        }
    }
}


/*
 * Функция устанавливает соединение с бд  -  получает запись пользователя из бд по данным из сессии
 * если есть в базе такой пользователь - редирект на index.php
 *
 * */
function is_authorization_user()
{
    $session_username = $_SESSION['session_username'];
    $session_userpass = $_SESSION['session_userpass'];
    global $dsn, $user, $password, $opt;
    global $no_user;
    global $errors;
    print_r($_SESSION);

    try {
        $pdo = new PDO($dsn, $user, $password, $opt);
        $stmt = $pdo->query("SELECT * FROM `tbl_users` WHERE `user_login` = '$session_username' AND `user_password` = '$session_userpass' ");
        $result = $stmt->fetch();

        if(!empty($result)){
            $_SESSION['user_id'] = $result['id_user'];
            header("Location: index.php");

        } else $no_user[] = 'данный пользователь не зарегестрирован.';
        print_r($no_user);

    } catch (Exception $e) {
        $errors[] = $e->getMessage();
        print_r($errors);
    }

}

/*
 * Функция устанавливает соединение с бд - получает запись пользователя из бд по данным из формы
 * если находит пользователя - редирект на index.php
 * */
function authorization_user()
{
    $username = $_POST['login'];
    $userpass = md5($_POST['password']);
    global $dsn, $user, $password, $opt;
    global $no_user;
    global $errors;

    try {
        $pdo = new PDO($dsn, $user, $password, $opt);
        $stmt = $pdo->query("SELECT * FROM `tbl_users` WHERE `user_login` = '$username' AND `user_password` = '$userpass' ");
        $result = $stmt->fetch();

        if(!empty($result))
        {
            $_SESSION['user_id'] = $result['id_user'];
            $_SESSION['session_username'] = htmlspecialchars($_POST['login']);
            $_SESSION['session_userpass'] = md5($_POST['password']);
            header("Location: index.php");
        } else $no_user[] = 'данный пользователь не зарегестрирован.';
            print_r($no_user);

    } catch (Exception $e) {
        $errors[] = $e->getMessage();
        print_r($errors);
    }
}


/*
 * Получение списка пунктов меню
 * Вывод всех пунктов меню в $arResult и в шаблон index.php
 * */
function get_ar_result(){

    try {
        global $dsn, $user, $password, $opt;
        global $arResult;
        $id_user = $_SESSION['user_id'];
        $pdo = new PDO($dsn, $user, $password, $opt);
//        $stmt = $pdo->query("SELECT * FROM `tbl_menu_items`");
        $stmt = $pdo->query("SELECT * FROM sm_db.tbl_accssec JOIN sm_db.tbl_menu_items ON tbl_accssec.menu_id=tbl_menu_items.menu_id WHERE user_id=$id_user");

        while($result = $stmt->fetch()){
            $arResult[] = $result;
        }

//        echo "<pre>";
//        print_r($arResult);
//        return $arResult;

//        if(!empty($result))
//        {
//        $_SESSION['session_username'] = htmlspecialchars($_POST['login']);
//        $_SESSION['session_userpass'] = md5($_POST['password']);
//        header("Location: index.php");
//        } else $no_user[] = 'данный пользователь не зарегестрирован.';
//        print_r($no_user);
//        include "./html/index.html";

    } catch (Exception $e) {
        $errors[] = $e->getMessage();
        print_r($errors);
    }

}






