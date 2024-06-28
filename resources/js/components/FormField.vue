<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            timeout: null,
        };
    },

    computed: {
        params() {
            return {
                resourceId: this.resourceId,
                resourceName: this.resourceName,
            };
        },
    },

    mounted() {
        this.storeOrUpdateHeartbeat();
        this.storeOrUpdateHeartbeatAction();
    },

    beforeUnmount() {
        if (this.timeout)
            clearTimeout(this.timeout);

        this.removeMyHeartbeats();
    },

    methods: {
        fill(formData) {
            // Do nothing because this is an invisible field
        },

        storeOrUpdateHeartbeatAction() {
            this.timeout = setTimeout(() => {
                this.storeOrUpdateHeartbeat();
                this.storeOrUpdateHeartbeatAction();
            }, this.field.interval);
        },

        async storeOrUpdateHeartbeat() {
            try {
                await Nova.request()
                    .post(`/nova-vendor/nova-heartbeat-resource-field/heartbeats`, this.params)
            } catch (error) {
                let msg = error.response.statusText;

                if (error.response.data && Array.isArray(error.response.data) && error.response.data.length > 0)
                    msg = error.response.data[0];

                return Nova.error(msg);
            }
        },

        async removeMyHeartbeats() {
            try {
                await Nova.request().delete(`/nova-vendor/nova-heartbeat-resource-field/heartbeats`, {
                    params: this.params,
                });
            } catch (error) {
                let msg = error.response.statusText;

                if (error.response.data && Array.isArray(error.response.data) && error.response.data.length > 0)
                    msg = error.response.data[0];

                return Nova.error(msg);
            }
        },
    },
}
</script>
