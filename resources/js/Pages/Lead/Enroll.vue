<template>
    <Head :title="__('Enroll')" />

    <div class="mb-10">
        <div class="mx-auto max-w-2xl">
            <div
                class="box pb-8 pt-8 sm:pb-12 sm:pt-10 mt-5 shadow rounded-xl px-5 sm:px-14"
            >
                <h2 class="text-lg mb-3 font-bold">
                    {{ __("Start your enrollment") }}
                </h2>
                <div class="text-gray-600 text-sm font-semibold">
                    {{
                        __(
                            "Send your request and complete your enrollment with ease"
                        )
                    }}
                </div>
                <hr class="mt-6 mb-8" />
                <LeadCreateEdit />
            </div>
        </div>
    </div>
</template>

<script>
import LeadCreateEdit from "@/Components/Lead/CreateEdit.vue";

export default {
    components: {
        LeadCreateEdit,
    },
};
</script>
