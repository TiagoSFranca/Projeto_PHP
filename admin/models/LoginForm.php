<?php

namespace app\models;

use Yii;
use yii\base\Model;


class LoginForm extends Model
{
    public $username;
    public $password;
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            [['username'], 'string', 'max' => 25, 'min' => 5],
            [['password'], 'string', 'max' => 20, 'min' => 8],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Usuário',
            'password' => 'Senha',
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Usuário ou Senha Incorretos.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), 0);
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Usuario::findByUsername($this->username,1);// 2 - COMMON USER
        }

        return $this->_user;
    }
}
