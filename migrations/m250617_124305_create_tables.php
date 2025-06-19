<?php

use yii\db\Migration;

class m250617_124305_create_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
		// Таблица авторов
		$this->createTable('{{%author}}', [
			'id' => $this->primaryKey(),
			'full_name' => $this->string(255)->notNull(),
			'created_at' => $this->integer()->notNull(),
			'updated_at' => $this->integer()->notNull(),
		]);

		// Таблица книг
		$this->createTable('{{%book}}', [
			'id' => $this->primaryKey(),
			'title' => $this->string(255)->notNull(),
			'year' => $this->integer()->notNull(),
			'description' => $this->text(),
			'isbn' => $this->string(20)->notNull()->unique(),
			'cover_image' => $this->string(255),
			'created_at' => $this->integer()->notNull(),
			'updated_at' => $this->integer()->notNull(),
		]);

		// Связующая таблица книга-автор
		$this->createTable('{{%book_author}}', [
			'book_id' => $this->integer()->notNull(),
			'author_id' => $this->integer()->notNull(),
			'PRIMARY KEY(book_id, author_id)',
		]);

		// Таблица подписок
		$this->createTable('{{%subscription}}', [
			'id' => $this->primaryKey(),
			'author_id' => $this->integer()->notNull(),
			'guest_phone' => $this->string(20)->notNull(),
			'created_at' => $this->integer()->notNull(),
		]);

		// Внешние ключи
		$this->addForeignKey(
			'fk_book_author_book_id',
			'{{%book_author}}',
			'book_id',
			'book',
			'id',
			'CASCADE'
		);

		$this->addForeignKey(
			'fk_book_author_author_id',
			'{{%book_author}}',
			'author_id',
			'author',
			'id',
			'CASCADE'
		);

		$this->addForeignKey(
			'fk_subscription_author_id',
			'{{%subscription}}',
			'author_id',
			'author',
			'id',
			'CASCADE'
		);
	}

	/**
	* {@inheritdoc}
	*/
	public function safeDown()
	{
		$this->dropTable('{{%subscription}}');
		$this->dropTable('{{%book_author}}');
		$this->dropTable('{{%book}}');
		$this->dropTable('{{%author}}');
    }
}
