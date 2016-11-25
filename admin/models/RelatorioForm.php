<?php
/**
 * Created by PhpStorm.
 * User: Casa
 * Date: 20/10/2016
 * Time: 15:08
 */

namespace app\models;


use yii\base\Model;

class RelatorioForm extends Model
{

    public $usu_data_inicial;
    public $usu_data_final;

    public function rules()
    {
        return [
            [['usu_data_final'], 'required'],
            [['usu_data_inicial', 'usu_data_final'], 'safe'],
            ['usu_data_final', 'compare', 'compareAttribute'=>'usu_data_inicial','message'=>'Data Final não deve ser menor que a inicial', 'operator'=>'>'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'usu_data_inicial' => 'Data Inicial',
            'usu_data_final' => 'Data Final',
        ];
    }

    /*
     * PARAMETROS
     * 1 - DATA DE NASCIMENTO
     * 2- DATA DE CADASTRO
    */
    public function listarTodosUsuarios($parametro){
        if($this->validate()) {
            $usuarios =  Usuario::find()->where(['ace_id'=>2]);
            if ($parametro == 1){
                $usuarios =  Usuario::find()->where(['ace_id'=>2]);
                    $usuarios->andFilterCompare('usu_data_nascimento','<='.$this->usu_data_final);
               if($this->usu_data_inicial != 0) {
                   $usuarios->andFilterCompare('usu_data_nascimento', '>='.$this->usu_data_inicial);
                }
            }else{
                   $usuarios->andFilterCompare('usu_data_cadastro','<='.$this->usu_data_final);
                if($this->usu_data_inicial != 0) {
                    $usuarios->andFilterCompare('usu_data_cadastro', '>='.$this->usu_data_inicial);
                }
            }
            return $usuarios->all();
        }
        return false;
    }

}