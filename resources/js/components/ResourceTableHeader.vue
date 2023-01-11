<template>
  <thead class="o1-bg-gray-50 dark:o1-bg-gray-800">
    <tr>
      <th
        class="td-fit o1-uppercase text-xxs o1-text-gray-500 o1-tracking-wide o1-pl-5 o1-pr-2 o1-py-2"
        :class="{
          'o1-border-r o1-border-gray-200 dark:o1-border-gray-600': shouldShowColumnBorders,
        }"
        v-if="shouldShowCheckboxes || canSeeReorderButtons"
      >
        <span v-if="shouldShowCheckboxes" class="o1-sr-only">{{ __('Selected Resources') }}</span>
      </th>

      <th
        v-for="(field, index) in fields"
        :key="field.uniqueKey"
        :class="{
          [`text-${field.textAlign}`]: true,
          'o1-border-r o1-border-gray-200 dark:o1-border-gray-600': shouldShowColumnBorders,
          'o1-px-6': index == 0 && !shouldShowCheckboxes && !canSeeReorderButtons,
          'o1-px-2': index != 0 || shouldShowCheckboxes || canSeeReorderButtons,
          'o1-whitespace-nowrap': !field.wrapping,
        }"
        class="o1-uppercase o1-text-gray-500 text-xxs o1-tracking-wide o1-py-2"
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
      <th class="o1-uppercase text-xxs o1-tracking-wide o1-px-2 o1-py-2">
        <span class="o1-sr-only">{{ __('Controls') }}</span>
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
