<?php
/**
 * Created by PhpStorm.
 * User: Casa
 * Date: 20/10/2016
 * Time: 15:08
 */

namespace app\models;


use yii\base\Model;

class UsuarioForm extends Model
{
    public $usu_nome;
    public $usu_data_nascimento;
    public $ace_id;
    public $usu_login;
    public $usu_senha;
    public $usu_confirm_senha;
    public $usu_email;
    public $usu_sexo;
    public $_user = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usu_nome', 'usu_login', 'usu_senha', 'usu_email', 'usu_data_nascimento', 'usu_sexo'], 'required'],
            [['usu_data_nascimento'], 'safe'],
            [['ace_id'], 'integer'],
            [['usu_nome'], 'string', 'max' => 60],
            [['usu_login'], 'string', 'max' => 25, 'min' => 5],
            [['usu_senha'], 'string', 'max' => 20, 'min' => 8],
            [['usu_confirm_senha'], 'string', 'max' => 20, 'min' => 8],
            ['usu_confirm_senha', 'compare', 'compareAttribute'=>'usu_senha','message'=>'Senhas Diferentes'],
            [['usu_email'], 'string', 'max' => 50],
            [['usu_sexo'], 'string', 'max' => 1],
            ['usu_login','validateLogin'],
            ['usu_senha','validateSenha'],
            ['usu_email','validateEmail'],
            [ 'usu_login', 'match', 'not' => true, 'pattern' => '/[^0-9!@#$%+.&*a-zA-Z_-]/',
                'message' => 'Apenas [ 0-9 ,a-z , A-Z , _ , - , ! , @ , # , $ , % , + , . , & , *]'],
            [ 'usu_senha', 'match', 'not' => true, 'pattern' => '/[^0-9!@#$%+.&*a-zA-Z_-]/',
                'message' => 'Apenas [ 0-9 ,a-z , A-Z , _ , - , ! , @ , # , $ , % , + , . , & , *]']

        ];
    }

    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Usuario::findByEmail($this->usu_email);

            if ($user) {
                $this->addError($attribute, 'Email já Cadastrado');
            }
        }
    }
    public function validateSenha($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if(substr_count($this->usu_senha,' ') >=1){
                $this->addError($attribute, 'Não pode conter espaços em branco');
            }
        }
    }

    public function validateLogin($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if(substr_count($this->usu_login,' ') >=1){
                $this->addError($attribute, 'Não pode conter espaços em branco');
            }
            $user = Usuario::findByUsername($this->usu_login);
            if ($user) {
                $this->addError($attribute, 'Usuario já Cadastrado');
            }
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usu_id' => 'Usu ID',
            'usu_nome' => 'Nome',
            'usu_login' => 'Login',
            'usu_senha' => 'Senha',
            'usu_email' => 'Email',
            'usu_data_nascimento' => 'Data Nascimento',
            'usu_sexo' => 'Sexo',
            'usu_confirm_senha'=>'Repetir Senha',
            'ace_id' => 'Ace ID',
        ];
    }

    /*
     * OPCAO
     * 1 - USUARIO COMUM;
     * 2 - ADMIN;
     * 3 -
     * */

    public function criarUsuario(){
            if($this->validate()) {
                $this->ace_id = 1;
                $usu = new Usuario();
                $usu->usu_nome = $this->usu_nome;
                $usu->usu_login = $this->usu_login;
                $usu->usu_senha = md5($this->usu_senha);
                $usu->usu_email = $this->usu_email;
                $usu->usu_data_nascimento = $this->usu_data_nascimento;
                $usu->usu_sexo = $this->usu_sexo;
                $usu->usu_data_cadastro = date('y-m-d');
                $usu->ace_id = $this->ace_id;
                $usu->save();
                return true;
            }
            return false;
    }

    public function atualizarUsuario(){
            $this->_user->usu_nome = $this->usu_nome;
            $this->_user->usu_data_nascimento = $this->usu_data_nascimento;
            $this->_user->usu_sexo = $this->usu_sexo;
            $this->_user->save();
            return true;
    }

}