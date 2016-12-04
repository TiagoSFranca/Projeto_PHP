<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "visualizacao".
 *
 * @property integer $visu_id
 * @property string $visu_data
 * @property integer $foto_id
 *
 * @property Foto $foto
 */
class Visualizacao extends \yii\db\ActiveRecord
{
    public $quantidade;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visualizacao';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visu_data', 'foto_id'], 'required'],
            [['visu_data'], 'safe'],
            [['foto_id'], 'integer'],
            [['foto_id'], 'exist', 'skipOnError' => true, 'targetClass' => Foto::className(), 'targetAttribute' => ['foto_id' => 'foto_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'visu_id' => 'Visu ID',
            'visu_data' => 'Data de Visualização',
            'foto_id' => 'Foto ID',
        ];
    }

    public static function findByFoto($foto_id){
        $model = Visualizacao::find()->where(['foto_id'=>$foto_id])->all();
        return $model;
    }

    public static function findByFotoWithGroup($foto_id){
        $model = Visualizacao::find()->select(['visu_data,COUNT(*) AS quantidade'])->where(['foto_id'=>$foto_id])->groupBy(['visu_data'])->all();
        return $model;
    }

    public static function findByUser($user_id){
        $model = Visualizacao::find()->innerJoin('foto')->where(['usu_id'=>$user_id])->all();
        return $model;
    }
}
