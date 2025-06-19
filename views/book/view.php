<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = '"' . $this->title . '"';
\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1>Книга: "<?= Html::encode($this->title) ?>"</h1>

	<?php if (!Yii::$app->user->isGuest): ?>
    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить эту книгу?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
	<?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
			[
				'label' => 'Автор',
				'format' => 'raw',
				'value' => function($model) {
					$authors = [];
					foreach ($model->authors as $author) {
						$authors[] = Html::a(Html::encode($author->full_name), ['author/view', 'id' => $author->id]);
					}
					return implode('<br>', $authors) ?: 'Автор не указаны';
				},
			],
            'year',
            'description:ntext',
            'isbn',
			[
                'attribute' => 'cover_image',
                'format' => 'html',
                'value' => function($model) {
                    return $model->cover_image 
                        ? Html::img($model->cover_image, ['style' => 'max-width: 300px; max-height: 300px;']) 
                        : 'Обложка отсутствует';
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
</div>
