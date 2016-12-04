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

    public $data_inicial;
    public $data_final;

    public function rules()
    {
        return [
            [['data_final'], 'required'],
            [['data_inicial', 'data_final'], 'safe'],
            ['data_final', 'compare', 'compareAttribute'=>'data_inicial','message'=>'Data Final não deve ser menor que a inicial', 'operator'=>'>'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'data_inicial' => 'Data Inicial',
            'data_final' => 'Data Final',
        ];
    }

    /*
     * PARAMETROS
     * 1 - DATA DE NASCIMENTO
     * 2- DATA DE CADASTRO
    */
    public function listAllUsers($param,$typeUser,$filter,$sort){
        if($this->validate()) {
            $query =  Usuario::find()->where(['ace_id'=>$typeUser]);
                    $query->andFilterCompare($param,'<='.$this->data_final);
               if($this->data_inicial != 0) {
                   $query->andFilterCompare($param, '>='.$this->data_inicial);
                }
            $usuarios = $query->orderBy($filter.' '.$sort)->all();
            foreach ($usuarios as $usuario) {
                $usuario->downloads = sizeof(Download::findByUser($usuario->usu_id));
                $usuario->fotos = sizeof(Foto::findByUser($usuario->usu_id));
                $usuario->visualizacoes = sizeof(Visualizacao::findByUser($usuario->usu_id));
            }
            return $usuarios;
        }
        return false;
    }

    public function listAllPictures($sort,$filter){
        if($this->validate()) {
            $query = Foto::find()->andFilterCompare('foto_data_upload', '<=' . $this->data_final);
            if ($this->data_inicial != 0) {
                $query->andFilterCompare('foto_data_upload', '>=' . $this->data_inicial);
            }

            $fotos = $query->orderBy($filter.' '.$sort)->all();
            foreach ($fotos as $foto) {
                $foto->downloads = sizeof(Download::findByFoto($foto->foto_id));
                $foto->visualizacoes = sizeof(Visualizacao::findByFoto($foto->foto_id));
                $foto->usu_login = Usuario::findIdentity($foto->usu_id)->usu_login;
            }
            return $fotos;
        }
        return false;
    }

    public function listAllViews($sort,$filter){
        if($this->validate()) {
            $query = Visualizacao::find()->select(['visu_data,COUNT(*) AS quantidade'])->andFilterCompare('visu_data', '<=' . $this->data_final);
            if ($this->data_inicial != 0) {
                $query->andFilterCompare('visu_data', '>=' . $this->data_inicial);
            }

            $views = $query->groupBy('visu_data')->orderBy($filter.' '.$sort)->all();
            return $views;
        }
        return false;
    }

    public function listAllDownloads($sort,$filter){
        if($this->validate()) {
            $query = Download::find()->select(['down_data,COUNT(*) AS quantidade'])->andFilterCompare('down_data', '<=' . $this->data_final);
            if ($this->data_inicial != 0) {
                $query->andFilterCompare('down_data', '>=' . $this->data_inicial);
            }

            $downs = $query->andWhere(['<>','down_data','0000-00-00'])->groupBy('down_data')->orderBy($filter.' '.$sort)->all();
            return $downs;
        }
        return false;
    }



    public function listAllPicturesOfUser($id_user){
        $fotos = Foto::findByUser($id_user);
        foreach ($fotos as $foto) {
            $foto->downloads = sizeof(Download::findByFoto($foto->foto_id));
            $foto->visualizacoes = sizeof(Visualizacao::findByFoto($foto->foto_id));
        }
        return $fotos;
    }

    public static function getPicture($id_picture){
        $foto = Foto::findOne(['foto_id'=>$id_picture]);
        $foto->downloads = Download::findByFotoWithGroup($id_picture);
        $foto->visualizacoes = Visualizacao::findByFotoWithGroup($id_picture);
        $foto->usu_login = Usuario::findOne(['usu_id'=>$foto->usu_id]);
        return $foto;
    }
}