<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Author;

/** @var yii\web\View $this */
/** @var app\models\Book $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	
	 <?= $form->field($model, 'authorIds')->dropDownList(
        ArrayHelper::map(Author::find()->orderBy('full_name')->all(), 'id', 'full_name'),
        [
            'multiple' => true,
            'class' => 'form-select',
            'size' => 5
        ]
    )->hint('Для выбора нескольких авторов удерживайте Ctrl (Windows) или Command (Mac)') ?>

    <?= $form->field($model, 'year')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'isbn')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'coverImageFile')->fileInput() ?>
    
    <?php if (!$model->isNewRecord && $model->cover_image): ?>
        <div class="form-group">
            <label>Текущая обложка</label><br>
            <?= Html::img($model->cover_image, ['style' => 'max-width: 200px; max-height: 200px;']) ?>
            <?= Html::a('Удалить', ['delete-image', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-xs',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить это изображение?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    <?php endif; ?>
	 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
/* Стили для многострочного выбора */
select[multiple].form-select {
    height: auto;
    min-height: 120px;
    padding: .5rem;
}
select[multiple].form-select option {
    padding: .375rem .75rem;
    margin: 2px 0;
    border-radius: .25rem;
}
select[multiple].form-select option:checked {
    background-color: #0d6efd;
    color: white;
}
</style>
