<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Foto;
use app\models\Download;
use app\models\Visualizacao;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */

$this->title = 'Relatório de Usuários';
$this->registerCss("
.gi{font-size: 2em;}");
$opcao = 0;
$opcao_usuario = 0;
if($users!= null) {
    $opcao = $op;
    $opcao_usuario = $opUsuario;
}
?>
<div class="usuario-config">
        <?php $form = ActiveForm::begin(); ?>
        <div class="form">
            <div class=" container-fluid">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <div class="selectContainer">
                                    <select class="form-control" name="tipo_usuario">
                                        <option value="2" <?=$opcao_usuario==2?'selected':'';?>>
                                            Usuário Comum
                                        </option>
                                        <option value="1" <?=$opcao_usuario==1?'selected':'';?>>
                                            Admin
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                                <div class="selectContainer">
                                    <select class="form-control" name="data">
                                        <option value="1" <?=$opcao==1?'selected':'';?>>
                                            Data de Nascimento
                                        </option>
                                        <option value="2" <?=$opcao==2?'selected':'';?>>
                                            Data de Cadastro
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <?= $form->field($model,'usu_data_inicial')->textInput(['type' =>'date'])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <?= $form->field($model,'usu_data_final')->textInput(['type' =>'date'])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-info btn-block']) ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </div>
    <?php ActiveForm::end();
    if($users != null){
    ?>
    <div class="fixed-table-container">
        <?=
        Html::a(
            '<i class="glyphicon glyphicon-print gi"></i>',
            ['relatorio/save'],
            ['class' => 'btn-lg pull-right',
                'data' => [
                    'method' => 'post',
                    'params' => ['lista' => $users]
                ],
            ]
        );
        ?>
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $users,
            'class' => 'fixed-table-body',
            'columns' => [
                'usu_id',
                'usu_nome',
                'usu_login',
                'usu_data_nascimento',
                'usu_sexo',
                'usu_email',
                'usu_data_cadastro',
                [
                    'class' => 'yii\grid\DataColumn',
                    'header'=>'Fotos',
                    'value' => function ($data) {
                        return sizeof(Foto::findByUser($data->usu_id));
                    },
                ],
                [
                    'class' => 'yii\grid\DataColumn',
                    'header'=>'Downloads',
                    'value' => function ($data) {
                        return sizeof(Download::findByUser($data->usu_id));
                    },
                ],
                [
                    'class' => 'yii\grid\DataColumn',
                    'header'=>'Visualizações',
                    'value' => function ($data) {
                        return sizeof(Visualizacao::findByUser($data->usu_id));
                    },
                ],
            ],
        ]) ?>
        </div>
        <?php
    }
    ?>
    </div>
