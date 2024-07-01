<template>
    <div class="flex flex-col -mx-6 px-6 py-2 space-y-2 md:flex-row @sm/peekable:flex-row @md/modal:flex-row md:py-0 @sm/peekable:py-0 @md/modal:py-0 md:space-y-0 @sm/peekable:space-y-0 @md/modal:space-y-0" v-if="!loading && data && Object.keys(data).length">
        <div class="md:w-1/4 @sm/peekable:w-1/4 @md/modal:w-1/4 md:py-3 @sm/peekable:py-3 @md/modal:py-3"><h4 class="font-normal @sm/peekable:break-all">
            <span class="block py-1.5">{{ __('novaHeartbeatResourceField.resource_currently_editing') }}</span></h4>
        </div>
        <div class="break-all md:w-3/4 @sm/peekable:w-3/4 @md/modal:w-3/4 md:py-3 @sm/peekable:py-3 md/modal:py-3 lg:break-words @md/peekable:break-words @lg/modal:break-words flex items-center">
            <img :src="data.created_by_avatar_url" :alt="data.created_by_name" class="rounded-full w-8 h-8">
            <strong class="pl-3">{{ data.created_by_name }}</strong>
            <span class="pl-1">({{ __('novaHeartbeatResourceField.resource_blocked_from_details') }} {{ dateFrom }})</span>
            <p class="pl-1" v-if="field.allowRetake">
                -
                <button v-on:click.prevent="showModal = true;" class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center border-transparent text-primary-500 hover:[&:not(:disabled)]:text-primary-400">{{ __('novaHeartbeatResourceField.resource_blocked_retake') }}</button>
            </p>
        </div>

        <teleport to="body">
            <Modal
                :show="showModal && field.allowRetake"
                role="dialog"
                size="sm"
            >
                <div class="mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <ModalHeader v-html="__('novaHeartbeatResourceField.resource_blocked_retake_modal_title')"/>

                    <div class="py-3 px-8">
                        <p class="leading-normal" v-html="__('novaHeartbeatResourceField.resource_blocked_retake_modal_body')"></p>
                    </div>

                    <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 flex">
                        <div class="ml-auto">
                            <button v-on:click.prevent="showModal = false;" class="mr-3 appearance-none bg-transparent font-bold text-gray-400 hover:text-gray-300 active:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 dark:active:text-gray-600 dark:hover:bg-gray-800 mr-3 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 mr-3 appearance-none bg-transparent font-bold text-gray-400 hover:text-gray-300 active:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 dark:active:text-gray-600 dark:hover:bg-gray-800 mr-3">{{ __('novaHeartbeatResourceField.resource_blocked_retake_modal_cancel_button')}}</button>
                            <button v-on:click.prevent="retakeResource" class="border text-left appearance-none cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 relative disabled:cursor-not-allowed inline-flex items-center justify-center shadow h-9 px-3 bg-red-500 border-red-500 hover:[&:not(:disabled)]:bg-red-400 hover:[&:not(:disabled)]:border-red-400 text-white dark:text-red-950">{{ __('novaHeartbeatResourceField.resource_blocked_retake_modal_submit_button')}}</button>
                        </div>
                    </div>
                </div>
            </Modal>
        </teleport>
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
            showModal: false,
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

        async retakeResource() {
            this.showModal = false;
            this.loading = true;

            let dataForm = {};

            try {
                let {data} = await Nova.request()
                    .patch(`/nova-vendor/nova-heartbeat-resource-field/heartbeats`, this.params)

                dataForm = data;
            } catch (error) {
                let msg = error.response.statusText;

                if (error.response.data && Array.isArray(error.response.data) && error.response.data.length > 0)
                    msg = error.response.data[0];

                this.loading = false;

                return Nova.error(msg);
            }

            this.loading = false;

            if (typeof dataForm === 'object' && dataForm !== null)
                return Nova.visit(dataForm.redirect_url);
        },
    }
}
</script>
