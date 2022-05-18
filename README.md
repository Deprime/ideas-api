
# Test idea app - api

This api app based on **Laravel** framework. This app provides list of 10KK ideas and REST api for it.

## Requirements

- PHP ^8.0.2
- Composer
- Postgres DB

## Installation

- clone git repo
- open terminal and navigate to repo directory
- make `composer i`

## Setup

- In postgres create a database which will be used in by this app
- make a copy of `.env.sample` environment cofig file and rename it to `.env`
- In `.env` change next variabales according to your Postgres settings for created DB:
  - `DB_DATABASE`
  - `DB_USERNAME`
  - `DB_PASSWORD`
- If your Postgres instance has special port or host, you can provide this settings via next env variables:
  - `DB_HOST`
  - `DB_PORT`

## DB Migration and seeding

After installation and setuo you can run migrations to create DB structure:

Open terminal, navigate to repo directory and run:

```console
php artisan migrate
```

Since the app has 10KK records planned in the **idea** table, the process of filling in the table will take about 15 minutes (most of seeding time consumed by Faker).

```console
php artisan db:seed
```

## Running server

```console
php artisan serve
```

In basic variant server will provide address `127.0.0.1:8000`

Now you can access to your ideas api

## List of records

**GET** `http://127.0.0.1:8000/api/v1/ideas`

You can use navigationa by page by passing `page` param.

**GET** `http://127.0.0.1:8000/api/v1/ideas?page=10`

## Get single record

**GET** `http://127.0.0.1:8000/api/v1/ideas/{id}`

## Create record

I didn't have enough time to implement authorization (active vacation), so only a **title** is needed to create a record. The app will generate other attributes of the record on its own.

**POST** `http://127.0.0.1:8000/api/v1/ideas`

```json
// Payload
{
  "title": "Create a new title please"
}
```

## Update record

**POST** `http://127.0.0.1:8000/api/v1/ideas/{id}`

```json
// Payload
{
  "title": "I will update this title"
}
```

## Delete record

> Тут я напишу от себя: я слукавил. В текущей реализации я не нашел универсального решения, которое бы поддерживало бу физическое удаление записей и обеспечивало бы консистентность данных во время постраничной навигации.
> 
> Я изучил материал по этому поводу (ну хотя бы этот базис https://habr.com/ru/post/301044/) и выбрал наименьшее из зол - Пагинация по набору ключей для упорядоченных запросов.
> 
> По этому удаление реализовано через soft delete - записи просто помечаются, как удаленные.

**DELETE** `http://127.0.0.1:8000/api/v1/ideas/{id}`

## Restore record

Since soft deletion is implemented, I decided to provide the possibility of restoring records.

**PATCH** `http://127.0.0.1:8000/api/v1/ideas/{id}`
