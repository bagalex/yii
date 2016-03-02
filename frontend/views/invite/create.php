<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\models\InviteForm */

$this->title = 'Invite Friend';
$this->params['breadcrumbs'][] = ['label' => 'Invite List', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invite-form-create">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>Enter the email to invite your friends:</p>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
