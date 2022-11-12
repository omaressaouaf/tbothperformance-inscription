<template>
    <Head :title="__('Enroll')" />

    <div class="mb-10">
        <div class="mx-auto">
            <EnrollmentsWizard>
                <div class="font-medium text-base">Profile Settings</div>
                <div class="grid grid-cols-12 gap-4 gap-y-5 mt-5">
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <label for="input-wizard-1" class="form-label"
                            >From</label
                        >
                        <input
                            id="input-wizard-1"
                            type="text"
                            class="form-control"
                            placeholder="example@gmail.com"
                        />
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <label for="input-wizard-2" class="form-label"
                            >To</label
                        >
                        <input
                            id="input-wizard-2"
                            type="text"
                            class="form-control"
                            placeholder="example@gmail.com"
                        />
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <label for="input-wizard-3" class="form-label"
                            >Subject</label
                        >
                        <input
                            id="input-wizard-3"
                            type="text"
                            class="form-control"
                            placeholder="Important Meeting"
                        />
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <label for="input-wizard-4" class="form-label"
                            >Has the Words</label
                        >
                        <input
                            id="input-wizard-4"
                            type="text"
                            class="form-control"
                            placeholder="Job, Work, Documentation"
                        />
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <label for="input-wizard-5" class="form-label"
                            >Doesn't Have</label
                        >
                        <input
                            id="input-wizard-5"
                            type="text"
                            class="form-control"
                            placeholder="Job, Work, Documentation"
                        />
                    </div>
                    <div class="intro-y col-span-12 sm:col-span-6">
                        <label for="input-wizard-6" class="form-label"
                            >Size</label
                        >
                        <select id="input-wizard-6" class="form-select">
                            <option>10</option>
                            <option>25</option>
                            <option>35</option>
                            <option>50</option>
                        </select>
                    </div>
                </div>
            </EnrollmentsWizard>
        </div>
    </div>
</template>

<script>
import EnrollmentsWizard from "@/Components/Leads/Enrollments/Wizard.vue";

export default {
    components: {
        EnrollmentsWizard,
    },
};
</script>
