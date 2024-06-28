<template>
  <div>
      Test
  </div>
</template>

<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ['resourceName', 'resourceId', 'field'],

  methods: {
    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
        // Do nothing because this is an invisible field
    },
  },
}
</script>
