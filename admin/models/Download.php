<?php

namespace app\models;


/**
 * This is the model class for table "download".
 *
 * @property integer $down_id
 * @property integer $foto_id_id
 * @property string $down_data
 *
 * @property Foto $foto
 */
class Download extends \yii\db\ActiveRecord
{

    public $quantidade;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'download';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['foto_id', 'down_data'], 'required'],
            [['foto_id'], 'integer'],
            [['down_data'], 'safe'],
            [['foto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Foto::className(), 'targetAttribute' => ['foto_id' => 'foto_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'down_id' => 'Down ID',
            'foto_id' => 'Foto ID',
            'down_data' => 'Data de Download',
        ];
    }



    public static function findByFoto($foto_id){
        $model = Download::find()->where(['foto_id'=>$foto_id])->andWhere(['<>','down_data','0000-00-00'])->all();
        return $model;
    }

    public static function findByFotoWithGroup($foto_id){
        $model = Download::find()->select(['down_data,COUNT(*) AS quantidade'])->where(['foto_id'=>$foto_id])->andWhere(['<>','down_data','0000-00-00'])->groupBy(['down_data'])->all();
        return $model;
    }

    public static function findByUser($user_id){
        $model = Download::find()->innerJoin('foto')->andWhere(['<>','down_data','0000-00-00'])->where(['usu_id'=>$user_id])->all();
        return $model;
    }
}
