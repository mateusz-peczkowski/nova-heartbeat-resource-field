![NovaHeartbeatResourceField](https://github.com/mateusz-peczkowski/nova-heartbeat-resource-field/blob/master/media/cover.jpeg?raw=true)

# Heartbeat Resource Field

This package provides a custom Nova field that will like heartbeat monitor watch who is editing currently the resource.

On a listing/details there will be information who is currently editing and as well another person will be blocked from editing the same resource to not overlap changes.

Package is made for Laravel Nova ^4 and it works as well with Laravel Nova ^5.

<p style="margin-top: 32px;">
  <img src="https://github.com/mateusz-peczkowski/nova-heartbeat-resource-field/blob/master/media/img-1.png?raw=true" height="70" />
  <img src="https://github.com/mateusz-peczkowski/nova-heartbeat-resource-field/blob/master/media/img-2.png?raw=true" height="70" />
  <img src="https://github.com/mateusz-peczkowski/nova-heartbeat-resource-field/blob/master/media/img-3.png?raw=true" height="70" />
</p>

## Installation
You can install the package via composer:

```bash
composer require mateusz-peczkowski/nova-heartbeat-resource-field
```

### Migrations
After installation, you need to run the migration to create the table that will store the heartbeat data.

```bash
php artisan migrate
```

### Publish package (optional)
Publish the configuration file to customize the settings of the package (check bellow for more details).

```bash
php artisan vendor:publish --provider="MateuszPeczkowski\NovaHeartbeatResourceField\HeartbeatResourceServiceProvider"
```


## Configuration
The package provides a configuration file that allows you to customize the settings of the package.

```php
return [
    'table_name'            => 'nova_heartbeats',
    'heartbeat_model'       => \MateuszPeczkowski\NovaHeartbeatResourceField\Models\HeartbeatResource::class,
    'heartbeat_interval'    => env('NOVA_HEARTBEAT_INTERVAL', 5 * 1000), // 5 seconds
    'heartbeat_timeout'     => env('NOVA_HEARTBEAT_TIMEOUT', 60 * 1000), // 1 minute
    'heartbeat_guard'       => 'web',
    'heartbeat_guard_name'  => 'name',
    'heartbeat_guard_email' => 'email',
    'heartbeat_avatar_url'  => null,
];
```


## Usage

### Nova Resource Field
To install this field in your Nova resource, you need to add the following code to the `fields` method of your resource.

```php
use MateuszPeczkowski\NovaHeartbeatResourceField\NovaHeartbeatResourceField;

NovaHeartbeatResourceField::make('Heartbeat')
    ->resourceId($this->id),
```

Optionally you can allow to retake the resource by adding the following code

```php
use MateuszPeczkowski\NovaHeartbeatResourceField\NovaHeartbeatResourceField;

NovaHeartbeatResourceField::make('Heartbeat')
    ->resourceId($this->id)
    ->allowRetake(),
```

Then on details view you will have additional button to retake the resource.

### Nova Resource Trait
Add this trait to your Nova resource

```php
use MateuszPeczkowski\NovaHeartbeatResourceField\Traits\HasNovaHeartbeats;

class YourResource extends Resource
{
    use HasNovaHeartbeats;
}
```

### Model Trait
Add this trait to your Model

```php
use MateuszPeczkowski\NovaHeartbeatResourceField\Traits\HasHeartbeats;

class YourModel extends Model
{
    use HasHeartbeats;
}
```

### Clearing expired heartbeats (recommended)
In case of failure of removing the heartbeat, you can use the following command to remove all the heartbeats that are older than the timeout.

```bash
$schedule->command('heartbeat:clear-expired')->everyMinute();
```

