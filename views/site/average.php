<?php

/* @var $this yii\web\View */

use app\models\Deposits;
use yii\helpers\Html;

$this->title = 'Average deposit amount';

$data = Deposits::getReportAverage();

?>
<div class="site-average">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?=  json_encode($data); ?>
    </p>

</div>
