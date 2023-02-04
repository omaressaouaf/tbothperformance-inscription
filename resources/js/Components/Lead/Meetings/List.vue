<template>
    <div class="mt-5">
        <div class="flex items-center justify-between">
            <h3
                class="text-base font-semibold text-gray-700 dark:text-gray-200 flex gap-2 items-center"
            >
                <CalendarIcon class="w-5 h-5" />
                {{ __("All meetings") }}
            </h3>
            <button @click="openCalendlyPopup" class="btn btn-primary">
                <PlusIcon class="w-4 h-4 me-2" />
                {{ __("Book a meeting") }}
            </button>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        isCalendlyEvent(e) {
            return (
                e.origin === "https://calendly.com" &&
                e.data.event &&
                e.data.event.indexOf("calendly.") === 0
            );
        },
        openCalendlyPopup() {
            Calendly.initPopupWidget({
                url: import.meta.env.VITE_CALENDLY_EMBEDED_URL,
                prefill: {
                    name: this.$page.props.auth.lead.full_name,
                    email: this.$page.props.auth.lead.email,
                    customAnswers: {
                        a1: this.$page.props.auth.lead.phone,
                    },
                },
            });
        },
        handleCalendlyEvent(e) {
            if (
                this.isCalendlyEvent(e) &&
                e.data.event === "calendly.event_scheduled"
            ) {
                this.$inertia.post(route("lead.meetings.store"), {
                    event_url: e.data.payload.event.uri,
                });
            }
        },
    },
    mounted() {
        window.addEventListener("message", this.handleCalendlyEvent);
    },
    beforeUnmount() {
        window.removeEventListener("message", this.handleCalendlyEvent);
    },
};
</script>
