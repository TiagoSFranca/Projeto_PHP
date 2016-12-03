<?php

namespace app\models;


/**
 * This is the model class for table "foto".
 *
 * @property integer $foto_id
 * @property string $foto_nome
 * @property integer $usu_id
 * @property string $foto_caminho
 * @property string $foto_tag
 * @property string $foto_data_upload
 *
 * @property Download[] $downloads
 * @property Usuario $usu
 * @property Visualizacao[] $visualizacaos
 */
class Foto extends \yii\db\ActiveRecord
{
    public $visualizacoes;
    public $downloads;
    public $usu_login;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'foto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['foto_nome', 'usu_id', 'foto_caminho', 'foto_data_upload'], 'required'],
            [['usu_id'], 'integer'],
            [['foto_data_upload'], 'safe'],
            [['foto_caminho'], 'string', 'max' => 100],
            [['foto_nome'], 'string', 'max' => 32],
            [['foto_tag'], 'string', 'max' => 200],
            [['usu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::className(), 'targetAttribute' => ['usu_id' => 'usu_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'foto_id' => 'ID',
            'foto_nome' => 'Nome',
            'usu_id' => 'Usu ID',
            'foto_caminho' => 'Caminho',
            'foto_tag' => 'Tag',
            'foto_data_upload' => 'Data Upload',
            'downloads'=>'Downloads',
            'visualizacoes'=>'Visualizações',
            'usu_login' => 'Usuário'
        ];
    }

    public static function findByUser($user_id){
        $model = Foto::find()->where(['usu_id' =>$user_id])->orderBy('foto_nome')->all();
        return $model;
    }

    public static function findByLike($param,$user_id = null)
    {
        $query = Foto::find()->andFilterWhere(['like', 'foto_nome', $param])
            ->orFilterWhere(['like', 'foto_tag', $param])->all();
        if($user_id != null)
            $query = Foto::findBySql("SELECT * FROM foto where usu_id = $user_id and (foto_nome like '%$param%' or foto_tag like '%$param%');")->orderBy(['foto_downloads' => SORT_DESC])->all();

        return $query;
    }
}
