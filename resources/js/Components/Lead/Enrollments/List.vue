<template>
    <div v-if="pendingEnrollment" class="border-b py-5">
        <h3
            class="text-base font-semibold text-gray-700 dark:text-gray-200 flex gap-2 items-center"
        >
            <div class="p-1.5 rounded-full bg-green-600"></div>
            {{ __("Pending enrollment") }}
        </h3>
        <DataTable
            :simple-table="true"
            :length="false"
            :paginate="false"
            class="mt-5 intro-x"
        >
            <template #headings>
                <tr class="bg-gray-200 dark:bg-dark-2 text-gray-700">
                    <Th class="whitespace-nowrap">{{
                        __("Enrollment No.")
                    }}</Th>
                    <Th>{{ __("Course") }}</Th>
                    <Th>
                        {{ __("Financing") }}
                    </Th>
                    <Th></Th>
                </tr>
            </template>
            <Tr class="!bg-white dark:!bg-dark-3">
                <Td>
                    {{ pendingEnrollment.number }}
                </Td>
                <Td>
                    <span v-if="pendingEnrollment.course">
                        {{ pendingEnrollment.course?.title }}
                    </span>
                    <span v-else class="text-theme-21">{{
                        __("Not selected")
                    }}</span>
                </Td>
                <Td>
                    <Badge
                        v-if="pendingEnrollment.financing_type"
                        :class="[
                            pendingEnrollment.financing_type === 'cpf'
                                ? 'bg-primary-1'
                                : 'bg-theme-39 bg-opacity-100',
                        ]"
                        class="text-xs uppercase"
                    >
                        {{ __(pendingEnrollment.financing_type) }}
                    </Badge>
                    <span v-else class="text-theme-21">{{
                        __("Not selected")
                    }}</span>
                </Td>
                <Td>
                    <div class="flex">
                        <Component
                            :is="
                                pendingEnrollment.next_step == 5 ? 'a' : 'Link'
                            "
                            :href="pendingEnrollment.next_edit_url"
                            class="flex items-center me-3 text-theme-20"
                        >
                            <Tippy
                                tag="span"
                                :content="
                                    pendingEnrollment.next_step == 5
                                        ? __('Pay for your enrollment')
                                        : __('Complete your enrollment')
                                "
                            >
                                <LogInIcon class="w-4 h-4 me-2 font-semibold" />
                            </Tippy>
                        </Component>
                    </div>
                </Td>
            </Tr>
        </DataTable>
    </div>
    <div class="mt-5">
        <div class="flex items-center justify-between">
            <h3
                class="text-base font-semibold text-gray-700 dark:text-gray-200 flex gap-2 items-center"
            >
                <BookmarkIcon class="w-5 h-5" />
                {{ __("All enrollments") }}
            </h3>
            <Link
                v-if="!pendingEnrollment"
                :href="route('lead.enrollments.store')"
                method="post"
                as="button"
                class="btn btn-primary"
            >
                <PlusIcon class="w-4 h-4 me-2" />
                {{ __("New enrollment") }}
            </Link>
        </div>
        <DataTable
            v-if="enrollments.data.length"
            :simple-table="true"
            :pagination="{
                links: enrollments.links,
                from: enrollments.from,
                to: enrollments.to,
                total: enrollments.total,
                currentPage: enrollments.current_page,
            }"
            class="mt-5 intro-x"
        >
            <template #headings>
                <tr class="bg-gray-200 dark:bg-dark-2 text-gray-700">
                    <Th class="whitespace-nowrap">{{
                        __("Enrollment No.")
                    }}</Th>
                    <Th>{{ __("Course") }}</Th>
                    <Th>
                        {{ __("Financing") }}
                    </Th>
                    <Th>
                        {{ __("Status") }}
                    </Th>
                    <Th></Th>
                </tr>
            </template>
            <Tr
                v-for="enrollment in enrollments.data"
                :key="enrollment.id"
                class="!bg-white dark:!bg-dark-3"
            >
                <Td>
                    {{ enrollment.number }}
                </Td>
                <Td>
                    <span v-if="enrollment.course">
                        {{ enrollment.course?.title }}
                    </span>
                    <span v-else class="text-theme-21">{{
                        __("Not selected")
                    }}</span>
                </Td>
                <Td>
                    <Badge
                        v-if="enrollment.financing_type"
                        :class="[
                            enrollment.financing_type === 'cpf'
                                ? 'bg-primary-1'
                                : 'bg-theme-39 bg-opacity-100',
                        ]"
                        class="text-xs uppercase"
                    >
                        {{ __(enrollment.financing_type) }}
                    </Badge>
                    <span v-else class="text-theme-21">{{
                        __("Not selected")
                    }}</span>
                </Td>
                <Td>
                    <Badge
                        :class="{
                            'bg-gray-300 text-gray-800':
                                enrollment.status === 'pending',
                            'bg-orange-600':
                                enrollment.status === 'contract signed',
                            'bg-green-600': enrollment.status === 'complete',
                            'bg-theme-21': enrollment.status === 'canceled',
                        }"
                        class="text-xs capitalize"
                    >
                        {{ __(enrollment.status) }}
                    </Badge>
                </Td>
                <Td>
                    <div class="flex">
                        <Link
                            :href="enrollment.next_edit_url"
                            class="flex items-center me-3 text-theme-20"
                        >
                            <Tippy tag="span" :content="__('Details')">
                                <MoreHorizontalIcon
                                    class="w-4 h-4 me-2 font-semibold"
                                />
                            </Tippy>
                        </Link>
                    </div>
                </Td>
            </Tr>
        </DataTable>
    </div>
</template>

<script>
export default {
    props: {
        pendingEnrollment: Object,
        enrollments: Object,
    },
    mounted() {
        if (this.$page.props.flash.openCpfLink) {
            window.open(this.$page.props.flash.openCpfLink, "_blank");
        }
    },
};
</script>
