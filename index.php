<?php
session_start();
require('smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->setTemplateDir('smarty/templates');
$smarty->setCompileDir('smarty/templates_c');
$smarty->setCacheDir('smarty/cache');
$smarty->setConfigDir('smarty/configs');

if (isset($_REQUEST['action'])) {
    //przetwórz polecenie od użytkownika
    $action = $_REQUEST['action'];
    $db = new mysqli('localhost', 'root', '', 'registerandlogin');
    if ($db->errno)
        throw new RuntimeException('mysqli connection error: ' . $db->error);
    switch ($action) {
        case 'logout':
            $smarty->assign('info', "wylogowano poprawnie");
            $smarty->display('index.tpl');
            break;
        case 'register':
            if (!isset($_REQUEST['email']) && !isset($_REQUEST['password']))
                $smarty->display('register.tpl');
            else {
                $email = $_REQUEST['email'];
                $password = $_REQUEST['password'];
                $query = $db->prepare("INSERT INTO user (id, email, password) VALUES (NULL, ?, ?)");
                $password = password_hash($password, PASSWORD_ARGON2I);
                $query->bind_param('ss', $email, $password);
                $result = $query->execute();
                if ($result)
                    $smarty->assign('info', "Konto utworzono poprawnie.");
                else {
                    if ($query->errno == 1062)
                        $smarty->assign('error', "Konto o takim adresie już istnieje.");
                    else
                        $smarty->assign('error', "Błąd podczas tworzenia konta.");
                }
                $smarty->display('index.tpl');
            }
            break;
        case 'login':
            if (!isset($_REQUEST['email']) && !isset($_REQUEST['password']))
                $smarty->display('login.tpl');
            else {
                $email = $_REQUEST['email'];
                $password = $_REQUEST['password'];
                $query = $db->prepare("SELECT id, password FROM user WHERE email = ? LIMIT 1");
                $query->bind_param('s', $email);
                $query->execute();
                $result = $query->get_result();
                $userRow = $result->fetch_assoc();
                $passwordCorrect = password_verify($password, $userRow['password']);
                if ($passwordCorrect) {
                    $smarty->assign('info', "Zalogowano poprawnie");
                    $_SESSION['user_id'] = $userRow['id'];
                    $_SESSION['user_email'] = $email;
                    $smarty->assign('id', $_SESSION['user_id']);
                    $smarty->assign('email', $_SESSION['user_email']);
                    $smarty->display('internal.tpl');
                } else {
                    $smarty->assign('error', "Nieprawidłowy login lub hasło");
                    $smarty->display('index.tpl');
                }
            }
            break;
        default:
            throw new RuntimeException("Nieprawidłowy parametr 'action'");
            break;
    }
} else if (isset($_SESSION['user_id'])) {
    //jesteśmy zalogowani - pokaż stronę wewnętrzną
    $smarty->assign('id', $_SESSION['user_id']);
    $smarty->assign('email', $_SESSION['user_email']);
    $smarty->display('internal.tpl');
} else {
    //nie jesteśmy zalogowani - pokaż stronę startową
    $smarty->display('index.tpl');
}
