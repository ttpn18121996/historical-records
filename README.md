# Historical records

## General

Record the history of activities affecting the database in a simple way.

[`PHP v8.2`](https://php.net)

[`Laravel v11.x`](https://github.com/laravel/laravel)

## Content

- [Installation](#installation)
- [Basic usage](#basic-usage)
- [Save history](#save-history)
- [The model configuration used for the HistoryManager](#the-model-configuration-used-for-the-historymanager)
- [History cleanup](#history-cleanup)
- [Show user actions and locale](#show-user-actions-and-locale)
- [Configurations](#configurations)
  - [History retention period](#history-retention-period)
  - [Names of devices that will save history](#names-of-devices-that-will-save-history)

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
use HistoricalRecords\Contracts\Historyable;

class User extends Authenticatable implements Historyable
{
    use HasHistory;

    //...
}
```

## Save history

```php

use HistoricalRecords\HistoryManager;

/*
id: 1
name: Trinh Tran Phuong Nam
email: ttpn18121996@example.com
*/
$user = auth()->user();

$history = HistoryManager::save(
    historyable: $user,
    feature: 'users',
    keyword: 'create',
    payload: ['id' => 2, 'name' => 'Minh Nguyet', 'email' => 'minhnguyet@example.com'],
);

echo sprintf(__('history.'.$history->feature.'.'.$history->keyword.'.action'), $user->name);
// Trinh Tran Phuong Nam has created a user.
```

## The model configuration used for the HistoryManager

```php
use App\Models\History;
use HistoricalRecords\HistoryManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        HistoryManager::$modelName = History::class;
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
    'historyable_id' => 1,
    'historyable_type' => App\Models\User,
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
