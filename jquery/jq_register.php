<?php
global $MYSQL;
$errors = array();

function filter_param($val){
    return trim(htmlspecialchars(addslashes($val)));
}


function empty_check($info)
{
    global $errors;
    foreach ($info as $key => $value)
        if (strlen($value) == 0 || $value='0')
            $errors[] = "Поле $key обязательно для заполнения";
}

function pass_check($pass)
{
    global $errors;
    if (strlen($pass)<5)
        $errors[] = "Пароль меньше 5 символов";
}

function mail_check($mail)
{
    global $MYSQL;
    global $errors;
    $tbusers  = "pfx_users";
    if (!is_email($mail))
        $errors[] = "Введен некорректный mail";

    $result = $MYSQL->query("SELECT Count(*) FROM $tbusers WHERE email='$mail'");
    if($result[0]['count'] > 0)
        $errors[] = "К сожалению, данный email занят";

}

function phone_check($phone)
{
    global $errors;
    if (strlen($phone)<11 || ! is_numeric($phone))
        $errors[] = "Ошибка при вводе номера телефона";
    global $MYSQL;
    $tbusers  = "pfx_users";
    $result = $MYSQL->query("SELECT Count(*) FROM $tbusers WHERE mobile='$phone'");
    if($result[0]['count'] > 0)
        $errors[] = "Пользователь с этим номером телефона уже существует";
}


$userinfo_raw = json_decode($_POST['userinfo'],true);
$userinfo = array_map("filter_param", $userinfo_raw);
$userinfo['phone'] = $userinfo['phone_code'].$userinfo['phone'];
$userinfo['phone'] = preg_replace("(\s|\(|\)|\+|\-)",'', $userinfo['phone']);
empty_check($userinfo);
pass_check($userinfo['password']);
mail_check($userinfo['email']);
phone_check($userinfo['phone']);
$userinfo['birthday_day'] = (int)$userinfo['birthday_day'];
$userinfo['birthday_month'] = (int)$userinfo['birthday_month'];
$userinfo['birthday_year'] = (int)$userinfo['birthday_year'];
$userinfo['town'] = (int)$userinfo['town'];
$userinfo['country'] = (int)$userinfo['country'];
$userinfo['sex'] = (int) $userinfo['sex'];
$response = array();
//Есть ошибки?
if (count($errors)>0){
    $response['status'] = -1; //Ошибки в заполнении формы
    $response['errors'] = $errors; // Список ошибок
    echo json_encode($response);
    die();
}
global $USER;
$result = $USER->Add($userinfo);
if ($result>=0)
{
    $response['status'] = 0; // все ОК, пользователю отправили письмо
    echo json_encode($response);
    die();
}
$response['status'] = -2;
$response['errors'] = array('Произошла неизвестная ошибка. Попробуйте повторить позднее');
echo json_encode($response);
die();
?>