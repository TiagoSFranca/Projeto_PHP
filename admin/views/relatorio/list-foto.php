<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */

$this->title = 'Relatório de Fotos';
$this->registerCss("
.gi{font-size: 2em;}");
$filtro_pesquisa = "";
$opcao_ordenacao= "ASC";
if($fotos!= null) {
    $filtro_pesquisa = $filtro;
}
?>
<div class="list-foto">
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
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <div class="selectContainer">
                                    <select class="form-control" name="filtro">
                                        <option value="foto_id">
                                            Selecione o Filtro...
                                        </option>
                                        <option value="foto_id" <?=$filtro_pesquisa=='foto_id'?'selected':'';?>>
                                            ID
                                        </option>
                                        <option value="foto_nome" <?=$filtro_pesquisa=='foto_nome'?'selected':'';?>>
                                            Nome
                                        </option>
                                        <option value="foto_data_upload" <?=$filtro_pesquisa=='foto_data_upload'?'selected':'';?>>
                                            Data de Upload
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
    if($fotos != null){
    ?>
    <div class="fixed-table-container">
        <?=
        Html::a(
            '<i class="glyphicon glyphicon-print gi"></i>',
            ['relatorio/save-foto'],
            ['class' => 'btn-lg pull-right',
                'data' => [
                    'method' => 'post',
                    'params' => [
                        'lista' => (array)$fotos,
                        'data_inicial'=>empty($model->data_inicial)?'Não Informado':$model->data_inicial,
                        'data_final'=>$model->data_final,
                        'filtro' => $filtro_pesquisa,
                        'ordenacao'=>$opcao_ordenacao
                    ]
                ],
            ]
        );
        ?>
        <?php
            echo GridView::widget([
                'dataProvider' => $fotos,
                'class' => 'fixed-table-body',
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'foto_id',
                    'foto_nome',
                    'usu_login',
                    'foto_data_upload',
                    'downloads',
                    'visualizacoes'
                ],
            ]);
        ?>
        </div>
        <?php
    }
    ?>
    </div>
