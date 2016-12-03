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
            'visu_data' => 'Visu Data',
            'foto_id' => 'Foto ID',
        ];
    }


    public static function findByFoto($foto_id){
        $model = Visualizacao::find()->where(['foto_id'=>$foto_id])->all();
        return $model;
    }
}
