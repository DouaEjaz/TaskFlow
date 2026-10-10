<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Category;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Task $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'todo' => 'Todo', 'in_progress' => 'In progress', 'done' => 'Done', ], ['prompt' => '', 'style' => 'max-width: 300px;']) ?>

    <?= $form->field($model, 'priority')->dropDownList([ 'low' => 'Low', 'medium' => 'Medium', 'high' => 'High', ], ['prompt' => '', 'style' => 'max-width: 300px;']) ?>

    <?= $form->field($model, 'due_date')->input('date', ['style' => 'max-width: 300px;']) ?>

    <?= $form->field($model, 'category_id')->dropDownList(
            ArrayHelper::map(Category::find()->all(), 'id', 'name'),
                ['prompt' => 'Select a category', 'style' => 'max-width: 300px;']
            )
    ?>
    

    <div class="form-group mt-3">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
