<?php
use yii\helpers\Html;
?>

<a href="http://<?= Html::encode($_SERVER['SERVER_NAME']) ?>/invite/confirm/<?= Html::encode($link) ?>">I invite you to register</a>
