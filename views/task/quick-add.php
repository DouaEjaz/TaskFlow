<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Task $model */
/** @var ActiveForm $form */
?>
<div class="task-quick-add">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'description') ?>
        <?= $form->field($model, 'due_date') ?>
        <?= $form->field($model, 'category_id') ?>
        <?= $form->field($model, 'status') ?>
        <?= $form->field($model, 'priority') ?>
        <?= $form->field($model, 'title') ?>
        <?= $form->field($model, 'created_at') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- task-quick-add -->
