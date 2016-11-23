<?php
/**
 * Created by PhpStorm.
 * User: Casa
 * Date: 20/10/2016
 * Time: 14:36
 */

namespace app\controllers;


use app\models\Download;
use app\models\Foto;
use app\models\LoginForm;
use app\models\Usuario;
use app\models\UsuarioForm;
use app\models\UsuarioMailForm;
use app\models\UsuarioPasswordForm;
use app\models\UsuarioUpdateForm;
use app\models\Visualizacao;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;

class UsuarioController extends Controller
{

    /**
     * Login action.
     *
     * @return string
     */
    public function beforeAction($action){
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->ace_id != 2) {
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

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $tratamento = false;
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if(Yii::$app->user->identity->usu_sexo == 'M'){
                $tratamento = "Seja Bem vindo Sr.";
            }else{
                $tratamento = "Seja Bem vinda Sra.";
            }
            Yii::$app->getSession()->setFlash('sucess',$tratamento.' '.Yii::$app->user->identity->usu_nome);
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionIndex(){
        if ($this->verificarLogin()) {
            $model = Yii::$app->user;
            $teste = Yii::$app->getRequest()->getQueryParam('q');
            if ($teste != null) {
                $modelFoto = Foto::findByLike($teste, $model->getId());
            } else {
                $modelFoto = Foto::findByUser($model->getId());
            }

            foreach ($modelFoto as $foto) {
                $foto->foto_downloads = Download::find()->where(['foto_id' => $foto->foto_id])->count();
                $foto->foto_views = Visualizacao::find()->where(['foto_id' => $foto->foto_id])->count();
            }
            return $this->render('index', [
                'model' => $model, 'modelFoto' => $modelFoto, 'teste' => $teste
            ]);
        }else{
            $this->goHome();
        }
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
                Yii::$app->getSession()->setFlash('sucess', 'Cadastro realizado Com Sucesso.');
                return $this->redirect(["/usuario/index"]);
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
        $model = new UsuarioForm();
        if ($model->load(Yii::$app->request->post()) && $model->criarUsuario()) {
            Yii::$app->getSession()->setFlash('sucess', 'Cadastro realizado Com Sucesso.');
            return $this->redirect(["/usuario/login"]);
        }else {
            $model->usu_sexo = 'M';
            return $this->render('create', [
                'model' => $model,
            ]);
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