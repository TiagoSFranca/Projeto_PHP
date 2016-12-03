<?php
/**
 * Created by PhpStorm.
 * User: Casa
 * Date: 20/10/2016
 * Time: 15:08
 */

namespace app\models;


use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use Yii;

class FotoForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $foto_caminho;
    public $foto_nome;
    public $usu_id;
    public $foto_tag;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['foto_nome','usu_id'], 'required'],
            [['usu_id'], 'integer'],
            [['foto_nome'], 'string', 'max' => 32],
            [['foto_caminho'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['foto_tag'], 'string', 'max' => 20, 'min' => 3]

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'foto_nome' => 'Nome',
            'foto_caminho' => 'Imagem',
            'foto_downloads' => 'Numero de Downloads',
            'foto_tag'=>'Tags',
            'usu_id' => 'Usuario ID',
        ];
    }

    /*
     * OPCAO
     * 1 - USUARIO COMUM;
     * 2 - ADMIN;
     * 3 -
     * */

    private function tratarNomeFoto(){
        $row = Foto::findBySql("SELECT * FROM foto ORDER BY foto_id DESC LIMIT 1")->one();
        return $row['foto_id']+1;
    }

    public function enviarFoto(){
            if($this->validate()) {
                $foto = new Foto();
                $foto->usu_id = $this->usu_id;
                $foto->foto_nome = $this->foto_nome;
                $foto->foto_tag = $this->foto_tag;
                $path = getdate();
                $dia = $path["mday"];
                $mes = $path["mon"];
                $ano = $path["year"];
                $path = "/uploads/$ano/$mes/$dia/";
                FileHelper::createDirectory(Yii::getAlias('@app').$path);
                $this->foto_caminho->saveAs(Yii::getAlias('@app').$path. $this->tratarNomeFoto() . '.' . $this->foto_caminho->extension);
                $foto->foto_caminho = $path . $this->tratarNomeFoto() . '.' . $this->foto_caminho->extension;
                $foto->foto_data_upload = date('y-m-d');;
                $foto->save();
                return true;
            }
            return false;
    }


}