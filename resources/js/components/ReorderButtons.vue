<template>
  <div class="flex items-center">
    <slot name="checkbox" />

    <div class="flex items-center ml-4" v-tooltip="reorderDisabledTooltip" v-if="resource.sortable">
      <div class="flex flex-col">
        <svg
          class="fill-current outline-none"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          width="12"
          height="12"
          @click="!reorderDisabled && $emit('moveToStart')"
          :class="{
            'cursor-pointer text-70 hover:text-80': !reorderDisabled,
            'cursor-default text-50': reorderDisabled,
          }"
          v-tooltip="moveToStartTooltip"
        >
          <path
            d="M13 5.41V21a1 1 0 0 1-2 0V5.41l-5.3 5.3a1 1 0 1 1-1.4-1.42l7-7a1 1 0 0 1 1.4 0l7 7a1 1 0 1 1-1.4 1.42L13 5.4z"
          />
        </svg>

        <svg
          class="fill-current outline-none"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          width="12"
          height="12"
          @click="!reorderDisabled && $emit('moveToEnd')"
          :class="{
            'cursor-pointer text-70 hover:text-80': !reorderDisabled,
            'cursor-default text-50': reorderDisabled,
          }"
          v-tooltip="moveToEndTooltip"
        >
          <path
            d="M11 18.59V3a1 1 0 0 1 2 0v15.59l5.3-5.3a1 1 0 0 1 1.4 1.42l-7 7a1 1 0 0 1-1.4 0l-7-7a1 1 0 0 1 1.4-1.42l5.3 5.3z"
          />
        </svg>
      </div>

      <svg
        v-if="resource.sortable"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        width="22"
        height="22"
        style="flex-shrink: 0;"
        class="ml-2 fill-current"
        :class="{
          'handle cursor-move text-70 hover:text-80': !reorderDisabled,
          'text-50 cursor-default': reorderDisabled,
        }"
        aria-labelledby="arrows"
        role="presentation"
      >
        <path
          d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z"
        />
      </svg>
    </div>
  </div>
</template>

<script>
export default {
  props: ['resource', 'reorderDisabled'],
  computed: {
    tooltipClasses() {
      return ['bg-white', 'px-3', 'py-2', 'rounded', 'border', 'border-50', 'shadow', 'text-sm', 'leading-normal'];
    },
    reorderDisabledTooltip() {
      return this.reorderDisabled
        ? {
            content: 'Reordering is disabled with active sort options.',
            classes: this.tooltipClasses,
            offset: 5,
            boundariesElement: document,
          }
        : void 0;
    },
    moveToStartTooltip() {
      return !this.reorderDisabled
        ? {
            content: 'Move to start',
            classes: this.tooltipClasses,
            offset: 5,
          }
        : void 0;
    },
    moveToEndTooltip() {
      return !this.reorderDisabled
        ? {
            content: 'Move to end',
            classes: this.tooltipClasses,
            offset: 5,
          }
        : void 0;
    },
  },
};
</script>
