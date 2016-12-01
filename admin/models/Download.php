<?php

namespace app\models;


/**
 * This is the model class for table "download".
 *
 * @property integer $down_id
 * @property integer $foto_id
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFoto()
    {
        return $this->hasOne(Foto::className(), ['foto_id' => 'foto_id']);
    }


    public static function findByFoto($foto){
        $model = Download::find()->where(['foto_id'=>$foto])->all();
        return $model;
    }




    public static function findByFotoWithGroup($foto){
        $model = Download::find()->select(['down_data,COUNT(*) AS quantidade'])->where(['foto_id'=>$foto])->groupBy(['down_data'])->all();
        return $model;
    }

    public static function findByUser($usuario){
        $model = Download::find()->innerJoin('foto')->where(['usu_id'=>$usuario])->all();
        return $model;
    }
}
