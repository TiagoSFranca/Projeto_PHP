<?php
/**
 * Created by PhpStorm.
 * User: Casa
 * Date: 20/10/2016
 * Time: 14:36
 */

namespace app\controllers;


use app\models\Usuario;
use app\models\UsuarioForm;
use app\models\UsuarioMailForm;
use app\models\UsuarioPasswordForm;
use app\models\UsuarioUpdateForm;
use yii\web\Controller;
use Yii;

class UsuarioController extends Controller
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

    public function actionConfig(){
        if($this->verificarLogin()) {
            $user = $this->findModel(Yii::$app->user->identity->usu_login);
            $model = new UsuarioForm();
            $model->usu_login = $user->usu_login;
            $model->usu_nome = $user->usu_nome;
            $model->usu_data_nascimento = $user->usu_data_nascimento;
            $model->usu_sexo = $user->usu_sexo;
            $model->_user = $user;
            if ($model->load(Yii::$app->request->post()) && $model->atualizarUsuario()) {
                Yii::$app->getSession()->setFlash('sucess', 'Dados alterados Com Sucesso.');
                $this->goHome();
            } else {
                return $this->render('config', [
                    'model' => $model,
                ]);
            }
        }else{
            $this->goHome();
        }
    }
    public function actionCreate()
    {
        if($this->verificarLogin()) {
            $model = new UsuarioForm();
            if ($model->load(Yii::$app->request->post()) && $model->criarUsuario()) {
                //Yii::$app->set
                Yii::$app->user->logout();
                $this->goHome();
            } else {
                $model->usu_sexo = 'M';
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }else{
            $this->goHome();
        }
    }

    public function actionPassword(){
        if($this->verificarLogin()) {
            $model = new UsuarioPasswordForm();
            $model->usu_id = Yii::$app->user->identity->getId();
            if ($model->load(Yii::$app->request->post()) && $model->atualizarSenha()) {
                Yii::$app->getSession()->setFlash('sucess', 'Senha Alterada Com Sucesso.');
                return $this->redirect(["/usuario/index"]);
            } else {
                return $this->render('password', [
                    'model' => $model,
                ]);
            }
        }else{
            $this->goHome();
        }
    }


    public function actionMail(){
        if($this->verificarLogin()) {
            $user = $this->findModel(Yii::$app->user->identity->usu_login);
            $model = new UsuarioMailForm();
            $model->_user = $user;
            if ($model->load(Yii::$app->request->post()) && $model->atualizarEmail()) {
                Yii::$app->getSession()->setFlash('sucess', 'Senha Alterada Com Sucesso.');
                return $this->redirect(["/usuario/index"]);
            } else {
                return $this->render('mail', [
                    'model' => $model,
                ]);
            }
        }else{
            $this->goHome();
        }
    }
    protected function findModel($usuario)
    {
        if (($model = Usuario::findByUsername($usuario)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}