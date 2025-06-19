<?php

use app\models\Author;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Авторы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-index">

    <h1><?= Html::encode($this->title) ?></h1>

	<?php if (!Yii::$app->user->isGuest): ?>
    <p>
        <?= Html::a('Добавить автора', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?php endif; ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => array_merge(
			[
				'id',
				[
					'attribute' => 'full_name',
					'format' => 'raw',
					'value' => function($model) {
						return Html::a(
							Html::encode($model->full_name),
							['author/view', 'id' => $model->id],
							['title' => 'Просмотр автора']
						);
					},
					'headerOptions' => ['style' => 'width:90%'],
				],
				
			],
			(!Yii::$app->user->isGuest) ?
			[
				[
					'class' => ActionColumn::className(),
					'urlCreator' => function ($action, Author $model, $key, $index, $column) {
						return Url::toRoute([$action, 'id' => $model->id]);
					 }
				]
			] : []
		)
    ]); ?>
</div>
