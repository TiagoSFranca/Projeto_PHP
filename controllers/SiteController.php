<?php

namespace app\controllers;

use app\models\Download;
use app\models\Foto;
use app\models\Usuario;
use app\models\Visualizacao;
use Yii;
use yii\data\Pagination;
use yii\data\Sort;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

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
        $model = Yii::$app->user;
        $parametro = Yii::$app->getRequest()->getQueryParam('q');

        if($parametro != null){
            $modelFoto = Foto::findByLike($parametro);
        }else {
            $modelFoto = Foto::findBySql('select f.*,count(d.down_data) as downloads from foto f
                                    join download d using (foto_id)
                                    group by f.foto_id 
                                    order by downloads desc')->limit(18)->all();
        }
        foreach ($modelFoto as $foto) {
            $foto->usu_login = Usuario::findIdentity($foto->usu_id);
            $foto->downloads = sizeof(Download::findByFoto($foto->foto_id))-1;
            $foto->visualizacoes = sizeof(Visualizacao::findByFoto($foto->foto_id));
        }

        return $this->render('index', [
            'model' => $model,'modelFoto'=>$modelFoto,'parametro' =>$parametro
        ]);
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
