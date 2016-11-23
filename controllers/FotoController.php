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
use app\models\FotoForm;
use app\models\UsuarioUpdateForm;
use app\models\Visualizacao;
use yii\web\Controller;
use Yii;
use yii\web\UploadedFile;

class FotoController extends Controller
{



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

    public function actionSend()
    {
        if ($this->verificarLogin()) {
            $model = new FotoForm();
            $model->usu_id = Yii::$app->user->identity->getId();
            if ($model->load(Yii::$app->request->post())) {
                $model->foto_caminho = UploadedFile::getInstance($model, 'foto_caminho');
                if ($model->enviarFoto()) {
                    Yii::$app->getSession()->setFlash('sucess', 'Upload realizado Com Sucesso.');
                    return $this->redirect(["/usuario/index"]);
                }
            } else {
                return $this->render('send', [
                    'model' => $model,
                ]);
            }
        }else{
            $this->goHome();
        }
    }

    public function actionUpdate($id)
    {
        if ($this->verificarLogin()) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->getSession()->setFlash('sucess', 'Edição realizado Com Sucesso.');
                return $this->redirect(["/usuario/index"]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }else{
            $this->goHome();
        }
    }


    public function actionSee($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->user->getId() !== $model->usu_id || Yii::$app->user->isGuest){
            $modelSee = new Visualizacao();
            $modelSee->visu_data = date('y-m-d');
            $modelSee->foto_id = $model->foto_id;
            $modelSee->save();
        }
        return $this->render('see', [
            'model' => $model,
        ]);
    }
    public function actionDelete($id)
    {
        if ($this->verificarLogin()) {
            unlink(Yii::getAlias('@app') . $this->findModel($id)->foto_caminho);
            $this->findModel($id)->delete();
            Yii::$app->getSession()->setFlash('sucess', 'Exclusão realizada Com Sucesso.');
        }else{
            $this->goHome();
        }

    }

    private function redirecionar(){
        /*$foto->foto_downloads += 1;
        $foto->save();
        Yii::$app->getSession()->setFlash('sucess', 'Download realizado Com Sucesso.');*/
        return $this->redirect(["/site/index"]);
    }

    public function actionDownload($id){
    $path = Yii::getAlias('@app');
    $foto  = $this->findModel($id);
    $file = $path .$foto->foto_caminho;

    if (file_exists($file)) {
        //ini_set('max_execution_time', 5*60);
        //Yii::$app->response->SendFile($file)->send();
        Yii::$app->response->sendFile($file)->on(\yii\web\Response::EVENT_AFTER_SEND, function($event) {
            /*$foto->foto_downloads += 1;
            $foto->save();*/
            Yii::$app->getSession()->setFlash('sucess', 'Download realizado Com Sucesso.');
            $this->redirecionar();
    }, $file);
            $download = new Download();
            $download->foto_id = $foto->foto_id;
            $download->down_data = date('y-m-d');
            $download->save();
        }
    }

    protected function findModel($id)
    {
        if (($model = Foto::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Não encontrado');
        }
    }

}