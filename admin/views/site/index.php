<?php

/* @var $this yii\web\View */

use yii\bootstrap\Html;

$this->title = 'Início';
$script = <<<CSS
.well {
    min-height: 20px;
    padding: 0px;
    margin-bottom: 20px;
    background-color: #D9D9D9;
    border: 1px solid #D9D9D9;
    border-radius: 0px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    padding-left: 15px;
    border:0px;
}
.thumbnail .caption {
    padding: 9px;
    color: #333;
    padding-left: 0px;
    padding-right: 0px;
}
.icon-style{
    margin-right:15px;
    font-size:18px;
    margin-top:20px;
}
p{
    margin:3px;
}
.well-add-card{
    margin-bottom:10px;
}
.btn-add-card{
    margin-top:20px;
}
.thumbnail {
    display: block;
    padding: 1px;
    margin-bottom: 20px;
    line-height: 1.42857143;
    background-color: #fff;
    border: 3px solid #D9D9D9;
    border-radius: 15px;
    -webkit-transition: border .2s ease-in-out;
    -o-transition: border .2s ease-in-out;
    transition: border .2s ease-in-out;
    padding-left: 0px;
    padding-right: 0px;
}
.btn
{
    border-radius:0px;
}
.btn-update
{
    margin-left:15px;
}
CSS;

$this->registerCss($script);

?>
<div class="site-index">
    <p class="alert-success" style="display: flex">
        <?=
        Yii::$app->session->getFlash('sucess');
        ?>
    </p>
    <div class="container" id="tourpackages-carousel">
        <div class="row">
            <div class="col-lg-12">
                <h4>
                    <strong>
                        Usuários
                    </strong>
                </h4>
            </div>
            <?php
                foreach ($users as $user) {
                    ?>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                        <div class="thumbnail">
                            <div class="caption">
                                <div class='col-lg-12 text-primary'>
                                    <i class="glyphicon glyphicon-picture"> <?= sizeof($user->fotos)?></i>
                                    <i class="glyphicon glyphicon-download pull-right"> <?= sizeof($user->downloads)?></i>
                                </div>
                                <div class='col-lg-12 well well-add-card'>
                                    <h4>
                                        <strong>
                                            <?=$user->usu_nome ?>
                                        </strong>
                                    </h4>
                                </div>
                                <div class='col-lg-12'>
                                    <p>
                                        <strong>
                                            Email:
                                        </strong>
                                        <?= $user->usu_email ?>
                                    </p>
                                    <p>
                                        <strong>
                                            Login:
                                        </strong>
                                        <?= $user->usu_login ?>
                                    </p>
                                    <p>
                                        <strong>
                                            Nascimento:
                                        </strong>
                                        <?= $user->usu_data_nascimento ?>
                                    </p>
                                </div>

                                <?php
                                sizeof($user->fotos)>0 ? $class = 'btn btn-primary':$class = 'hidden';

                                echo Html::a(
                                    'Ver Fotos',
                                    ['foto/list'],
                                    ['class' => $class.' btn-xs btn-update btn-add-card',
                                    'data' => [
                                        'method' => 'post',
                                        'params'=>['id' => $user->usu_id]
                                    ],
                                    ]
                                );

                                echo Html::a(
                                    'Excluir Usuário',
                                    ['usuario/delete', 'id' => $user->usu_id],
                                    ['class' => 'btn btn-danger btn-xs btn-update btn-add-card',
                                        'data' => [
                                            'confirm' => 'Deseja excluir esta foto?',
                                            'method' => 'post',
                                        ],
                                    ]
                                );
                                echo Html::a(
                                    '<i class="glyphicon glyphicon-list"></i> Relatório',
                                    ['relatorio/usuario', 'id' => $user->usu_id],
                                    ['class' => $class.' btn-xs btn-update btn-add-card',
                                        'data' => [
                                            'confirm' => 'Deseja excluir esta foto?',
                                            'method' => 'post',
                                        ],
                                    ]
                                )
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div><!-- End row -->
    </div><!-- End container -->

</div>
