<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */

$this->title = 'Relatório de Usuários';
$this->registerCss("
.gi{font-size: 2em;}");
$opcao = "";
$opcao_usuario = 0;
$opcao_ordenacao = "ASC";
$filtro_usuario = "";
if($users!= false) {
    $opcao = $op;
    $opcao_usuario = $opUsuario;
    $filtro_usuario = $filtro;
    $opcao_ordenacao = $ordenacao;
}
?>
<div class="list-usuario">
        <?php $form = ActiveForm::begin(); ?>
        <div class="form">
            <div class=" container-fluid">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-xs-2 col-sm-2 col-md-2 pull-right">
                            <div class="form-group">
                                <div data-toggle="buttons">
                                    <label class="btn btn-default btn-circle btn-sm <?=$opcao_ordenacao=='ASC'?'active':'';?>">
                                        <input type="radio" name="ordenacao" value="ASC" <?=$opcao_ordenacao=='ASC'?'checked':'';?>/>
                                        <i class="glyphicon glyphicon-sort-by-attributes"></i>
                                    </label>
                                    <label class="btn btn-default btn-circle btn-sm <?=$opcao_ordenacao=='DESC'?'active':'';?>">
                                        <input type="radio" name="ordenacao" value="DESC" <?=$opcao_ordenacao=='DESC'?'checked':'';?>/>
                                        <i class="glyphicon glyphicon-sort-by-attributes-alt"></i>
                                    </label>

                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <div class="form-group">
                                <div class="selectContainer">
                                    <select class="form-control" name="tipo_usuario">
                                        <option value="2">
                                            Selecione o Usuário...
                                        </option>
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
                                        <option value="usu_data_nascimento">
                                            Tipo de Requisição
                                        </option>
                                        <option value="usu_data_nascimento" <?=$opcao=="usu_data_nascimento"?'selected':'';?>>
                                            Data de Nascimento
                                        </option>
                                        <option value="usu_data_cadastro" <?=$opcao=="usu_data_cadastro"?'selected':'';?>>
                                            Data de Cadastro
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <?= $form->field($model,'data_inicial')->textInput(['type' =>'date'])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <?= $form->field($model,'data_final')->textInput(['type' =>'date'])->label(false) ?>
                            </div>
                        </div>
                        <div class="col-xs-offset-1 col-xs-5 col-sm-5 col-md-5">
                            <div class="form-group">
                                <div class="selectContainer">
                                    <select class="form-control" name="filtro">
                                        <option value="usu_id" >
                                            Selecione o Filtro...
                                        </option>
                                        <option value="usu_id" <?=$filtro_usuario=='usu_id'?'selected':'';?>>
                                            ID
                                        </option>
                                        <option value="usu_nome" <?=$filtro_usuario=='usu_nome'?'selected':'';?>>
                                            Nome
                                        </option>
                                        <option value="usu_login" <?=$filtro_usuario=='usu_login'?'selected':'';?>>
                                            Login
                                        </option>
                                        <option value="usu_data_nascimento" <?=$filtro_usuario=='usu_data_nascimento'?'selected':'';?>>
                                            Data de Nascimento
                                        </option>
                                        <option value="usu_email" <?=$filtro_usuario=='usu_email'?'selected':'';?>>
                                            Email
                                        </option>
                                        <option value="usu_data_cadastro" <?=$filtro_usuario=='us_data_cadastro'?'selected':' ';?>>
                                            Data de Cadastro
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
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
            ['relatorio/save-user'],
            ['class' => 'btn-lg pull-right',
                'data' => [
                    'method' => 'post',
                    'params' => [
                        'lista' => (array)$users,
                        'opcao'=>$opcao==1?'Data de Nascimento':'Data de Cadastro',
                        'opcao_usuario'=>$opcao_usuario==1?'Admin':'Usuário Comum',
                        'data_inicial'=>empty($model->data_inicial)?'Não Informado':$model->data_inicial,
                        'data_final'=>$model->data_final,
                        'filtro' => $filtro_usuario
                    ]
                ],
            ]
        );
        ?>
        <?php
        if ($opcao_usuario != 1) {
            echo GridView::widget([
                'dataProvider' => $users,
                'class' => 'fixed-table-body',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'usu_id',
                    'usu_nome',
                    'usu_login',
                    'usu_data_nascimento',
                    'usu_sexo',
                    'usu_email',
                    'usu_data_cadastro',
                    'downloads',
                    'fotos',
                    'visualizacoes',
                ],
            ]);
        }else{
            echo GridView::widget([
                'dataProvider' => $users,
                'class' => 'fixed-table-body',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'usu_id',
                    'usu_nome',
                    'usu_login',
                    'usu_data_nascimento',
                    'usu_sexo',
                    'usu_email',
                    'usu_data_cadastro',
                ],
            ]);
        }
        ?>
        </div>
        <?php
    }
    ?>
    </div>
