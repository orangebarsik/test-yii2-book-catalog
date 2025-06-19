<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Подписаться на автора: ' . $author->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $author->full_name, 'url' => ['view', 'id' => $author->id]];
$this->params['breadcrumbs'][] = 'Подписаться';

?>

<div class="author-subscribe">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="author-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'guest_phone')->widget(\yii\widgets\MaskedInput::class, [
			'mask' => '+7-999-999-99-99',
			'options' => [
				'class' => 'form-control phone-input',
				'placeholder' => '+7-XXX-XXX-XX-XX',
				'maxlength' => 16, // +7-XXX-XXX-XX-XX = 16 символов
			],
			'clientOptions' => [
				'clearIncomplete' => true,
				'showMaskOnHover' => false,
			]
		]) ?>

        <div class="form-group">
            <?= Html::submitButton('Подписаться', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>