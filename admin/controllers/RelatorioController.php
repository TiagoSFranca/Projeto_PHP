<?php
/**
 * Created by PhpStorm.
 * User: Casa
 * Date: 20/10/2016
 * Time: 14:36
 */

namespace app\controllers;


use app\models\Foto;
use app\models\RelatorioForm;
use app\models\Usuario;
use app\models\UsuarioDeleteForm;
use app\models\UsuarioForm;
use app\models\UsuarioMailForm;
use app\models\UsuarioPasswordForm;
use app\models\UsuarioUpdateForm;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\web\Controller;
use Yii;

class RelatorioController extends Controller
{

    public function beforeAction($action){
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->ace_id != 1) {
            Yii::$app->user->logout();
            return $this->goHome();
        } else {
            return $action;
        }
    }

    private function verificarLogin(){
        if(Yii::$app->user->identity == null){
            return false;
        }
        return true;
    }

    public function actionListUsuario(){
        if($this->verificarLogin()) {
            $model = new RelatorioForm();
            if ($model->load(Yii::$app->request->post())) {

                $parametro = Yii::$app->getRequest()->getBodyParam('data');
                $tipoUsuario =  Yii::$app->getRequest()->getBodyParam('tipo_usuario');
                $query = $model->listarTodosUsuarios($parametro,$tipoUsuario);
                    $models  = new ArrayDataProvider([
                        'allModels' => $query,
                        'pagination' => [
                            'pageSize' => 10,
                        ],
                    ]);;

                    /*foreach ($models as $usuario) {
                        $usuario->downloads = 0;
                        $usuario->fotos = Foto::findByUser($usuario->usu_id);
                        foreach ($usuario->fotos as $foto) {
                            $usuario->downloads += $foto->getDownloads()->count();
                        }
                        $usuario->fotos = sizeof($usuario->fotos);
                    }*/

                    return $this->render('list-usuario', [
                        'model' => $model,
                        'users' => $models,
                        'op' => $parametro,
                        'opUsuario' => $tipoUsuario,
                    ]);
            } else {
                return $this->render('list-usuario', [
                    'model' => $model,'users'=>false,
                ]);
            }
        }else{
            $this->goHome();
        }
    }

    public function actionSave($lista){
        $pdf = new \File_PDF();
    }
}