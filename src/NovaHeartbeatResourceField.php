<?php

namespace MateuszPeczkowski\NovaHeartbeatResourceField;

use Laravel\Nova\Fields\Field;

class NovaHeartbeatResourceField extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-heartbeat-resource-field';

    public $showOnCreation = false;

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta([
            'indexName' => null,
            'interval'  => config('nova-heartbeat-resource-field.heartbeat_interval'),
        ]);
    }

    /**
     * Sets the resource id value displayed on the field.
     **/
    public function resourceId($id)
    {
        return $this->withMeta(['resourceId' => $id]);
    }
}
