<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 24 24"
    width="22"
    height="22"
    style="flex-shrink: 0"
    class="ml-2 fill-current"
    :class="customClass"
    @click="$emit('click')"
  >
    <path
      d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"
    />
  </svg>
</template>

<script>
export default {
  props: ['customClass'],
};
</script>
