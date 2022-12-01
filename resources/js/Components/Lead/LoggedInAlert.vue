<template>
    <div
        class="alert alert-primary show mb-12 !px-10"
        role="alert"
    >
        <div class="flex flex-col items-center justify-center">
            <div class="font-medium text-lg mb-2">
                {{ __("Lead account") }} {{ $page.props.auth.lead.email }}
            </div>
            <p class="text-base text-center">
                {{
                    __(
                        "You are logged in now. you can complete your enrollment, see all past enrollments, ask for support..."
                    )
                }}
            </p>
            <p class="text-base text-center">
                {{
                    __(
                        "If you are ever logged out. just enter your email. and you'll be automatically logged in"
                    )
                }}
            </p>
        </div>
    </div>
</template>
