<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */

$this->title = 'Relatório de Visualizaçoes';
$this->registerCss("
    .gi{
        font-size: 2em;
        }
    .gi-1x{
        font-size: 1em;
    }
        ");
$opcao = "";
$opcao_ordenacao = "ASC";
if($views != false){
    $opcao = $filtro;
    $opcao_ordenacao = $ordenacao;
}
?>
<div class="list-view">
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
                                    <select class="form-control" name="filtro">
                                        <option value="visu_data">
                                            Selecione o Filtro...
                                        </option>
                                        <option value="visu_data" <?=$opcao=='visu_data'?'selected':'';?>>
                                            Data da Visualização
                                        </option>
                                        <option value="quantidade" <?=$opcao=='quantidade'?'selected':'';?>>
                                            Quantidade
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
    if($views != null){
    ?>
    <div class="fixed-table-container">
        <?=
        Html::a(
            '<i class="glyphicon glyphicon-print gi"></i>',
            ['relatorio/save-view'],
            ['class' => 'btn-lg pull-right',
                'data' => [
                    'method' => 'post',
                    'params' => [
                        'lista' => (array)$views,
                        'data_inicial'=>empty($model->data_inicial)?'Não Informado':$model->data_inicial,
                        'data_final'=>$model->data_final,
                        'filtro'=>$filtro,
                        'ordenacao'=>$ordenacao
                    ]
                ],
            ]
        );
        ?>
        <?php
            echo GridView::widget([
                'dataProvider' => $views,
                'class' => 'fixed-table-body',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'visu_data',
                    'quantidade'
                ],
            ]);
        ?>
        </div>
        <?php
    }
    ?>
    </div>
