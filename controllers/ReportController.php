<?php

namespace app\controllers;

use app\models\Author;
use app\models\Book;

class ReportController extends \yii\web\Controller
{
    public function actionTopAuthors($year = null)
    {
        // Устанавливаем текущий год по умолчанию
		$currentYear = date('Y');
		$year = $year ?? $currentYear;
		
		// Проверка корректности года
		$year = (int)$year;
		if ($year < 1000 || $year > 9999) {
			Yii::$app->session->setFlash('error', 'Год должен быть в диапазоне 1000-9999');
			$year = $currentYear;
		}

		// Получаем данные
		$authors = Author::find()
			->select(['author.*', 'COUNT(book.id) as books_count'])
			->joinWith('books')
			->andWhere(['book.year' => $year])
			->groupBy('author.id')
			->orderBy(['books_count' => SORT_DESC])
			->limit(10)
			->asArray() // Получаем данные как массив
			->all();

		return $this->render('top-authors', [
			'authors' => $authors,
			'year' => $year,
		]);
    }
}
