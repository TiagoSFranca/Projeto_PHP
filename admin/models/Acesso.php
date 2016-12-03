<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "acesso".
 *
 * @property integer $ace_id
 * @property string $ace_descricao
 *
 * @property Usuario[] $usuarios
 */
class Acesso extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acesso';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ace_descricao'], 'required'],
            [['ace_descricao'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ace_id' => 'Ace ID',
            'ace_descricao' => 'Ace Descricao',
        ];
    }
}
