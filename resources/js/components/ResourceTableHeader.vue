<template>
  <thead class="nova-sortable-bg-gray-50 dark:nova-sortable-bg-gray-800">
    <tr>
      <th
        class="td-fit nova-sortable-uppercase text-xxs nova-sortable-text-gray-500 nova-sortable-tracking-wide nova-sortable-pl-5 nova-sortable-pr-2 nova-sortable-py-2"
        :class="{
          'nova-sortable-border-r nova-sortable-border-gray-200 dark:nova-sortable-border-gray-600':
            shouldShowColumnBorders,
        }"
        v-if="shouldShowCheckboxes || canSeeReorderButtons"
      >
        <span v-if="shouldShowCheckboxes" class="nova-sortable-sr-only">{{ __('Selected Resources') }}</span>
      </th>

      <th
        v-for="(field, index) in fields"
        :key="field.uniqueKey"
        :class="{
          [`text-${field.textAlign}`]: true,
          'nova-sortable-border-r nova-sortable-border-gray-200 dark:nova-sortable-border-gray-600':
            shouldShowColumnBorders,
          'nova-sortable-px-6': index == 0 && !shouldShowCheckboxes && !canSeeReorderButtons,
          'nova-sortable-px-2': index != 0 || shouldShowCheckboxes || canSeeReorderButtons,
          'nova-sortable-whitespace-nowrap': !field.wrapping,
        }"
        class="nova-sortable-uppercase nova-sortable-text-gray-500 text-xxs nova-sortable-tracking-wide nova-sortable-py-2"
      >
        <SortableIcon
          @sort="requestOrderByChange(field)"
          @reset="resetOrderBy(field)"
          :resource-name="resourceName"
          :uri-key="field.sortableUriKey"
          v-if="sortable && field.sortable"
        >
          {{ field.indexName }}
        </SortableIcon>

        <span v-else>{{ field.indexName }}</span>
      </th>

      <!-- View, Edit, and Delete -->
      <th class="nova-sortable-uppercase text-xxs nova-sortable-tracking-wide nova-sortable-px-2 nova-sortable-py-2">
        <span class="nova-sortable-sr-only">{{ __('Controls') }}</span>
      </th>
    </tr>
  </thead>
</template>

<script>
import ReordersResources from '../mixins/ReordersResources';

export default {
  name: 'ResourceTableHeader',

  mixins: [ReordersResources],

  emits: ['order', 'reset-order-by'],

  props: {
    resource: Object | null,
    resourceName: String,
    shouldShowColumnBorders: Boolean,
    shouldShowCheckboxes: Boolean,
    fields: {
      type: [Object, Array],
    },
    sortable: Boolean,
  },
  methods: {
    /**
     * Broadcast that the ordering should be updated.
     */
    requestOrderByChange(field) {
      this.$emit('order', field);
    },

    /**
     * Broadcast that the ordering should be reset.
     */
    resetOrderBy(field) {
      this.$emit('reset-order-by', field);
    },
  },
};
</script>
