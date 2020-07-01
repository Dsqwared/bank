<?php

/* @var $this yii\web\View */

use app\models\Transactions;
use yii\helpers\Html;

$this->title = 'Loss or profit of the bank';

$data = Transactions::getReportProfit();
?>
<div class="site-profit">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?=  json_encode($data); ?>
    </p>

</div>
