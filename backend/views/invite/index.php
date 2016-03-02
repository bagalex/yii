<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\InviteSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invitation List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invite-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns'      => [
            ['class' => 'yii\grid\SerialColumn'],
            'email:email',
            [
                'attribute' => 'status',
                'format'    => 'raw',
                'value'     => function ($model, $key, $index, $column) {
                    /** @var User $model */
                    /** @var \yii\grid\DataColumn $column */
                    $value = $model->{$column->attribute};

                    switch ($value) {
                        case User::STATUS_ACTIVE:
                            $class = 'success';
                            break;
                        case User::STATUS_DELETED:
                            $class = 'danger';
                            break;
                        case User::STATUS_INVITED:
                            $class = 'default';
                            break;
                        case User::STATUS_BLOCKED:
                        default:
                            $class = 'warning';
                    };
                    $html = Html::tag('span', Html::encode($value), ['class' => 'label label-' . $class]);

                    return $html;
                }
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{view} {block} {delete}',
                'buttons'  => [
                    'block' => function ($url, $model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-remove-sign"></span></span>',
                            $url);
                    },
                ],
            ],

        ],
    ]); ?>

</div>
