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

## Basic usage

First, add the HistoricalRecords\Concerns\HasHistory trait to your User model(s):

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use HistoricalRecords\Concerns\HasHistory;

class User extends Authenticatable
{
    use HasHistory;

    //...
}
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
    historyable: $user,
    feature: 'users',
    keyword: 'create',
    payload: ['id' => 2, 'name' => 'Minh Nguyet', 'email' => 'minhnguyet@example.com'],
);

echo sprintf(__('history.'.$history->feature.'.'.$history->keyword.'.action'), $user->name);
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

## Show user actions and locale

We support 1 set of languages ​​to display user actions in the file `config/en/historical.php`.

```php
return [
    'users' => [
        'create' => [
            'title' => 'Create',
            'action' => '%s has created a user.',
        ],
        'update' => [
            'title' => 'Update',
            'action' => '%s has updated a user.',
        ],
        'delete' => [
            'title' => 'Delete',
            'action' => '%s has deleted a user.',
        ],
        'destroy' => [
            'title' => 'Force delete',
            'action' => '%s has hard deleted a user.',
        ],
        'restore' => [
            'title' => 'Restore',
            'action' => '%s has restored a user.',
        ],
        'login' => [
            'title' => 'Login',
            'action' => '%s has logged in.',
        ],
        'change_password' => [
            'title' => 'Change password',
            'action' => '%s has changed the login account password.',
        ],
        'update_profile' => [
            'title' => 'Update profile',
            'action' => '%s has updated the profile.',
        ],
        'email_verification' => [
            'title' => 'Email verification',
            'action' => '%s has verified the email.',
        ],
    ],
];
```

The model supports user action methods. We can rely on the language file to display user actions.

Suppose we have historical information

```php
/*
User [
    'id' => 1
    'name' => 'John Doe'
]
History [
    'feature' => 'users',
    'ketword' => 'create'
    'user_id' => 1
]
*/
$history = History::first();

echo $history->action_for_trans;

// historical.users.create.action
// :name has created a user.

__($history->action_for_trans, ['name' => $history->user->name]);

// John Doe has created a user
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
