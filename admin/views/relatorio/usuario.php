<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use \yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Usuario */

$this->title = 'Relatório do Usuário';
$this->registerCss("
.gi{font-size: 2em;}");
?>
<div class="list-usuario">

    <div class=" container-fluid">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="fixed-table-container">
                        <h3>
                            Lista de fotos do Usuário <small><strong><?=$user?>(<?=$login?>)</strong></small>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        <?=
        Html::a(
            '<i class="glyphicon glyphicon-print gi"></i>',
            ['relatorio/save-single-user'],
            ['class' => 'btn-lg pull-right',
                'data' => [
                    'method' => 'post',
                    'params' => [
                        'lista' => $fotos,
                        'login'=>$login
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
                    'downloads',
                    'visualizacoes'
                ],
            ]);
        ?>
        </div>
    </div>
