<template>
  <div class="flex items-center">
    <slot name="checkbox" />

    <div class="flex items-center ml-4" v-tooltip="reorderDisabledTooltip" v-if="resource.sortable">
      <div class="flex flex-col">
        <chevron-up-icon
          @click="!reorderDisabled && $emit('moveToStart')"
          :class="{
            'cursor-pointer text-70 hover:text-80': !reorderDisabled,
            'cursor-default text-50': reorderDisabled,
          }"
          v-tooltip="moveToStartTooltip"
        />

        <chevron-down-icon
          @click="!reorderDisabled && $emit('moveToEnd')"
          :class="{
            'cursor-pointer text-70 hover:text-80': !reorderDisabled,
            'cursor-default text-50': reorderDisabled,
          }"
          v-tooltip="moveToEndTooltip"
        />
      </div>

      <burger-icon
        v-if="resource.sortable"
        :class="{
          'handle cursor-move text-70 hover:text-80': !reorderDisabled,
          'text-50 cursor-default': reorderDisabled,
        }"
      />
    </div>
  </div>
</template>

<script>
import ChevronUpIcon from '../icons/ChevronUpIcon';
import ChevronDownIcon from '../icons/ChevronDownIcon';
import BurgerIcon from '../icons/BurgerIcon';

export default {
  components: { ChevronUpIcon, ChevronDownIcon, BurgerIcon },
  props: ['resource', 'reorderDisabled'],
  computed: {
    tooltipClasses() {
      return ['bg-white', 'px-3', 'py-2', 'rounded', 'border', 'border-50', 'shadow', 'text-sm', 'leading-normal'];
    },
    reorderDisabledTooltip() {
      return this.reorderDisabled
        ? {
            content: this.__('novaSortable.reorderingDisabledTooltip'),
            classes: this.tooltipClasses,
            offset: 5,
            boundariesElement: document,
          }
        : void 0;
    },
    moveToStartTooltip() {
      return !this.reorderDisabled
        ? {
            content: this.__('novaSortable.moveToStart'),
            classes: this.tooltipClasses,
            offset: 5,
          }
        : void 0;
    },
    moveToEndTooltip() {
      return !this.reorderDisabled
        ? {
            content: this.__('novaSortable.moveToEnd'),
            classes: this.tooltipClasses,
            offset: 5,
          }
        : void 0;
    },
  },
};
</script>
