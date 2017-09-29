<?php
require_once('model.php');
class modelAccounts extends model{
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
        return 'accounts';
    }
    /**
     * Правила для полей таблицы user
     * @return array
     */
    public function rules(){
        return array(
            array('user_id, account', 'required'),
            array('user_id, account', 'integer'),
        );
    }
    /**
     * Добавление аккаунта
     * @param $data Array
     * @return bool
     */
    public function add($data){

        $this->attributes = $data;
        if($this->valid($data)){
            try{
                $table = $this->getTable();
                $date = date('Y-m-d H:i:s');
                $addAcc = $this->_db->prepare("INSERT INTO $table (user_id, account, added) values
                  (:user_id, :account, '".$date."')");
                $data['user_id'] = (int)$data['user_id'];

                $addAcc->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
                $addAcc->bindParam(':account', $data['account'], PDO::PARAM_INT);

                $result = $addAcc->execute();
            } catch(Exception $e){
                echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
                exit;
            }

            return $result;
        }

        return false;
    }

    protected function valid($data){
        if(!parent::valid($data)){
            return false;
        }

        $modelUser = new modelUsers();
        if(!$modelUser->isUser($data['user_id'])){
            $this->setError('user_id', 'Нет такого пользователя');
        }

        return count($this->errors) < 1;
    }
    /**
     * Список ошибок
     * @return array
     */
    public function getErrors(){
        return $this->errors;
    }
}