<h1>Тестовое задание "Каталог Книг"</h1>
<p>Скворцов Владимир (тестовое задание для ИнфоТек)</p>
<strong>Демо версия</strong> [test6.barsiklb.ru](https://test6.barsiklab.ru)

```
Для проверки работы:

Гость
Без авторизации

Admin
Логин:  admin
Пароль: 12345


Тестовое задание:

Необходимо сделать на фреймворке Yii2 + MySQL каталог книг. Книга может иметь несколько авторов. Тестовое задание можно делать без верстки. 
1. Книга - название, год выпуска, описание, isbn, фото главной страницы.
2. Авторы - ФИО.

Права на доступ:
1. Гость - только просмотр + подписка на новые книги автора.
2. Юзер - просмотр, добавление, редактирование, удаление. (CRUD). Отчет без разницы.
Отчет - ТОП 10 авторов выпуствиших больше книг за какой-то год.
ПЛЮСОМ БУДЕТ
Уведомление о поступлении книг из подписки должно отправляться на смс гостю.
https://smspilot.ru/
там "Для тестирования можно использовать ключ эмулятор (реальной отправки SMS не происходит)."

Ответы на часто задаваемые вопросы/пожелания к выполнению:
1)	Нужно сделать web приложение? Не API? – web
2)	Нужна авторизация? – Да
3)	Отчет нужен как PDF? или как отдельная страница? Если отдельная страница или PDF, то кто имеет право ее видеть? - Отдельная страница, доступ для всех
4)	Нужен функционал администратора, который может управлять подпиской/отпиской? – не нужен
5)	Как осуществляется отписка от новых поступлений? – это не требуется
6)	По тестовому заданию нужна БД MySQL. Можно прислать в виде файлов, но без директорий runtime и vendor. Дамп БД не нужен, нужны миграции. 
7)	Пример кода будут запускать локально или смотреть по коду? – просто смотреть.  
8)	Какие версии PHP и СУБД использовать? – PHP 8+, MySQL/MariaDB
9)	Какой шаблон yii2 использовать advanced или basic? – любой, который Вы сочтёте более подходящим под эту задачу
10)	 Для rbac использовать phpManager или dbManager? – не принципиально 
11)	 Выполненное тестовое прислать архивом или ссылкой (GitHub, Bitbucket и т.п.) – любым удобным для Вас способом. 
```




<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic Project Template</h1>
    <br>
</p>

Yii 2 Basic Project Template is a skeleton [Yii 2](https://www.yiiframework.com/) application best for
rapidly creating small projects.

The template contains the basic features including user login/logout and a contact page.
It includes all commonly used configurations that would allow you to focus on adding new
features to your application.

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![build](https://github.com/yiisoft/yii2-app-basic/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-basic/actions?query=workflow%3Abuild)

DIRECTORY STRUCTURE
-------------------

```
      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources
```
