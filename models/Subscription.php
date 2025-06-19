<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%subscription}}".
 *
 * @property int $id
 * @property int $author_id
 * @property string $guest_phone
 * @property int $created_at
 *
 * @property Author $author
 */
class Subscription extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%subscription}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
		return [
			[['author_id', 'guest_phone'], 'required'],
			[['author_id', 'created_at'], 'integer'],
			[['guest_phone'], 'string', 'max' => 20],
			[['guest_phone'], 'match', 
				'pattern' => '/^\+7-\d{3}-\d{3}-\d{2}-\d{2}$/',
				'message' => 'Номер телефона должен быть в формате +7-XXX-XXX-XX-XX'
			],
			[['guest_phone'], 
				'unique',
				'targetAttribute' => ['author_id', 'guest_phone'],
				'message' => 'Вы уже подписаны на этого автора',
				'when' => function($model) {
					// Нормализуем телефон перед проверкой
					$model->guest_phone = self::normalizePhone($model->guest_phone);
					return true;
				}
			],
		];
    }

	public function beforeValidate()
	{
		if (parent::beforeValidate()) {
			// Удаляем все нецифровые символы, кроме +
			$phone = preg_replace('/[^\d+]/', '', $this->guest_phone);
			
			// Форматируем в нужный вид
			if (strlen($phone) >= 11) {
				$this->guest_phone = sprintf('+7-%s-%s-%s-%s',
					substr($phone, 2, 3),
					substr($phone, 5, 3),
					substr($phone, 8, 2),
					substr($phone, 10, 2)
				);
			}
			
			return true;
		}
		return false;
	}

	public function beforeSave($insert)
	{
		if (parent::beforeSave($insert)) {
			// Нормализуем телефон перед проверкой уникальности
			$this->guest_phone = self::normalizePhone($this->guest_phone);
			
			// Проверяем существующую подписку
			$exists = Subscription::find()
				->where([
					'author_id' => $this->author_id,
					'guest_phone' => $this->guest_phone
				])
				->exists();
				
			if ($exists) {
				$this->addError('guest_phone', 'Вы уже подписаны на этого автора с данным номером телефона');
				return false;
			}
			
			return true;
		}
		return false;
	}
	
	public static function normalizePhone($phone)
	{
		return preg_replace('/[^\d+]/', '', $phone);
	}		
	
	public function getPhoneForSms()
	{
		return str_replace('+', '', $this->guest_phone);
	}

	public function getFormattedPhone()
	{
		if (preg_match('/^\+7(\d{3})(\d{3})(\d{2})(\d{2})$/', $this->guest_phone, $matches)) {
			return sprintf('+7-%s-%s-%s-%s', $matches[1], $matches[2], $matches[3], $matches[4]);
		}
		return $this->guest_phone;
	}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Автор',
            'guest_phone' => 'Телефон',
            'created_at' => 'Дата подписки',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
	
	public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }
}
