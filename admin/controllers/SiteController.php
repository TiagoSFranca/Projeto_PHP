<?php

namespace app\controllers;

use app\models\Download;
use app\models\Foto;
use app\models\LoginAdminForm;
use app\models\Usuario;
use app\models\Visualizacao;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        //AppAsset::verifyAccess(1);
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new LoginAdminForm();

        if (($model->load(Yii::$app->request->post()) && $model->login()) || !Yii::$app->user->isGuest) {
            $parametro = Yii::$app->getRequest()->getQueryParam('q');

            if($parametro != null){
                $usuarios = Usuario::findByLike($parametro);
            }else {
                $usuarios = Usuario::findAll(['ace_id' => 2]);
            }
                foreach ($usuarios as $usuario) {
                    $usuario->downloads = sizeof(Download::findByUser($usuario->usu_id));
                    $usuario->fotos = sizeof(Foto::findByUser($usuario->usu_id));
                    $usuario->visualizacoes = sizeof(Visualizacao::findByUser($usuario->usu_id));
                }
            return $this->render('index',[
                'users'=>$usuarios,
                'param'=>$parametro
            ]);
        }
        else{
            return $this->render('login', ['model' => $model]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
