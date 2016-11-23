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
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFotos()
    {
        return $this->hasMany(Foto::className(), ['usu_id' => 'usu_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAce()
    {
        return $this->hasOne(Acesso::className(), ['ace_id' => 'ace_id']);
    }

    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException(["Não possui"]);
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->usu_id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
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
