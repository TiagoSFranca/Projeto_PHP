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
use app\models\Usuario;
use app\models\Visualizacao;
use yii\web\Controller;
use Yii;

class FotoController extends Controller
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

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

       if ($model->load(Yii::$app->request->post()) && $model->save()) {
           Yii::$app->getSession()->setFlash('sucess', 'Edição realizado Com Sucesso.');
            return $this->redirect(["/usuario/index"]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
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

    public function actionList()
    {
        $id = Yii::$app->getRequest()->post();
        if ($this->verificarLogin() && $id['id'] != null) {
            isset($id['q'])?$parametro=$id['q']:$parametro=null;
            $model = Usuario::findOne(['usu_id'=>$id]);
            if($parametro!= null){
                $modelFoto = Foto::findByLike($parametro,$model->usu_id);
            }else{
                $modelFoto = Foto::findAll(['usu_id'=>$id]);
            }
            foreach ($modelFoto as $foto) {
                $foto->downloads = sizeof(Download::findByFoto($foto->foto_id));
                $foto->visualizacoes = sizeof(Visualizacao::findByFoto($foto->foto_id));
            }

            return $this->render('list', [
                'modelFoto' => $modelFoto,
                'model'=>$model,
                'param'=>$parametro
            ]);
        }else{
            $this->goHome();
        }
    }

    public function actionDelete($id)
    {
        unlink(Yii::getAlias('@app').$this->findModel($id)->foto_caminho);
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('sucess', 'Exclusão realizada Com Sucesso.');

    }

    public function actionIndex()
    {
        if($this->verificarLogin()) {
            $model = Yii::$app->user;
            $parametro = Yii::$app->getRequest()->getQueryParam('q');

            if ($parametro != null) {
                $modelFoto = Foto::findByLike($parametro);
            } else {
                $modelFoto = Foto::find()->limit(18)->all();
            }
            foreach ($modelFoto as $foto) {
                $foto->downloads = sizeof(Download::findByFoto($foto->foto_id));
                $foto->visualizacoes = sizeof(Visualizacao::findByFoto($foto->foto_id));
            }

            return $this->render('index', [
                'model' => $model,
                'modelFoto' => $modelFoto,
                'param' => $parametro
            ]);
        }else{
            $this->goHome();
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