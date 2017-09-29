<?php
require_once('Db.php');
class model {

    protected $_db = null;
    public $attributes = array();
    /**
     * инициируем подключение к БД
     */
    public function __construct(){
        $this->_db = Db::instance();
    }


    /**
     * Проверка полей являются ли они пустыми
     * @param $fields string
     */
    private function isRequired($fields){
        $fieldsArr = $this->trimExplode(',', $fields);
        foreach($fieldsArr as $field){
            if(empty(trim($this->attributes[$field]))){
                $this->setError($field, 'Поле не должно быть пустым');
            }
        }
    }

    /**
     * Проверка полей являются ли они Email
     * @param $fields string
     */
    private function isEmail($fields){
        $fieldsArr = $this->trimExplode(',', $fields);
        foreach($fieldsArr as $field){
            if (!filter_var($this->attributes[$field], FILTER_VALIDATE_EMAIL)) {
                $this->setError($field, 'Не корректное email');
            }
        }
    }

    /**
     * проверка являются ли указаные поля целым число
     * @param $fields string
     */
    private function isInteger($fields){
        $fieldsArr = $this->trimExplode(',', $fields);
        foreach($fieldsArr as $field){
            if (!(is_numeric($this->attributes[$field]) && is_int($this->attributes[$field] + 0))) {
                $this->setError($field, 'Не корректное значение');
            }
        }
    }

    /**
     * Проверка полей по заданым правилам на валидность
     * @param $data
     * @return bool
     */
    protected function valid($data){

        $rules = $this->rules();
        foreach($rules as $rule){
            switch($rule[1]){
                case 'required':
                    $this->isRequired($rule[0]);
                    break;
                case 'email':
                    $this->isEmail($rule[0]);
                    break;
                case 'unique':
                    $type = isset($rule[2]) ? $rule[2] : PDO::PARAM_STR;
                    $this->isUnique($rule[0], $type);
                    break;
                case 'integer':
                    $this->isInteger($rule[0]);
                    break;
            }
        }
        return count($this->errors) < 1;
    }

    /**
     * Проверяет наличие такого значения в таблице
     * @param $fields
     * @param $type
     */
    private function isUnique($fields, $type){
        $fieldsArr = $this->trimExplode(',', $fields);
        $table = $this->getTable();
        foreach($fieldsArr as $field){
            $res = $this->_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = :$field");
            $res->bindParam(":".$field, $this->attributes[$field], $type);
            $res->execute();
            $countFields = $res->fetchColumn();
            if($countFields > 0){
                $this->setError($field, 'Уже есть такое название');
            }
        }
    }

    /**
     * Убираем пробелы в начале и конце строки значений
     * @param $delimiter
     * @param $string
     * @return array
     */
    private function trimExplode($delimiter, $string){
        $arr = explode(',', $string);
        array_walk($arr, function(&$value){
            $value = trim($value);
        });
        return $arr;
    }

    /**
     * Записуем новою ошибку
     * @param $key
     * @param $val
     */
    public function setError($key, $val){
        if(!isset($this->errors[$key])){
            $this->errors[$key] = $val;
        }
    }
}