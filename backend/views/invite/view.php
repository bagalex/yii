<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Invite */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'User Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invite-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'email:email',
            'name',
            'sex',
            'location',
            'status',
            'invite_by_user',
            'sent_date',
            'registration_date:datetime',
        ],
    ]) ?>

</div>
