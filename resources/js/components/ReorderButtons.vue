<template>
  <div class="flex items-center">
    <slot></slot>
    <div class="flex items-center ml-4" v-tooltip="reorderDisabledTooltip" v-if="canSeeReorderButtons">
      <div class="flex flex-col">
        <ChevronUpIcon
          @click="!reorderDisabled && $emit('moveToStart')"
          :custom-class="{
            'cursor-pointer text-gray-400 hover:text-primary-400 active:text-primary-500': !reorderDisabled,
            'cursor-default text-gray-200': reorderDisabled,
          }"
          v-tooltip="moveToStartTooltip"
        />

        <ChevronDownIcon
          @click="!reorderDisabled && $emit('moveToEnd')"
          :custom-class="{
            'cursor-pointer text-gray-400 hover:text-primary-400  active:text-primary-500': !reorderDisabled,
            'cursor-default text-gray-200': reorderDisabled,
          }"
          v-tooltip="moveToEndTooltip"
        />
      </div>

      <BurgerIcon
        style="min-width: 22px; width: 22px"
        :custom-class="{
          'handle cursor-move text-gray-400 hover:text-primary-400 active:text-primary-500': !reorderDisabled,
          'text-gray-200 cursor-default': reorderDisabled,
        }"
      />
    </div>
  </div>
</template>

<script>
import ChevronUpIcon from '../icons/ChevronUpIcon';
import ChevronDownIcon from '../icons/ChevronDownIcon';
import BurgerIcon from '../icons/BurgerIcon';
import { canSortResource } from '../mixins/canSortResource';

export default {
  components: { ChevronUpIcon, ChevronDownIcon, BurgerIcon },

  props: ['resource', 'viaResourceId', 'relationshipType', 'viaRelationship', 'resourceName'],

  computed: {
    canSeeReorderButtons() {
      return canSortResource(this.resource, this.relationshipType);
    },

    // Returns reason string why reordering is disabled
    reorderDisabled() {
      if (this.resource.sort_not_allowed) {
        return 'notAllowed';
      }

      if (this.hasDirection || this.isSorted) {
        return 'activeSort';
      }

      return false;
    },

    /**
     * The current route parameters
     */
    routeParameters() {
      const searchParams = new URLSearchParams(window.location.search);
      return Object.fromEntries(searchParams.entries());
    },

    /**
     * The name of the sortable resource
     */
    resourceKey() {
      return this.viaRelationship ? this.viaRelationship : this.resourceName;
    },

    /**
     * The order query parameter for the sortable resource
     */
    sortKey() {
      return `${this.resourceKey}_order`;
    },

    /**
     * The current order query parameter value
     */
    sortColumn() {
      return this.routeParameters[this.sortKey];
    },

    /**
     * The direction query parameter for the sortable resource
     */
    directionKey() {
      return `${this.resourceKey}_direction`;
    },

    /**
     * The current direction query parameter value
     */
    direction() {
      return this.routeParameters[this.directionKey];
    },

    /**
     * Check if there is a current direction
     */
    hasDirection() {
      return ['asc', 'desc'].includes(this.direction);
    },

    /**
     * Check if there is a current direction
     */
    isSorted() {
      return !!this.routeParameters[this.sortKey];
    },

    /**
     * The content of the reorderDisabledTooltip
     */
    reorderDisabledTooltip() {
      return this.reorderDisabled ? this.__(`novaSortable.reorderingDisabledTooltip.${this.reorderDisabled}`) : void 0;
    },

    /**
     * The content of the moveToStartTooltip
     */
    moveToStartTooltip() {
      return !this.reorderDisabled ? this.__('novaSortable.moveToStart') : void 0;
    },

    /**
     * The content of the moveToEndTooltip
     */
    moveToEndTooltip() {
      return !this.reorderDisabled ? this.__('novaSortable.moveToEnd') : void 0;
    },
  },
};
</script>
