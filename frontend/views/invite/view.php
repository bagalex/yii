<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\InviteForm */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Invite List', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invite-form-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <h1><?= Html::encode($model->name) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'email:email',
            'status',
            'sent_date:datetime',
            'registration_date:datetime',
        ],
    ]) ?>

</div>
