<template>
  <div class="overflow-hidden overflow-x-auto relative">
    <table
      v-if="resources.length > 0"
      class="w-full"
      :class="[`table-${tableStyle}`]"
      cellpadding="0"
      cellspacing="0"
      data-testid="resource-table"
    >
      <ResourceTableHeader
        :resource-name="resourceName"
        :fields="fields"
        :should-show-column-borders="shouldShowColumnBorders"
        :should-show-checkboxes="shouldShowCheckboxes"
        :sortable="sortable"
        @order="requestOrderByChange"
        @reset-order-by="resetOrderBy"
      />
      <draggable
        tag="tbody"
        item-key="id"
        v-model="fakeResources"
        handle=".handle"
        draggable="tr"
        @update="updateOrder"
      >
        <ResourceTableRow
          v-for="(resource, index) in fakeResources"
          :key="`${resource.id.value}-items-${index}`"
          @actionExecuted="$emit('actionExecuted')"
          :testId="`${resourceName}-items-${index}`"
          :delete-resource="deleteResource"
          :restore-resource="restoreResource"
          :resource="resource"
          :resource-name="resourceName"
          :relationship-type="relationshipType"
          :via-relationship="viaRelationship"
          :via-resource="viaResource"
          :via-resource-id="viaResourceId"
          :via-many-to-many="viaManyToMany"
          :checked="selectedResources.indexOf(resource) > -1"
          :actions-are-available="actionsAreAvailable"
          :actions-endpoint="actionsEndpoint"
          :should-show-checkboxes="shouldShowCheckboxes"
          :should-show-column-borders="shouldShowColumnBorders"
          :table-style="tableStyle"
          :update-selection-status="updateSelectionStatus"
          @moveToStart="moveToStart(resource)"
          @moveToEnd="moveToEnd(resource)"
        />
      </draggable>
    </table>
  </div>
</template>

<script>
import { InteractsWithResourceInformation } from 'laravel-nova-mixins';
import { VueDraggableNext } from 'vue-draggable-next';
import ReordersResources from '../mixins/ReordersResources';

export default {
  emits: ['actionExecuted', 'updateOrder', 'delete', 'restore', 'order', 'reset-order-by'],

  mixins: [InteractsWithResourceInformation, ReordersResources],

  components: {
    draggable: VueDraggableNext,
  },

  props: {
    authorizedToRelate: {
      type: Boolean,
      required: true,
    },
    resourceName: {
      default: null,
    },
    resources: {
      default: [],
    },
    singularName: {
      type: String,
      required: true,
    },
    selectedResources: {
      default: [],
    },
    selectedResourceIds: {},
    shouldShowCheckboxes: {
      type: Boolean,
      default: false,
    },
    actionsAreAvailable: {
      type: Boolean,
      default: false,
    },
    viaResource: {
      default: null,
    },
    viaResourceId: {
      default: null,
    },
    viaRelationship: {
      default: null,
    },
    relationshipType: {
      default: null,
    },
    updateSelectionStatus: {
      type: Function,
    },
    actionsEndpoint: {
      default: null,
    },
    sortable: {
      type: Boolean,
      default: false,
    },
  },

  data: () => ({
    selectAllResources: false,
    selectAllMatching: false,
    resourceCount: null,
  }),

  methods: {
    /**
     * Delete the given resource.
     */
    deleteResource(resource) {
      this.$emit('delete', [resource]);
    },

    /**
     * Restore the given resource.
     */
    restoreResource(resource) {
      this.$emit('restore', [resource]);
    },

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

  computed: {
    /**
     * Get all of the available fields for the resources.
     */
    fields() {
      if (this.resources) {
        return this.resources[0].fields;
      }
    },

    /**
     * Determine if the current resource listing is via a many-to-many relationship.
     */
    viaManyToMany() {
      return this.relationshipType == 'belongsToMany' || this.relationshipType == 'morphToMany';
    },

    /**
     * Determine if the current resource listing is via a has-one relationship.
     */
    viaHasOne() {
      return this.relationshipType == 'hasOne' || this.relationshipType == 'morphOne';
    },

    /**
     * Determine if the resource table should show column borders.
     */
    shouldShowColumnBorders() {
      return this.resourceInformation.showColumnBorders;
    },

    tableStyle() {
      return this.resourceInformation.tableStyle;
    },
  },
};
</script>

<style>
.flip-list-move {
  transition: transform 0.25s;
}
</style>
