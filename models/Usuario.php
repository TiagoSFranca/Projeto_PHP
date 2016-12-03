<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario".
 *
 * @property integer $usu_id
 * @property string $usu_nome
 * @property string $usu_login
 * @property string $usu_senha
 * @property string $usu_email
 * @property string $usu_data_nascimento
 * @property string $usu_sexo
 * @property string $usu_data_cadastro
 * @property integer $ace_id
 *
 * @property Foto[] $fotos
 * @property Acesso $ace
 */
class Usuario extends \yii\db\ActiveRecord implements  \yii\web\IdentityInterface
{
    public static function tableName()
    {
        return 'usuario';
    }

    public function rules()
    {
        return [
            [['usu_nome', 'usu_login', 'usu_senha', 'usu_email', 'usu_data_nascimento', 'usu_sexo', 'usu_data_cadastro', 'ace_id'], 'required'],
            [['usu_data_nascimento', 'usu_data_cadastro'], 'safe'],
            [['ace_id'], 'integer'],
            [['usu_nome'], 'string', 'max' => 60],
            [['usu_login'], 'string', 'max' => 25],
            [['usu_senha'], 'string', 'max' => 32],
            [['usu_email'], 'string', 'max' => 50],
            [['usu_sexo'], 'string', 'max' => 1],
            [['ace_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acesso::className(), 'targetAttribute' => ['ace_id' => 'ace_id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'usu_id' => 'Usu ID',
            'usu_nome' => 'Nome',
            'usu_login' => 'Usu Login',
            'usu_senha' => 'Usu Senha',
            'usu_email' => 'Usu Email',
            'usu_data_nascimento' => 'Usu Data Nascimento',
            'usu_sexo' => 'Usu Sexo',
            'ace_id' => 'Ace ID',
            'usu_data_cadastro' => 'Usu Data Cadastro',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException(["Não possui"]);
    }

    public function getId()
    {
        return $this->usu_id;
    }

    public function getAuthKey()
    {
    }

    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException(["Não possui"]);
    }


    public static function findByUsername($usuario,$acesso = null){
        if($acesso != null) {
            return self::findOne(['usu_login' => $usuario, 'ace_id' => $acesso]);
        }else{
            return self::findOne(['usu_login' => $usuario]);
        }
    }

    public static function findByEmail($email){
        return self::findOne(['usu_email' => $email]);
    }

    public function validatePassword($senha){
        return $this->usu_senha === md5($senha);
    }

}
