<template>
  <thead class="bg-gray-50 dark:bg-gray-800">
    <tr>
      <th
        class="td-fit uppercase text-xxs text-gray-500 tracking-wide pl-5 pr-2 py-2"
        :class="{
          'border-r border-gray-200 dark:border-gray-600': shouldShowColumnBorders,
        }"
        v-if="shouldShowCheckboxes || canSeeReorderButtons"
      >
        <span v-if="shouldShowCheckboxes" class="sr-only">{{ __('Selected Resources') }}</span>
      </th>

      <th
        v-for="(field, index) in fields"
        :key="field.uniqueKey"
        :class="{
          [`text-${field.textAlign}`]: true,
          'border-r border-gray-200 dark:border-gray-600': shouldShowColumnBorders,
          'px-6': index == 0 && !shouldShowCheckboxes && !canSeeReorderButtons,
          'px-2': index != 0 || shouldShowCheckboxes || canSeeReorderButtons,
          'whitespace-nowrap': !field.wrapping,
        }"
        class="uppercase text-gray-500 text-xxs tracking-wide py-2"
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
      <th class="uppercase text-xxs tracking-wide px-2 py-2">
        <span class="sr-only">{{ __('Controls') }}</span>
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
