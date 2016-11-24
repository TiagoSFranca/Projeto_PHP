<?php
/**
 * Created by PhpStorm.
 * User: Casa
 * Date: 10/11/2016
 * Time: 16:49
 */

namespace app\models;


use yii\base\Model;

class UsuarioPasswordForm extends Model
{
    public $senha_atual;
    public $usu_senha;
    public $usu_confirm_senha;
    public $usu_id;
    private $_user = false;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['senha_atual', 'usu_senha', 'usu_confirm_senha'], 'required'],
            [['usu_senha','usu_confirm_senha','senha_atual'], 'string', 'max' => 20, 'min' => 8],
            ['usu_confirm_senha', 'compare', 'compareAttribute'=>'usu_senha','message'=>'Senhas Diferentes'],
            ['usu_senha', 'compare', 'compareAttribute'=>'senha_atual','message'=>'Nova Senha não pode ser igual à Antiga', 'operator'=>'!='],
            ['usu_senha','validateSenha'],
            ['senha_atual', 'validatePassword'],
            [ 'usu_senha', 'match', 'not' => true, 'pattern' => '/[^0-9!@#$%+.&*a-zA-Z_-]/',
                'message' => 'Apenas [ 0-9 ,a-z , A-Z , _ , - , ! , @ , # , $ , % , + , . , & , *]']

        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->senha_atual)) {
                $this->addError($attribute, 'Senha Incorreta');
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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usu_senha' => 'Nova Senha',
            'senha_atual' => 'Senha Atual',
            'usu_confirm_senha'=>'Confirmar Senha',
        ];
    }



    public function deletarUsuario(){
        if($this->validate()) {
            if($this->_user->usu_senha == md5($this->usu_senha)) {
                $fotos = Foto::findByUser($this->_user->usu_id);
                foreach ($fotos as $foto){
                    Download::deleteAll(['foto_id'=>$foto->foto_id]);
                    Visualizacao::deleteAll(['foto_id'=>$foto->foto_id]);
                    $foto->delete();
                }
                $this->_user->delete();
                return true;
            }
        }
        return false;
    }

    public function atualizarSenha(){
        if($this->validate()) {
            $this->_user->usu_senha = md5($this->usu_senha);
            $this->_user->save();
            return true;
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Usuario::findOne($this->usu_id);// 2 - COMMON USER
        }

        return $this->_user;
    }

}