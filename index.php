<?php
require_once('Users.php');
require_once('Accounts.php');
$users = new Users();
$listDataUsers = $users->getUsersWithAccounts();
$listUsers = $users->getUsers();

if(isset($_POST['User'])){
     $result = $users->addUser($_POST['User']);
    echo json_encode($result);
    exit;
}

if(isset($_POST['Accounts'])){
    $accounts = new Accounts();
     $result = $accounts->addAcc($_POST['Accounts']);
    echo json_encode($result);
    exit;
}

if(isset($_GET['refreshListUsers'])){
    $result = array();
    if($_GET['refreshListUsers'] == 'data'){
        $result = $users->getUsersWithAccounts();
    } elseif($_GET['refreshListUsers'] == 'list'){
        $result = $users->getUsers();
    }

    echo json_encode($result);
    exit;
}

function pr()
{
    $args = func_get_args();
    if ( !empty($args))
    {
        foreach($args as $a)
        {
            echo '<pre>';
            print_r($a);
            echo '</pre>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="jquery.min.js"></script>
    <script src="users.js"></script>
    <title></title>
    <style>
        body {
            margin: 0px;
        }
        .error{
            color:red
        }
    </style>
</head>
<body>
    <div style="border: solid 1px black; width: 200px">
        <p>
            Добавить пользователя
        </p>
        <form id="form-user">
            Имя<br>
            <input name="User[usr_name]" type="text">
            <p id="error-usr_name" class="error"></p>
            Email<br>
            <input name="User[usr_email]" type="text">
            <p id="error-usr_email" class="error"></p>
            Адресс<br>
            <input name="User[usr_address]" type="text">
            <p id="error-usr_address" class="error"></p>
            <button class="ajax-save">Сохранить</button>
        </form>
    </div>
    <div style="border: solid 1px black; width: 200px">
        <p>
            Добавить аккаунт
        </p>
        <form>
            Aккаунт<br>
            <input name="Accounts[account]" type="text">
            <p class="error" id="error-account"></p>
            Пользователь<br>
            <select id="Accounts-user_id"  name="Accounts[user_id]" type="text">
                <option value="0">Выберите пользователя</option>
                <?php foreach($listUsers as $user):?>
                    <option value="<?=$user['uusers_id']?>"><?=$user['usr_name']?></option>
                <?php  endforeach; ?>
            </select>
            <p class="error" id="error-user_id"></p>
            <button class="ajax-save">Сохранить</button>
        </form>
    </div>
    <table cellpadding="1" cellspacing="1" border="1" id="list-users">
        <thead>
            <tr>
                <td>
                    Name
                </td>
                <td>
                    Email
                </td>
                <td>
                    Address
                </td>
                <td>
                    accounts
                </td>
                <td>
                    Date added
                </td>
            </tr>
        </thead>
        <tbody>
            <?php foreach($listDataUsers as $user):?>
                <tr>
                    <td>
                        <?=$user['usr_name']; ?>
                    </td>
                    <td>
                        <?=$user['usr_email']; ?>
                    </td>
                    <td>
                        <?=$user['usr_address']; ?>
                    </td>
                    <td>
                        <?=$user['account']; ?>
                    </td>
                    <td>
                        <?=$user['added']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>