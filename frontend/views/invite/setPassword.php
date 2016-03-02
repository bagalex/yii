<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\InviteForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invite-form-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->hiddenInput(['maxlength' => true, 'autofocus' => true, 'value' => $email])->label(false) ?>
    <?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'submit-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
