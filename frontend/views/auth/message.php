<?php
use yii\helpers\Html;
?>
Please activate your account by clicking the link below.<a href="http://<?= Html::encode($_SERVER['SERVER_NAME']) ?>/auth/confirm/<?= Html::encode($link) ?>">confirm my account</a>