<template>
  <span
    :class="class"
    class="rounded-full bg-opacity-95 px-2 py-0.5 text-gray-100 font-semibold text-sm"
  >
    <slot />
  </span>
</template>

<script>
export default {
  props: {
    class: {
      type: String,
      default: "",
    },
  },
};
</script>

<style>
</style>
