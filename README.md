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

## History cleanup

---

Run the command to delete history more than 30 days

```bash
php artisan historical-records:cleanup
```

If you want to specify the number of days to exceed to clear the history, pass the option `--time=`. Valid values:

```text
<number><d|days|m|months|y|years>
#Ex:
7d|7days => 7 days
1m|1months => 1 month
1y|1years => 1 year
```

```bash
php artisan historical-records:cleanup --time=14days
#OR
php artisan historical-records:cleanup -t 14d
```

## Configurations

Configuration parameters will be stored in the file `config/historical-records.php`.

### History retention period

You can configure the history period for cleaning. By default, history will be stored within 90 days.

```php
return [
    'history_expires' => 90, // days
    ...
];
```

### Names of devices that will save history

You can configure device names to save history.

```php
return [
    ...
    'device_name' => [ // device name that will be saved
        'phone' => 'phone',
        'tablet' => 'tablet',
        'desktop' => 'desktop',
        'default' => 'unknown',
    ],
];
```
