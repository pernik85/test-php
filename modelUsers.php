<?php
require_once('model.php');
class modelUsers extends model{
    /**
     * Хранилище ошибок
     * @var array
     */
    public $errors = array();
    /**
     * Имя таблицы
     * @return string
     */
    public function getTable(){
        return 'users';
    }
    /**
     * Правила для полей таблицы user
     * @return array
     */
    public function rules(){
        return array(
            array('usr_name, usr_email, usr_address', 'required'),
            array('usr_email', 'email'),
            array('usr_email, usr_name', 'unique', PDO::PARAM_STR)
        );
    }
    /**
     * Добавляем нового пользователя
     * @param $data Array
     * @return bool
     */
    public function add($data){

        $this->attributes = $data;
        if($this->valid($data)){
            $table = $this->getTable();
            $newUser = $this->_db->prepare("INSERT INTO $table (usr_name, usr_email, usr_address) values
              (:usr_name, :usr_email, :usr_address)");

            $newUser->bindParam(':usr_name', $data['usr_name'], PDO::PARAM_STR);
            $newUser->bindParam(':usr_email', $data['usr_email'], PDO::PARAM_STR);
            $newUser->bindParam(':usr_address', $data['usr_address'], PDO::PARAM_STR);

            return $newUser->execute();
        }

        return false;
    }

    /**
     * Наличие пользователя
     * @param $id
     * @return bool
     */
    public function isUser($id){
        $countUsers = $this->_db->query("SELECT COUNT(*) FROM users WHERE id = ".(int)$id)->fetchColumn();
        return $countUsers > 0;
    }

    /**
     * Получаем список данных пользователе из БД
     * @return array
     */
    public function getListDataUsers(){
        $users = $this->_db->query('SELECT users.*, users.id as uusers_id, accounts.account, accounts.added FROM '.$this->getTable().' LEFT JOIN accounts ON(users.id = accounts.user_id)');
        return $users->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Получаем список пользователе из БД
     * @return array
     */
    public function getListUsers(){
        $users = $this->_db->query('SELECT * FROM '.$this->getTable());
        return $users->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Список ошибок
     * @return array
     */
    public function getErrors(){
        return $this->errors;
    }
}