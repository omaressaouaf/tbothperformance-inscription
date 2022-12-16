<template>
    <Head :title="__('Payment Successful')" />
    <div class="container py-5 mt-5">
        <div class="box py-16 px-4 flex flex-col items-center justify-center">
            <h2 class="text-lg font-semibold flex items-center gap-3">
                <CheckCircleIcon class="w-6 h-6 text-theme-20" />
                {{
                    __("Your payment for :enrollment was successful", {
                        enrollment: enrollment.label,
                    })
                }}
            </h2>
            <p class="text-md font-semibold mt-2 mb-2">
                {{
                    __(
                        "You will be contacted and receive an email shortly with confirmation of your order"
                    )
                }}
            </p>
            <Link
                class="btn btn-primary mt-3 me-3"
                :href="route('lead.dashboard')"
            >
                {{ __("Go back to dashboard") }}
            </Link>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        enrollment: Object,
    },
};
</script>
