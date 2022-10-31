<template>
  <ul class="pagination">
    <li v-for="(link, index) in links" :key="index">
      <Link
        :href="link.url"
        :class="{ 'pagination__link--active': link.active }"
        class="pagination__link"
        preserve-state
      >
        <Component
          v-if="index === 0 || index === links.length - 1"
          :is="
            _locale !== 'ar'
              ? index === 0
                ? 'ChevronLeftIcon'
                : 'ChevronRightIcon'
              : index === 0
              ? 'ChevronRightIcon'
              : 'ChevronLeftIcon'
          "
          class="w-4 h-4"
        />
        <span v-else>{{ link.label }}</span>
      </LInk>
    </li>
  </ul>
</template>

<script>
export default {
  props: {
    links: Array,
  },

  methods: {
    navigate(url) {
      if (!url) return;

      let queryStrings = route().params;
      if (queryStrings.page) {
        delete queryStrings.page;
      }

      this.$inertia.get(url, {}, { preserveState: true });
    },
  },
};
</script>

<style>
</style>
