<?php
require_once('modelUsers.php');
class Users {

    /**
     * @var modelUsers
     */
    private $model;

    /**
     * ���������� ������
     */
    public function __construct(){
        $this->model = new modelUsers();
    }
    /**
     * ������� ������ ������������� � �� ����������
     * @return array
     */
    public function getUsersWithAccounts(){
        return $this->model->getListDataUsers();
    }
    /**
     * ������� ������ ������������� � �� ����������
     * @return array
     */
    public function getUsers(){
        return $this->model->getListUsers();
    }

    /**
     * @param $data Array
     * @return string json
     */
    public function addUser($data){
        if(!$this->model->add($data)){
            return json_encode(array('status' => 'error', 'errors' => $this->model->getErrors()));
        }

        return json_encode(array('status' => 'ok'));
    }
}