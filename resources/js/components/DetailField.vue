<template>
    <div class="flex flex-col -mx-6 px-6 py-2 space-y-2 md:flex-row @sm/peekable:flex-row @md/modal:flex-row md:py-0 @sm/peekable:py-0 @md/modal:py-0 md:space-y-0 @sm/peekable:space-y-0 @md/modal:space-y-0" v-if="!loading && data && Object.keys(data).length">
        <div class="md:w-1/4 @sm/peekable:w-1/4 @md/modal:w-1/4 md:py-3 @sm/peekable:py-3 @md/modal:py-3"><h4 class="font-normal @sm/peekable:break-all">
            <span class="block py-1.5">{{ __('novaHeartbeatResourceField.resource_currently_editing') }}</span></h4>
        </div>
        <div class="break-all md:w-3/4 @sm/peekable:w-3/4 @md/modal:w-3/4 md:py-3 @sm/peekable:py-3 md/modal:py-3 lg:break-words @md/peekable:break-words @lg/modal:break-words flex items-center">
            <img :src="data.created_by_avatar_url" :alt="data.created_by_name" class="rounded-full w-8 h-8">
            <strong class="pl-3">{{ data.created_by_name }}</strong>
            <span class="pl-1">({{ __('novaHeartbeatResourceField.resource_blocked_from_details') }} {{ dateFrom }})</span>
        </div>
    </div>
</template>

<script>
import moment from "moment-timezone";

export default {
    props: [
        'index',
        'resource',
        'resourceName',
        'resourceId',
        'field'
    ],

    data() {
        return {
            loading: false,
            data: {},
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
        this.removeMyHeartbeats();
    },

    methods: {
        async fetchHeartbeats() {
            this.loading = true;

            let {data} = await Nova.request().get(`/nova-vendor/nova-heartbeat-resource-field/heartbeats`, {
                params: this.params,
            });

            this.loading = false;

            if (typeof data === 'object' && data !== null)
                this.data = data;
        },

        async removeMyHeartbeats() {
            await Nova.request().delete(`/nova-vendor/nova-heartbeat-resource-field/heartbeats`, {
                params: this.params,
            });
        },
    }
}
</script>
