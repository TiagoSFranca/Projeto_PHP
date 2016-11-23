<?php

/* @var $this yii\web\View */

use yii\bootstrap\Html;

$this->title = 'Início';
$script = <<<CSS
.caption div {
    box-shadow: 0 0 5px #C8C8C8;
    transition: all 0.3s ease 0s;
}
.img-circle {
    border-radius: 50%;
}
.img-circle {
    border-radius: 0;
}

.ratio {
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 0;
    padding-bottom: 100%;
    position: relative;
    width: 100%;
}
.img-circle {
    border-radius: 50%;
}
.img-responsive {
    display: block;
    height: auto;
    max-width: 100%;
}
.thumbnail {
    position:relative;
    overflow:hidden;
}
 
.caption {
    position:absolute;
    top:0;
    right:0;
    background:rgba(13, 8, 19, 0.75);
    width:100%;
    height:100%;
    padding:2%;
    display: none;
    text-align: left;
    color:#fff !important;
    z-index:2;
}
.caption-center{
    display:flex;
    justify-content:center;
    align-items:center;
}

.marginalizado{
    margin-left: 1em;
}
.gi-2x{font-size: 1em;}
.gi-3x{font-size: 3em;}
.gi-4x{font-size: 4em;}
.gi-5x{font-size: 5em;}
CSS;
$js = " $(document).ready(function() {
    $(\"[rel='tooltip']\").tooltip();    
 
    $('.thumbnail').hover(
        function(){
            $(this).find('.caption').slideDown(250); //.fadeIn(250)
        },
        function(){
            $(this).find('.caption').slideUp(250); //.fadeOut(205)
        }
    ); 
 });";

$this->registerCss($script);
$this->registerJs($js);

?>
<div class="site-index">
    <p class="alert-success" style="display: flex">
        <?=
        Yii::$app->session->getFlash('sucess');
        ?>
    </p>
    <div class="container">
        <div class="row">
            <?php
            if(sizeof($modelFoto)>=1){
                foreach ($modelFoto as $foto){
                ?>
                <div class="col-sm-2">
                    <div class="thumbnail">
                        <div class="caption " href="">
                            <h5 class="text-center"><?= $foto->foto_nome?></h5>
                            <br>
                            <p class="text-center">
                                <i class="glyphicon glyphicon-eye-open gi-2x uneditable-input top-left"></i> <?= $foto->foto_views?>
                                <i class="glyphicon glyphicon-download gi-2x  uneditable-input"></i> <?= $foto->foto_downloads?>
                            </p>
                            <p  class="text-center">
                                <?=
                                Html::a(
                                    "Ver" ,
                                    ['foto/see', 'id' => $foto->foto_id],
                                    ['class' => 'btn btn-primary']
                                )
                                ?>
                                <?=
                                Html::a(
                                    " Download",
                                    ['foto/download', 'id' => $foto->foto_id],
                                    ['class' => 'btn btn-primary ']
                                )
                                ?>
                            </p>
                        </div>
                        <a class="ratio img-responsive img-circle" style="background-image: url(<?='../..'.$foto->foto_caminho?>);"></a>

                    </div>
                </div>
                <?php
                }
            }else{

                ?><i class="glyphicon glyphicon-info-sign gi-4x text-danger"></i>
                <h3 class="error text-danger">Não há fotos para exibir
                    <?php
                        if($parametro != null)
                            echo "com o Parâmetro de Pesquisa: $parametro"
                    ?>.</h3>
                <?php
            }
            ?>
        </div>
    </div>
</div>
