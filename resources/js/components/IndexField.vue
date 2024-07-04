<template>
    <div>
        <Tooltip v-if="!loading && data && Object.keys(data).length" class="w-8">
            <img :src="data.created_by_avatar_url" :alt="data.created_by_name" class="rounded-full w-8 h-8">

            <template #content>
                <h6 class="text-center px-2 whitespace-nowrap uppercase text-gray-500 text-xs tracking-wide">{{ __('novaHeartbeatResourceField.resource_blocked') }}</h6>
                <p class="pt-2 text-center whitespace-nowrap text-xxs">{{ __('novaHeartbeatResourceField.resource_blocked_by') }} <strong>{{ data.created_by_name }}</strong></p>
                <p class="pt-1 text-center whitespace-nowrap text-xxs">{{ __('novaHeartbeatResourceField.resource_blocked_from') }} <strong>{{ dateFrom }}</strong></p>
            </template>
        </Tooltip>
    </div>
</template>

<script>
import moment from "moment-timezone";

export default {
    props: [
        'resourceName',
        'field'
    ],

    data() {
        return {
            loading: false,
            data: {},
            resourceId: this.field.resourceId,
        }
    },

    computed: {
        params() {
            return {
                resourceId: this.resourceId,
                resourceName: this.resourceName,
            };
        },
        dateFrom() {
            return moment(this.data.created_at).fromNow(true);
        }
    },

    mounted() {
        this.fetchHeartbeats();
    },

    methods: {
        async fetchHeartbeats() {
            this.loading = true;

            let dataForm = {};

            try {
                let {data} = await Nova.request()
                    .get(`/nova-vendor/nova-heartbeat-resource-field/heartbeats`, {
                        params: this.params,
                    })

                dataForm = data;
            } catch (error) {
                let msg = error.response.statusText;

                if (error.response.data && Array.isArray(error.response.data) && error.response.data.length > 0)
                    msg = error.response.data[0];

                return Nova.error(msg);
            }

            this.loading = false;

            if (typeof dataForm === 'object' && dataForm !== null)
                this.data = dataForm;
        },
    }
}
</script>
