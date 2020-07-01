<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the following fields to signup:</p>
    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
            <?= $form->field($model, 'first_name')->textInput(['autofocus' => true]) ?>
            <?= $form->field($model, 'last_name') ?>
            <?= $form->field($model, 'inn') ?>
            <?= $form->field($model, 'gender')->dropDownList([
                '0' => 'Female',
                '1' => 'Male'
            ]); ?>
            <?=
            $form->field($model, 'date_of_birth')->widget(DatePicker::className(), [
                'language' => 'en',
                'dateFormat' => 'yyyy-MM-dd',
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                    'yearRange' => '1940:2050',
                    'showOn' => 'button',
                    'buttonText' => 'Select date'
                ]
            ])
            ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <div class="form-group">
                <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>