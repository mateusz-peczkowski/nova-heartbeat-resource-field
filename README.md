![NovaHeartbeatResourceField](https://github.com/mateusz-peczkowski/nova-heartbeat-resource-field/blob/master/image.jpeg?raw=true)

# Heartbeat Resource Field

This package provides a custom Nova field that will like heartbeat monitor watch who is editing currently the resource.

On a listing/details there will be information who is currently editing and as well another person will be blocked from editing the same resource to not overlap changes.

Package is made for Laravel Nova ^4.


### Installation
You can install the package via composer:

```bash
composer require mateusz-peczkowski/nova-heartbeat-resource-field
```


After installation, you need to run the migration to create the table that will store the heartbeat data.
```bash
php artisan migrate
```


----


(optional) Publish the configuration file to customize the settings of the package (check bellow for more details).
```bash
php artisan vendor:publish --provider="MateuszPeczkowski\NovaHeartbeatResourceField\HeartbeatResourceServiceProvider"
```


#### Configuration
The package provides a configuration file that allows you to customize the field. You can set the interval in milliseconds between the heartbeat checks and the timeout after which the resource will be considered as not being edited anymore.

```php
return [
    'table_name'            => 'nova_heartbeats',
    'heartbeat_model'       => \MateuszPeczkowski\NovaHeartbeatResourceField\Models\HeartbeatResource::class,
    'heartbeat_interval'    => env('NOVA_HEARTBEAT_INTERVAL', 10 * 1000), // 10 seconds
    'heartbeat_timeout'     => env('NOVA_HEARTBEAT_TIMEOUT', 60 * 1000), // 1 minute
    'heartbeat_guard'       => 'web',
    'heartbeat_guard_name'  => 'name',
    'heartbeat_guard_email' => 'email',
    'heartbeat_avatar_url'  => null,
];
```


#### Usage
To install this field in your Nova resource, you need to add the following code to the `fields` method of your resource.

```php
use MateuszPeczkowski\NovaHeartbeatResourceField\NovaHeartbeatResourceField;

NovaHeartbeatResourceField::make('Heartbeat')
    ->resourceId($this->id),
```


And as well add trait to Nova resource model.

```php
use MateuszPeczkowski\NovaHeartbeatResourceField\Traits\HasNovaHeartbeats;

class YourResource extends Resource
{
    use HasNovaHeartbeats;
}
```


And one more on the model that you want to

```php
use MateuszPeczkowski\NovaHeartbeatResourceField\Traits\HasHeartbeats;

class YourModel extends Model
{
    use HasHeartbeats;
}
```

