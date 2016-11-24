<?php
/**
 * Created by PhpStorm.
 * User: Casa
 * Date: 10/11/2016
 * Time: 16:49
 */

namespace app\models;


use yii\base\Model;

class UsuarioDeleteForm extends Model
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
            [['senha_atual',  'usu_confirm_senha'], 'required'],
            [['usu_confirm_senha','senha_atual'], 'string', 'max' => 20, 'min' => 8],
            ['usu_confirm_senha', 'compare', 'compareAttribute'=>'senha_atual','message'=>'Senhas Diferentes'],
            ['senha_atual', 'validatePassword']

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

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'senha_atual' => 'Senha Atual',
            'usu_confirm_senha'=>'Confirmar Senha',
        ];
    }



    public function deletarUsuario(){
        if($this->validate()) {
            $fotos = Foto::findByUser($this->_user->usu_id);
            foreach ($fotos as $foto){
                Download::deleteAll(['foto_id'=>$foto->foto_id]);
                Visualizacao::deleteAll(['foto_id'=>$foto->foto_id]);
                $foto->delete();
            }
            $this->_user->delete();
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