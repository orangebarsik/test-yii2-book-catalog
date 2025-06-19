<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Author $model */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="author-view">

    <h1>Автор: <?= Html::encode($this->title) ?></h1>

	<?php if (!Yii::$app->user->isGuest): ?>
    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этого автора?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
	<?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
			[
				'label' => 'Книги',
				'format' => 'raw',
				'value' => function($model) {
					$books = [];
					foreach ($model->books as $book) {
						$books[] = Html::a(Html::encode($book->title), ['book/view', 'id' => $book->id]);
					}
					return implode('<br>', $books) ?: 'Книги не указаны';
				},
			],
            [
				'attribute' => 'created_at',
				'format' => 'raw',
				'value' => function($data){
					return Yii::$app->formatter->asDatetime($data->created_at);
				}
			],
			[
				'attribute' => 'updated_at',
				'format' => 'raw',
				'value' => function($data){
					return Yii::$app->formatter->asDatetime($data->updated_at);
				}
			],
        ],
    ]) ?>
	<?= Html::a('Подписаться на новые книги автора', ['author/subscribe', 'author_id' => $model->id], ['class' => 'btn btn-info']); ?>
</div>