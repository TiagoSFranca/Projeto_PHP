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
use app\models\RelatorioForm;
use app\models\Usuario;
use app\models\Visualizacao;
use kartik\mpdf\Pdf;
use yii\data\ArrayDataProvider;
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

    private function getContent($dados){
        $content = "
            <div class=\"panel panel-body\">
                <div class=\"row-fluid\">
                    <div class=\"col-xs-12 col-md-12 panel-heading\">
                        <h4 class=\"text-center\">
                            Projeto PHP
                        </h4>
                    </div>
                </div>
                <div class=\"row-fluid\">
                    <div class=\"col-xs-12 col-md-12\">
                        <h5 class=\"text-center\">
                            DADOS DO SOLICITANTE
                        </h5>
                    </div>
                </div>
                <div class=\"row-fluid  panel-heading\">
                    <div class=\"col-xs-5\">
                        Nome: ".Yii::$app->user->identity->usu_nome."
                    </div>
                    <div class=\"col-xs-5\">
                        Email: ".Yii::$app->user->identity->usu_email."
                    </div>
                </div>";
        if($dados!=null){
            $content.="
                <div class=\"row-fluid\">
                    <div class=\"col-xs-12 col-md-12\">
                        <h5 class=\"text-center\">
                            PERÍODO DA SOLICITAÇÃO
                        </h5>
                    </div>
                </div>
                <div class=\"row-fluid  panel-heading\">
                    <div class=\"col-xs-5\">
                        Data Inicial:  ".$dados["data_inicial"]."
                    </div>
                    <div class=\"col-xs-5\">
                        Data Final:  ".$dados["data_final"]."
                    </div>
                </div>";
        }

        $content.="
                <div class=\"row-fluid\">
                    <div class=\"col-xs-12 col-md-12\">
                        <h5 class=\"text-center\">
                            DADOS DA SOLICITACAO
                        </h5>
                    </div>
                </div>";

        return $content;
    }


    public function actionUsuario(){
        if($this->verificarLogin()) {
            $model = new RelatorioForm();
            $user = Yii::$app->getRequest()->post();
            $usu_id = $user["id"];
            $usu_nome = $user["nome"];
            $usu_login = $user["login"];
            $query = $model->listarFotosUsuarios($usu_id);
            $models  = new ArrayDataProvider([
                'allModels' => $query,
            ]);

            Yii::$app->session->set('lista',$query);
            return $this->render('usuario', [
                'fotos' => $models,
                'user'=>$usu_nome,
                'login'=>$usu_login,

            ]);
        }else{
            $this->goHome();
        }
    }

    public function actionSaveSingleUser(){
        $usu_login = Yii::$app->getRequest()->post();
        $dados = Usuario::findByUsername($usu_login);
        $dados->fotos = sizeof(Foto::findByUser($dados->usu_id));
        $dados->visualizacoes = sizeof(Visualizacao::findByUser($dados->usu_id));
        $dados->downloads = sizeof(Download::findByUser($dados->usu_id));
        $content = $this->getContent(null);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content.$this->getUsuario($dados),
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'options' => ['title' => 'User Report'],
            'cssInline'=>
                '@media print{
                        .page-break{display: block;page-break-before: always;}
                    }',
            'methods' => [
                'SetHeader'=>['||Gerado em: '.date("r")],
                'SetFooter'=>['Página {PAGENO}'],
            ]
        ]);

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        return $pdf->render();
    }

    private function getUsuario($usuario){

        $lista = Yii::$app->session->get('lista');
        $sexo = $usuario->usu_sexo=='M'?'Masculino':'Feminino';
        $tabela = "";
        $relatorio = "
                <div class=\"row-fluid\">
                    <div class=\"col-xs-5\">
                        Nome do Usuário: ".$usuario->usu_nome."
                    </div>
                    <div class=\"col-xs-5\">
                        Login: ".$usuario->usu_login."
                    </div>
                    <div class=\"col-xs-5\">
                        Email: ".$usuario->usu_email."
                    </div>
                    <div class=\"col-xs-5\">
                        Sexo: ".$sexo."
                    </div>
                    <div class=\"col-xs-5\">
                        Data de Nascimento: ".$usuario->usu_data_nascimento."
                    </div>
                    <div class=\"col-xs-5\">
                        Data de Cadastro: ".$usuario->usu_data_cadastro."
                    </div>
                    <div class=\"col-xs-3\">
                        Fotos: ".$usuario->fotos."
                    </div>
                    <div class=\"col-xs-3\">
                        Downloads: ".$usuario->downloads."
                    </div>
                    <div class=\"col-xs-3\">
                        Visualizações: ".$usuario->visualizacoes."
                    </div>
                </div>
            </div>
                <div class=\"row-fluid\">
                    <div class=\"col-xs-12 col-md-12\">
                        <h3 class=\"text-center\">
                            <strong>
                                RELÁTORIO
                            </strong>
                        </h3>
                    </div>
                </div>";
            foreach ($lista as $key=>$tupla) {
                $key+=1;
                $tabela .="
                    <tr>
                        <td>$key</td>
                        <td>$tupla->foto_id</td>
                        <td>$tupla->foto_nome</td>
                        <td>$tupla->foto_data_upload</td>
                        <td>$tupla->downloads</td>
                        <td>$tupla->visualizacoes</td>
                    </tr>";
            }

            $relatorio .= "
            <div class=\"table-responsive col-md-12\">
            <table class=\"table table-striped table-hover small\">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Data de Upload</th>
                        <th>Downloads</th>
                        <th>Visualizações</th>
                    </tr>
                </thead>
                <tbody>
                    $tabela;
                </tbody>
            </table>
        </div>
        ";

        return $relatorio;
    }




    public function actionListUsuario(){
        if($this->verificarLogin()) {
            $model = new RelatorioForm();
            if ($model->load(Yii::$app->request->post())) {

                $parametro = Yii::$app->getRequest()->getBodyParam('data');
                $tipoUsuario =  Yii::$app->getRequest()->getBodyParam('tipo_usuario');
                $filtro = Yii::$app->getRequest()->getBodyParam('filtro');
                $ordenacao =  Yii::$app->getRequest()->getBodyParam('ordenacao');
                $query = $model->listarTodosUsuarios($parametro,$tipoUsuario,$filtro,$ordenacao);
                $models  = new ArrayDataProvider([
                    'allModels' => $query,
                ]);

                Yii::$app->session->set('lista',$query);
                return $this->render('list-usuario', [
                    'model' => $model,
                    'users' => $models,
                    'op' => $parametro,
                    'opUsuario' => $tipoUsuario,
                    'filtro' =>$filtro,
                    'ordenacao'=>$ordenacao
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

    public function actionSaveUser(){
        $dados = Yii::$app->getRequest()->post();
        $content = $this->getContent($dados);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content.$this->getListUsuario($dados),
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'options' => ['title' => 'List User Report'],
            'cssInline'=>
                    '@media print{
                        .page-break{display: block;page-break-before: always;}
                    }',
            'methods' => [
                'SetHeader'=>['||Gerado em: '.date("r")],
                'SetFooter'=>['Página {PAGENO}'],
            ]
        ]);

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        return $pdf->render();
    }

    private function getListUsuario($dados){
        $filtro = substr($dados["filtro"],4);
        $filtro = str_replace("_"," ",$filtro);
        $filtro = ucfirst($filtro);
        $lista = Yii::$app->session->get('lista');
        $tabela = "";
        $relatorio = "
                <div class=\"row-fluid\">
                    <div class=\"col-xs-5\">
                        Tipo da Solicitação: ".$dados["opcao"]."
                    </div>
                    <div class=\"col-xs-5\">
                        Usuário Alvo: ".$dados["opcao_usuario"]."
                    </div>
                    <div class=\"col-xs-5\">
                        Filtro: $filtro
                    </div>
                </div>
            </div>
                <div class=\"row-fluid\">
                    <div class=\"col-xs-12 col-md-12\">
                        <h3 class=\"text-center\">
                            <strong>
                                RELÁTORIO DE USUÁRIOS
                            </strong>
                        </h3>
                    </div>
                </div>";


        if($dados["opcao_usuario"] != 'Admin') {
           foreach ($lista as $key=>$tupla) {
               $key+=1;
                $tabela .="
                    <tr>
                        <td>$key</td>
                        <td>$tupla->usu_id</td>
                        <td>$tupla->usu_nome</td>
                        <td>$tupla->usu_login</td>
                        <td>$tupla->usu_data_nascimento</td>
                        <td>$tupla->usu_sexo</td>
                        <td>$tupla->usu_email</td>
                        <td>$tupla->usu_data_cadastro</td>
                        <td>$tupla->downloads</td>
                        <td>$tupla->fotos</td>
                        <td>$tupla->visualizacoes</td>
                    </tr>";
            }

            $relatorio .= "
            <div class=\"table-responsive col-md-12\">
            <table class=\"table table-striped table-hover small\">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>Data de Nascimento</th>
                        <th>Sexo</th>
                        <th>Email</th>
                        <th>Data de Cadastro</th>
                        <th>Downloads</th>
                        <th>Fotos</th>
                        <th>Visualizações</th>
                    </tr>
                </thead>
                <tbody>
                    $tabela;
                </tbody>
            </table>
        </div>
        ";
        }
        else{
            foreach ($lista as $key=>$tupla) {
                $tabela .="
                    <tr>
                        <td>$key</td>
                        <td>$tupla->usu_id</td>
                        <td>$tupla->usu_nome</td>
                        <td>$tupla->usu_login</td>
                        <td>$tupla->usu_data_nascimento</td>
                        <td>$tupla->usu_sexo</td>
                        <td>$tupla->usu_email</td>
                        <td>$tupla->usu_data_cadastro</td>
                    </tr>";
            }

            $relatorio .= "
            <div class=\"table-responsive col-md-12\">
            <table class=\"table table-striped table-hover small\">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>Data de Nascimento</th>
                        <th>Sexo</th>
                        <th>Email</th>
                        <th>Data de Cadastro<th>
                    </tr>
                </thead>
                <tbody>
                    $tabela;
                </tbody>
            </table>
        </div>
        ";
        }

        return $relatorio;
    }




    public function actionListFoto(){
        if($this->verificarLogin()) {
            $model = new RelatorioForm();
            if ($model->load(Yii::$app->request->post())) {

                $filtro = Yii::$app->getRequest()->getBodyParam('filtro');
                $ordenacao =  Yii::$app->getRequest()->getBodyParam('ordenacao');
                $query = $model->listarTodasFotos($ordenacao,$filtro);
                $models  = new ArrayDataProvider([
                    'allModels' => $query,
                ]);

                Yii::$app->session->set('lista',$query);
                return $this->render('list-foto', [
                    'model' => $model,
                    'fotos' => $models,
                    'filtro' =>$filtro
                ]);
            } else {
                return $this->render('list-foto', [
                    'model' => $model,'fotos'=>false,
                ]);
            }
        }else{
            $this->goHome();
        }
    }

    public function actionSaveFoto(){
        $dados = Yii::$app->getRequest()->post();
        $content = $this->getContent($dados);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content.$this->getListFoto($dados),
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'options' => ['title' => 'List Picture Report'],
            'cssInline'=>
                '@media print{
                        .page-break{display: block;page-break-before: always;}
                    }',
            'methods' => [
                'SetHeader'=>['||Gerado em: '.date("r")],
                'SetFooter'=>['Página {PAGENO}'],
            ]
        ]);

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        return $pdf->render();
    }

    private function getListFoto($dados){
        $filtro = substr($dados["filtro"],5);
        $filtro = str_replace("_"," ",$filtro);
        $filtro = ucfirst($filtro);
        $lista = Yii::$app->session->get('lista');
        $tabela = "";
        $relatorio = "
                <div class=\"row-fluid panel-heading\">
                    <div class=\"col-xs-5\">
                        Tipo da Solicitação: Data de Cadastro
                    </div>
                    <div class=\"col-xs-5\">
                        Usuário Alvo: Usuário Comum
                    </div>
                    <div class=\"col-xs-5\">
                        Filtro: $filtro
                    </div>
                </div>
            </div>
                <div class=\"row-fluid\">
                    <div class=\"col-xs-12 col-md-12\">
                        <h3 class=\"text-center\">
                            <strong>
                                RELÁTORIO DE FOTOS
                            </strong>
                        </h3>
                    </div>
                </div>";


            foreach ($lista as $key=>$tupla) {
                $key+=1;
                $tabela .="
                    <tr>
                        <td>$key</td>
                        <td>$tupla->foto_id</td>
                        <td>$tupla->foto_nome</td>
                        <td>$tupla->usu_login</td>
                        <td>$tupla->foto_data_upload</td>
                        <td>$tupla->downloads</td>
                        <td>$tupla->visualizacoes</td>
                    </tr>";
            }

            $relatorio .= "
            <div class=\"table-responsive col-md-12 panel panel-body\">
            <table class=\"table table-striped table-hover small\">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Usuário</th>
                        <th>Data de Upload</th>
                        <th>Downloads</th>
                        <th>Visualizações</th>
                    </tr>
                </thead>
                <tbody>
                    $tabela;
                </tbody>
            </table>
        </div>
        ";

        return $relatorio;
    }




    public function actionListView(){
        if($this->verificarLogin()) {
            $model = new RelatorioForm();
            $ordenacao =  Yii::$app->getRequest()->getBodyParam('ordenacao');
            $filtro = Yii::$app->getRequest()->getBodyParam('filtro');
            if ($model->load(Yii::$app->request->post())) {
                $query = $model->listarTodasViews($ordenacao,$filtro);
                $models  = new ArrayDataProvider([
                    'allModels' => $query,
                ]);
                Yii::$app->session->set('lista',$query);
                return $this->render('list-view', [
                    'model' => $model,
                    'views' => $models,
                    'ordenacao'=>$ordenacao,
                    'filtro'=>$filtro
                ]);
            } else {
                return $this->render('list-view', [
                    'model' => $model,'views'=>false,
                ]);
            }
        }else{
            $this->goHome();
        }
    }

    public function actionSaveView(){
        $dados = Yii::$app->getRequest()->post();
        $content = $this->getContent($dados);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content.$this->getListView($dados),
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'options' => ['title' => 'View Report'],
            'cssInline'=>
                '@media print{
                        .page-break{display: block;page-break-before: always;}
                    }',
            'methods' => [
                'SetHeader'=>['||Gerado em: '.date("r")],
                'SetFooter'=>['Página {PAGENO}'],
            ]
        ]);

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        return $pdf->render();
    }

    private function getListView($dados){
        $filtro = $dados["filtro"];
        if($filtro == "quantidade"){
            $filtro = "Quantidade";
        }else{
            $filtro = "Data de Upload";
        }
        $lista = Yii::$app->session->get('lista');
        $tabela = "";
        $relatorio = "
                <div class=\"row-fluid panel-heading\">
                    <div class=\"col-xs-5\">
                        Filtro: $filtro
                    </div>
                    <div class=\"col-xs-5\">
                        Ordenação: ".$dados['ordenacao']."
                    </div>
                </div>
            </div>
                <div class=\"row-fluid\">
                    <div class=\"col-xs-12 col-md-12\">
                        <h3 class=\"text-center\">
                            <strong>
                                RELÁTORIO DE VISUALIZAÇÕES
                            </strong>
                        </h3>
                    </div>
                </div>";


        foreach ($lista as $key=>$tupla) {
            $key+=1;
            $tabela .="
                    <tr>
                        <td>$key</td>
                        <td>$tupla->visu_data</td>
                        <td>$tupla->quantidade</td>
                    </tr>";
        }

        $relatorio .= "
            <div class=\"table-responsive col-md-12 panel panel-body\">
            <table class=\"table table-striped table-hover small\">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Data de Visualização</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    $tabela;
                </tbody>
            </table>
        </div>
        ";

        return $relatorio;
    }




    public function actionListDownload(){
        if($this->verificarLogin()) {
            $model = new RelatorioForm();
            $ordenacao =  Yii::$app->getRequest()->getBodyParam('ordenacao');
            $filtro = Yii::$app->getRequest()->getBodyParam('filtro');
            if ($model->load(Yii::$app->request->post())) {
                $query = $model->listarTodosDownloads($ordenacao,$filtro);
                $models  = new ArrayDataProvider([
                    'allModels' => $query,
                ]);
                Yii::$app->session->set('lista',$query);
                return $this->render('list-download', [
                    'model' => $model,
                    'views' => $models,
                    'ordenacao'=>$ordenacao,
                    'filtro'=>$filtro
                ]);
            } else {
                return $this->render('list-download', [
                    'model' => $model,'views'=>false,
                ]);
            }
        }else{
            $this->goHome();
        }
    }

    public function actionSaveDownload(){
        $dados = Yii::$app->getRequest()->post();
        $content = $this->getContent($dados);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content.$this->getListDownload($dados),
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'options' => ['title' => 'Download Report'],
            'cssInline'=>
                '@media print{
                        .page-break{display: block;page-break-before: always;}
                    }',
            'methods' => [
                'SetHeader'=>['||Gerado em: '.date("r")],
                'SetFooter'=>['Página {PAGENO}'],
            ]
        ]);

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        return $pdf->render();
    }

    private function getListDownload($dados){
        $filtro = $dados["filtro"];
        if($filtro == "quantidade"){
            $filtro = "Quantidade";
        }else{
            $filtro = "Data de Download";
        }
        $lista = Yii::$app->session->get('lista');
        $tabela = "";
        $relatorio = "
                <div class=\"row-fluid panel-heading\">
                    <div class=\"col-xs-5\">
                        Filtro: $filtro
                    </div>
                    <div class=\"col-xs-5\">
                        Ordenação: ".$dados['ordenacao']."
                    </div>
                </div>
            </div>
                <div class=\"row-fluid\">
                    <div class=\"col-xs-12 col-md-12\">
                        <h3 class=\"text-center\">
                            <strong>
                                RELÁTORIO DE DOWNLOADS
                            </strong>
                        </h3>
                    </div>
                </div>";


        foreach ($lista as $key=>$tupla) {
            $key+=1;
            $tabela .="
                    <tr>
                        <td>$key</td>
                        <td>$tupla->down_data</td>
                        <td>$tupla->quantidade</td>
                    </tr>";
        }

        $relatorio .= "
            <div class=\"table-responsive col-md-12 panel panel-body\">
            <table class=\"table table-striped table-hover small\">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Data de Download</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    $tabela;
                </tbody>
            </table>
        </div>
        ";

        return $relatorio;
    }



    public function actionSaveSingleFoto(){
        $foto_id = Yii::$app->getRequest()->post();
        $foto = RelatorioForm::getFoto($foto_id);
        $content = $this->getContent(null);
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content.$this->getFoto($foto),
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            'options' => ['title' => 'Picture Report'],
            'cssInline'=>
                '@media print{
                        .page-break{display: block;page-break-before: always;}
                    }',
            'methods' => [
                'SetHeader'=>['||Gerado em: '.date("r")],
                'SetFooter'=>['Página {PAGENO}'],
            ]
        ]);

        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');

        return $pdf->render();
    }

    private function getFoto($foto){
        $chave = 0;
        $usuario = $foto->usu_login;
        $downloads = $foto->downloads;
        $visualizacoes = $foto->visualizacoes;
        $visu = array_sum(array_column($visualizacoes, 'quantidade'));
        $down = array_sum(array_column($downloads, 'quantidade'));
        $lista = $this->getDadosFoto($downloads,$visualizacoes);
        $sexo = $usuario->usu_sexo=='M'?'Masculino':'Feminino';
        $tabela = "";
        $relatorio = "
                <div class=\"row-fluid\">
                    <div class=\"col-xs-5\">
                        Login: ".$usuario->usu_login."
                    </div>
                    <div class=\"col-xs-5\">
                        Email: ".$usuario->usu_email."
                    </div>
                    <div class=\"col-xs-5\">
                        Sexo: ".$sexo."
                    </div>
                    <div class=\"col-xs-5\">
                        Data de Nascimento: ".$usuario->usu_data_nascimento."
                    </div>
                    <div class=\"col-xs-5\">
                        Nome da Foto: ".$foto->foto_nome."
                    </div>
                    <div class=\"col-xs-5\">
                        Tag: ".$foto->foto_tag."
                    </div>
                    <div class=\"col-xs-4\">
                        Data de Upload: ".$foto->foto_data_upload."
                    </div>
                    <div class=\"col-xs-3\">
                        Visualizações: ".$visu."
                    </div>
                    <div class=\"col-xs-3\">
                        Downloads: ".$down."
                    </div>
                </div>
            </div>
                <div class=\"row-fluid\">
                    <div class=\"col-xs-12 col-md-12\">
                        <h3 class=\"text-center\">
                            <strong>
                                RELÁTORIO
                            </strong>
                        </h3>
                    </div>
                </div>";
        foreach ($lista as $tupla) {
            if($tupla != null && !empty($tupla['data'])) {
                $chave++;
                $tabela .= "
                    <tr>
                        <td>$chave</td>
                        <td>" . $tupla['data'] . "</td>
                        <td>" . $tupla['down'] . "</td>
                        <td>" . $tupla['visu'] . "</td>
                        <td>" . ($tupla['visu'] + $tupla['down']) . "</td>
                    </tr>";
            }
        }

        $relatorio .= "
            <div class=\"table-responsive col-md-12 panel panel-body\">
            <table class=\"table table-striped table-hover small\">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Data</th>
                        <th>Download</th>
                        <th>Visualização</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                $tabela
                    <tr>
                        <td></td>
                        <td rowspan='2'>TOTAL</td>
                        <td>$down</td>
                        <td>$visu</td>
                        <td>".($visu+$down)."</td>
                    </tr>
                </tbody>
            </table>
        </div>
        ";
        return $relatorio;
    }

    private function getDadosFoto($downloads,$visualizacoes){
        $lista = [
            [
                'data'=>null,
                'down'=>null,
                'visu'=> null
            ]
        ];
        foreach ($downloads as $d){
            array_push($lista,
                [
                    'data'=>$d->down_data,
                    'down' => $d->quantidade,
                    'visu'=> 0
                ]
            );
        }
        foreach ($visualizacoes as $v){
            array_push($lista,
                [
                    'data'=>$v->visu_data,
                    'down' => 0,
                    'visu'=> $v->quantidade
                ]
            );
        }
        sort($lista);
        for ($i=0;$i<sizeof($lista);$i++){
            if($i+1<sizeof($lista))
                if($lista[$i]['data'] == $lista[$i+1]['data']){
                    $lista[$i]['down'] += $lista[$i+1]['down'];
                    $lista[$i]['visu'] += $lista[$i+1]['visu'];
                    unset($lista[$i+1]);
                }
        }
        return $lista;
    }

}