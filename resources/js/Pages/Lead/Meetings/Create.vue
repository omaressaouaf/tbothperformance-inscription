<template>
    <Head :title="__('Book a meeting')" />
    <div class="pt-5 sm:pt-14 mt-5 shadow rounded-xl mb-10 min-h-[90vh]">
        <div
            class="text-gray-700 dark:text-gray-300 text-center text-3xl px-2 md:px-0"
        >
            {{ __("Book a meeting") }}
        </div>
        <div id="calendly" style="min-width: 320px; height: 100vh"></div>
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
    },
    mounted() {
        Calendly.initInlineWidget({
            url: import.meta.env.VITE_CALENDLY_EMBEDED_URL,
            parentElement: document.getElementById("calendly"),
            prefill: {
                name: this.$page.props.auth.lead.full_name,
                email: this.$page.props.auth.lead.email,
            },
        });

        window.addEventListener("message", (e) => {
            if (this.isCalendlyEvent(e)) {
                console.log(e.data);
            }
        });
    },
};
</script>
