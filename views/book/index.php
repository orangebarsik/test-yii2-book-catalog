<?php

use app\models\Book;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php if (!Yii::$app->user->isGuest): ?>
    <p>
        <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?php endif; ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => array_merge(
			[
				'id',
				[
					'attribute' => 'title',
					'format' => 'raw',
					'value' => function($model) {
						return Html::a(
							Html::encode($model->title),
							['book/view', 'id' => $model->id],
							['title' => 'Просмотр книги']
						);
					},
					'headerOptions' => ['style' => 'width:30%'],
				],
				[
					'label' => 'Автор',
					'format' => 'raw',
					'value' => function($model) {
						$authorsLinks = [];
						foreach ($model->authors as $author) {
							$authorsLinks[] = Html::a(
								Html::encode($author->full_name),
								['author/view', 'id' => $author->id],
								['title' => 'Перейти к автору']
							);
						}
						return implode('<br>', $authorsLinks) ?: '—';
					},
					'contentOptions' => ['style' => 'max-width: 200px;'],
				],
				'year',
				'isbn',
				[
					'attribute' => 'cover_image',
					'format' => 'html',
					'value' => function($model) {
						return $model->cover_image 
							? Html::img($model->cover_image, ['style' => 'max-width: 150px; max-height: 210px;']) 
							: 'Нет обложки';
					},
					'contentOptions' => ['style' => 'text-align: center;'],
					'headerOptions' => ['style' => 'width: 120px;'],
				],
			], 
			(!Yii::$app->user->isGuest) ?
			[
				[
					'class' => ActionColumn::className(),
					'urlCreator' => function ($action, Book $model, $key, $index, $column) {
						return Url::toRoute([$action, 'id' => $model->id]);
					 }
				]
			] : []
		)
    ]); ?>
</div>
