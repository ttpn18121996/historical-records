# Historical records

## General

Record the history of activities affecting the database in a simple way.

[`PHP v8.2`](https://php.net)

[`Laravel v11.x`](https://github.com/laravel/laravel)

## Installation

Install using composer:

```bash
composer require ttpn18121996/historical-records
```

Next, publish HistoricalRecords's resources using the `historical-records:install` command:

```bash
php artisan historical-records:install
```

## Save history

```php
/*
id: 1
name: Trinh Tran Phuong Nam
email: ttpn18121996@example.com
*/
$user = auth()->user();

$historyRepository = app(\HistoricalRecords\Contracts\HistoryRepository::class);
$history = $historyRepository->saveHistory(
    userId: $user->id,
    tableName: 'users',
    keyword: 'create',
    payload: ['id' => 2, 'name' => 'Minh Nguyet', 'email' => 'minhnguyet@example.com'],
);

echo sprintf(__('history.'.$history->table_name.'.'.$history->keyword.'.action'), $user->name);
// Trinh Tran Phuong Nam has created a user.
```

## Override the HistoryRepository

```php
use App\Repositories\HistoryRepository;
use HistoricalRecords\Contracts\HistoryRepository as HistoryRepositoryContract;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(HistoryRepositoryContract::class, HistoryRepository::class);
    }
}
```
