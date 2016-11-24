<?php

namespace app\controllers;

use app\models\Foto;
use app\models\LoginAdminForm;
use app\models\Usuario;
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
            $usuarios = Usuario::findAll(['ace_id' => 2]);
            foreach ($usuarios as $usuario){
                $usuario->fotos = Foto::findByUser($usuario);
                foreach ($usuario->fotos as $foto){
                    $usuario->downloads += $foto->getDownloads()->count();
                }
            }
            return $this->render('index',['users'=>$usuarios]);
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
