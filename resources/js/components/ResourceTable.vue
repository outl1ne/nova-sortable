<template>
  <table
    v-if="resources.length > 0"
    class="table w-full"
    :class="[`table-${resourceInformation.tableStyle}`, resourceInformation.showColumnBorders ? 'table-grid' : '']"
    cellpadding="0"
    cellspacing="0"
    data-testid="resource-table"
  >
    <thead>
      <tr>
        <!-- Select Checkbox -->
        <th class="w-16" v-if="shouldShowCheckboxes || canSeeReorderButtons">&nbsp;</th>

        <!-- Field Names -->
        <th v-for="field in fields" :class="`text-${field.textAlign}`">
          <sortable-icon
            @sort="requestOrderByChange(field)"
            @reset="resetOrderBy(field)"
            :resource-name="resourceName"
            :uri-key="field.sortableUriKey"
            v-if="field.sortable"
          >
            {{ field.indexName }}
          </sortable-icon>

          <span v-else>{{ field.indexName }}</span>
        </th>

        <!-- Actions, View, Edit, Delete -->
        <th>&nbsp;</th>
      </tr>
    </thead>
    <draggable v-model="resources" tag="tbody" handle=".handle" @update="updateOrder">
      <tr
        v-for="(resource, index) in resources"
        @actionExecuted="$emit('actionExecuted')"
        :testId="`${resourceName}-items-${index}`"
        :key="resource.id.value"
        :delete-resource="deleteResource"
        :restore-resource="restoreResource"
        is="resource-table-row"
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
        :update-selection-status="updateSelectionStatus"
        @moveToStart="moveToStart(resource)"
        @moveToEnd="moveToEnd(resource)"
      />
    </draggable>
  </table>
</template>

<script>
import { InteractsWithResourceInformation } from 'laravel-nova';
import Draggable from 'vuedraggable';
import ReordersResources from '../mixins/ReordersResources';

export default {
  mixins: [InteractsWithResourceInformation, ReordersResources],
  components: { Draggable },
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
      Nova.$emit('metric-refresh');
    },

    /**
     * Restore the given resource.
     */
    restoreResource(resource) {
      this.$emit('restore', [resource]);
      Nova.$emit('metric-refresh');
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
  },
};
</script>

<style>
.flip-list-move {
  transition: transform 0.25s;
}
</style>
