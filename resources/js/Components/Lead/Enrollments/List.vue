<template>
    <div>
        <h3 class="text-base flex gap-2 items-center">
            <div class="p-1.5 rounded-full bg-green-600"></div>
            {{ __("Pending enrollment") }}
        </h3>
        <DataTable :simple-table="true" class="mt-5 intro-x">
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
                    {{ pendingEnrollment.id }}
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
                        <Link
                            :href="pendingEnrollment.next_edit_url"
                            class="flex items-center me-3 text-theme-20"
                        >
                            <Tippy
                                tag="span"
                                :content="__('Complete your enrollment')"
                            >
                                <LogInIcon class="w-4 h-4 me-2 font-semibold" />
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
        enrollments: Array,
    },
};
</script>
