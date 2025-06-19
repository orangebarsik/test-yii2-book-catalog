<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%book}}".
 *
 * @property int $id
 * @property string $title
 * @property int $year
 * @property string|null $description
 * @property string $isbn
 * @property string|null $cover_image
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Author[] $authors
 * @property BookAuthor[] $bookAuthors
 */
class Book extends \yii\db\ActiveRecord
{
	public $authorIds = []; // Для хранения выбранных авторов
    public $coverImageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'year', 'isbn'], 'required'],
            [['year'], 'integer'],
			[['description'], 'string'],
            [['title', 'cover_image'], 'string', 'max' => 255],
			[['description', 'cover_image'], 'default', 'value' => null],
            [['isbn'], 'string', 'max' => 20],
            [['isbn'], 'unique'],
			[['authorIds'], 'safe'],
            [['coverImageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'title' => 'Название',
            'year' => 'Год выпуска',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'cover_image' => 'Обложка',
            'authorIds' => 'Авторы',
            'coverImageFile' => 'Фото обложки',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * Gets query for [[Authors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])->viaTable('{{%book_author}}', ['book_id' => 'id']);
    }
		
	public function upload()
    {
        if ($this->coverImageFile) {
            $path = Yii::getAlias('@webroot/uploads/');
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            
            $filename = Yii::$app->security->generateRandomString() . '.' . $this->coverImageFile->extension;
            $filepath = $path . $filename;
            
            if ($this->coverImageFile->saveAs($filepath)) {
                // Удаляем старое изображение, если оно есть
                if ($this->cover_image && file_exists(Yii::getAlias('@webroot') . $this->cover_image)) {
                    unlink(Yii::getAlias('@webroot') . $this->cover_image);
                }
                
                $this->cover_image = '/uploads/' . $filename;
                return true;
            }
        }
        return false;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->coverImageFile = UploadedFile::getInstance($this, 'coverImageFile');
            if ($this->coverImageFile) {
                $this->upload();
            }
            return true;
        }
        return false;
    }

    public function afterDelete()
    {
        parent::afterDelete();
        // Удаляем файл изображения при удалении книги
        if ($this->cover_image && file_exists(Yii::getAlias('@webroot') . $this->cover_image)) {
            unlink(Yii::getAlias('@webroot') . $this->cover_image);
        }
    }
	
	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
		
		// Обновляем связи с авторами
		BookAuthor::deleteAll(['book_id' => $this->id]);
		
		if (!empty($this->authorIds)) {
			foreach ($this->authorIds as $authorId) {
				$relation = new BookAuthor();
				$relation->book_id = $this->id;
				$relation->author_id = $authorId;
				$relation->save();
			}
			
			// Отправляем уведомления только для новых книг
			if ($insert) {
				$this->notifySubscribers();
			}
		}
	}
	
	public function afterFind()
	{
		parent::afterFind();
		
		// Загружаем авторов только если свойство пустое
		// (чтобы не перезаписывать данные из формы)
		if (empty($this->authorIds)) {
			$this->authorIds = $this->getAuthors()->select('id')->column();
		}
	}
	
	protected function notifySubscribers()
	{
		$authorIds = $this->authorIds;
		if (empty($authorIds)) {
			return;
		}

		// Получаем подписки без дубликатов телефонов
		$subscriptions = Subscription::find()
			->where(['author_id' => $authorIds])
			->groupBy('guest_phone') // Исключаем дубликаты номеров
			->all();

		foreach ($subscriptions as $subscription) {
			$this->sendSmsNotification($subscription, $this);
		}
	}

	protected function sendSmsNotification($subscription, $book)
	{
		$message = "Новая книга автора: {$book->title} ({$book->year})";
		$phone = $subscription->getPhoneForSms();
		
		$url = 'http://smspilot.ru/api.php?' . http_build_query([
			'send' => $message,
			'to' => $phone,
			'apikey' => Yii::$app->params['smspilotApiKey'],
			'format' => 'json'
		]);
		
		$result = @file_get_contents($url);
		
		// Логируем результат отправки
		Yii::info("SMS отправка на {$phone}: {$result}", 'sms-notification');
		
		if (strpos($result, 'ERROR') === 0) {
			Yii::error("Ошибка отправки SMS на {$phone}: {$result}", 'sms-notification');
		}
	}
	
	public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }
}
