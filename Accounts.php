<?php
require_once('modelAccounts.php');
/**
 * Class Accounts
 */
class Accounts {
    /**
     * @var modelAccounts
     */
    private $model;

    /**
     * инициируем модель
     */
    public function __construct(){
        $this->model = new modelAccounts();
    }

    /**
     * Добавление аккаунта
     * @param $data Array
     * @return string
     */
    public function addAcc($data){
        if(!$this->model->add($data)){
            return json_encode(array('status' => 'error', 'errors' => $this->model->getErrors()));
        }

        return json_encode(array('status' => 'ok'));
    }
}