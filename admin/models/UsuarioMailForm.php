<?php
/**
 * Created by PhpStorm.
 * User: Casa
 * Date: 10/11/2016
 * Time: 17:30
 */

namespace app\models;


use yii\base\Model;

class UsuarioMailForm extends Model
{
    public $usu_email_atual;
    public $usu_email;
    public $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usu_email', 'usu_email_atual'], 'required'],
            [['usu_email','usu_email_atual'], 'string', 'max' => 50],
            ['usu_email_atual','validateEmail']

        ];
    }

    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Usuario::findByEmail($this->usu_email_atual);

            if (!$user) {
                $this->addError($attribute, 'Email incorreto.');
            }
        }
    }
    public function attributeLabels()
    {
        return [
            'usu_email' => 'Novo Email',
            'usu_email_atual' => 'Email Atual',
        ];
    }


    public function updateEmail(){
        if($this->validate()) {
            $this->_user->usu_email = $this->usu_email;
            $this->_user->save();
            return true;
        }
        return false;
    }

}