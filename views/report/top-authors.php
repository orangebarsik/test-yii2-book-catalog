<?php
use yii\helpers\Html;
use yii\bootstrap\Alert;
use app\models\Book;

$this->title = 'ТОП 10 авторов за ' . $year . ' год';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="report-top-authors">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php // Вывод flash-сообщений ?>
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <?= Alert::widget([
            'options' => ['class' => 'alert-danger'],
            'body' => Yii::$app->session->getFlash('error'),
        ]) ?>
    <?php endif; ?>


    <div class="row">
        <div class="col-md-6">
            <?= Html::beginForm([''], 'get', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <?= Html::label('Год', 'year', ['class' => 'control-label']) ?>
                    <?= Html::input('number', 'year', $year, [
                        'class' => 'form-control',
                        'min' => 1000,
                        'max' => 9999,
                    ]) ?>
                </div>
                <?= Html::submitButton('Показать', ['class' => 'btn btn-primary']) ?>
            <?= Html::endForm() ?>
        </div>
    </div>
	
    <div class="report-results" style="margin-top: 20px;">
		<?php if (empty($authors)): ?>
			<div class="alert alert-warning">
				В <?= $year ?> году не найдено ни одной книги.
				<?php 
				$closeYears = Book::find()
					->select('year')
					->distinct()
					->orderBy(['ABS(year - '.$year.')' => SORT_ASC])
					->limit(3)
					->column();
				
				if (!empty($closeYears)): ?>
					<div style="margin-top: 10px;">
						Попробуйте: 
						<?php foreach ($closeYears as $y): ?>
							<?= Html::a($y, ['', 'year' => $y], ['class' => 'btn btn-xs btn-default']) ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php else: ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Автор</th>
                        <th>Количество книг</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($authors as $index => $author): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= Html::a(Html::encode($author['full_name']), ['author/view', 'id' => $author['id']]) ?></td>
                        <td><?= $author['books_count'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>