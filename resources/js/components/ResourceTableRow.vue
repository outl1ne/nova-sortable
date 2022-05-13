<template>
  <tr
    :data-pivot-id="resource['id'].pivotValue"
    :dusk="resource['id'].value + '-row'"
    class="group"
    @click.stop.prevent="navigateToDetail"
  >
    <!-- Resource Selection Checkbox -->

    <td
      v-if="shouldShowCheckboxes || canSeeReorderButtons"
      :class="{
        'py-2': !shouldShowTight,
        'border-r': shouldShowColumnBorders,
        'border-t border-gray-100 dark:border-gray-700 px-2': true,
        'cursor-pointer': resource.authorizedToView,
      }"
      class="td-fit pl-5 pr-5 dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900"
      @click.stop
    >
      <ReorderButtons
        :resource="resource"
        :relationship-type="relationshipType"
        :via-resource-id="viaResourceId"
        :via-relationship="viaRelationship"
        :resource-name="resourceName"
        @moveToEnd="$emit('moveToEnd')"
        @moveToStart="$emit('moveToStart')"
      >
        <Checkbox
          v-if="shouldShowCheckboxes"
          :aria-label="__('Select Resource :title', { title: resource.title })"
          :checked="checked"
          :data-testid="`${testId}-checkbox`"
          :dusk="`${resource['id'].value}-checkbox`"
          @input="toggleSelection"
        />
      </ReorderButtons>
    </td>

    <!-- Fields -->
    <td
      v-for="(field, index) in resource.fields"
      :key="field.uniqueKey"
      :class="{
        'px-6': index == 0 && !shouldShowCheckboxes,
        'px-2': index != 0 || shouldShowCheckboxes,
        'py-2': !shouldShowTight,
        'border-r': shouldShowColumnBorders,
        'border-t border-gray-100 dark:border-gray-700': true,
        'whitespace-nowrap': !field.wrapping,
        'cursor-pointer': resource.authorizedToView,
      }"
      class="dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900"
    >
      <component
        :is="'index-' + field.component"
        :class="`text-${field.textAlign}`"
        :field="field"
        :resource="resource"
        :resource-name="resourceName"
        :via-resource="viaResource"
        :via-resource-id="viaResourceId"
      />
    </td>

    <td
      :class="{
        'py-2': !shouldShowTight,
        'border-t border-gray-100 dark:border-gray-700': true,
        'cursor-pointer': resource.authorizedToView,
      }"
      class="px-2 td-fit text-right align-middle dark:bg-gray-800 group-hover:bg-gray-50 dark:group-hover:bg-gray-900"
    >
      <div class="flex items-center space-x-0 text-gray-400">
        <InlineActionDropdown
          :actions="availableActions"
          :endpoint="actionsEndpoint"
          :resource="resource"
          :resource-name="resourceName"
          :via-many-to-many="viaManyToMany"
          :via-resource="viaResource"
          :via-resource-id="viaResourceId"
          :via-relationship="viaRelationship"
          @actionExecuted="$emit('actionExecuted')"
        />

        <!-- View Resource Link -->
        <template v-if="resource.authorizedToView">
          <Link
            v-tooltip.click="__('View')"
            :aria-label="__('View')"
            :data-testid="`${testId}-view-button`"
            :dusk="`${resource['id'].value}-view-button`"
            :href="$url(`/resources/${resourceName}/${resource['id'].value}`)"
            class="toolbar-button hover:text-primary-500 px-2"
            @click.stop
          >
            <Icon type="eye" />
          </Link>
        </template>

        <template v-if="resource.authorizedToUpdate">
          <!-- Edit Pivot Button -->
          <Link
            v-if="relationshipType == 'belongsToMany' || relationshipType == 'morphToMany'"
            v-tooltip.click="__('Edit Attached')"
            :aria-label="__('Edit Attached')"
            :dusk="`${resource['id'].value}-edit-attached-button`"
            :href="
              $url(`/resources/${viaResource}/${viaResourceId}/edit-attached/${resourceName}/${resource['id'].value}`, {
                viaRelationship: viaRelationship,
                viaPivotId: resource['id'].pivotValue,
              })
            "
            class="toolbar-button hover:text-primary-500"
            @click.stop
          >
            <Icon type="pencil-alt" />
          </Link>

          <!-- Edit Resource Link -->
          <Link
            v-else
            v-tooltip.click="__('Edit')"
            :aria-label="__('Edit')"
            :dusk="`${resource['id'].value}-edit-button`"
            :href="
              $url(`/resources/${resourceName}/${resource['id'].value}/edit`, {
                viaResource: viaResource,
                viaResourceId: viaResourceId,
                viaRelationship: viaRelationship,
              })
            "
            class="toolbar-button hover:text-primary-500 px-2"
            @click.stop
          >
            <Icon type="pencil-alt" />
          </Link>
        </template>

        <!-- Delete Resource Link -->
        <button
          v-if="resource.authorizedToDelete && (!resource.softDeleted || viaManyToMany)"
          v-tooltip.click="__(viaManyToMany ? 'Detach' : 'Delete')"
          :aria-label="__(viaManyToMany ? 'Detach' : 'Delete')"
          :data-testid="`${testId}-delete-button`"
          :dusk="`${resource['id'].value}-delete-button`"
          class="toolbar-button hover:text-primary-500 px-2"
          @click.stop="openDeleteModal"
        >
          <Icon type="trash" />
        </button>

        <!-- Restore Resource Link -->
        <button
          v-if="resource.authorizedToRestore && resource.softDeleted && !viaManyToMany"
          v-tooltip.click="__('Restore')"
          :aria-label="__('Restore')"
          :dusk="`${resource['id'].value}-restore-button`"
          class="toolbar-button hover:text-primary-500 px-2"
          @click.stop="openRestoreModal"
        >
          <Icon type="refresh" />
        </button>

        <DeleteResourceModal
          :mode="viaManyToMany ? 'detach' : 'delete'"
          :show="deleteModalOpen"
          @close="closeDeleteModal"
          @confirm="confirmDelete"
        />

        <RestoreResourceModal :show="restoreModalOpen" @close="closeRestoreModal" @confirm="confirmRestore">
          <ModalHeader v-text="__('Restore Resource')" />
          <ModalContent>
            <p class="leading-normal">
              {{ __('Are you sure you want to restore this resource?') }}
            </p>
          </ModalContent>
        </RestoreResourceModal>
      </div>
    </td>
  </tr>
</template>

<script>
import filter from 'lodash/filter';
import { Inertia } from '@inertiajs/inertia';
import ReordersResources from '../mixins/ReordersResources';

export default {
  emits: ['actionExecuted'],
  mixins: [ReordersResources],

  props: [
    'testId',
    'deleteResource',
    'restoreResource',
    'resource',
    'itemKey',
    'resourcesSelected',
    'resourceName',
    'relationshipType',
    'viaRelationship',
    'viaResource',
    'viaResourceId',
    'viaManyToMany',
    'checked',
    'actionsAreAvailable',
    'actionsEndpoint',
    'shouldShowCheckboxes',
    'shouldShowColumnBorders',
    'tableStyle',
    'updateSelectionStatus',
    'queryString',
  ],

  data: () => ({
    commandPressed: false,
    deleteModalOpen: false,
    restoreModalOpen: false,
  }),

  mounted() {
    window.addEventListener('keydown', this.handleKeydown);
    window.addEventListener('keyup', this.handleKeyup);
  },

  beforeUnmount() {
    window.removeEventListener('keydown', this.handleKeydown);
    window.removeEventListener('keyup', this.handleKeyup);
  },

  methods: {
    /**
     * Select the resource in the parent component
     */
    toggleSelection() {
      this.updateSelectionStatus(this.resource);
    },

    handleKeydown(e) {
      if (e.key === 'Meta') {
        this.commandPressed = true;
      }
    },

    handleKeyup(e) {
      if (e.key === 'Meta') {
        this.commandPressed = false;
      }
    },

    navigateToDetail(e) {
      if (!this.resource.authorizedToView) {
        return;
      }

      this.commandPressed ? window.open(this.viewURL, '_blank') : Inertia.visit(this.viewURL);
    },

    openDeleteModal() {
      this.deleteModalOpen = true;
    },

    confirmDelete() {
      this.deleteResource(this.resource);
      this.closeDeleteModal();
    },

    closeDeleteModal() {
      this.deleteModalOpen = false;
    },

    openRestoreModal() {
      this.restoreModalOpen = true;
    },

    confirmRestore() {
      this.restoreResource(this.resource);
      this.closeRestoreModal();
    },

    closeRestoreModal() {
      this.restoreModalOpen = false;
    },
  },

  computed: {
    viewURL() {
      return this.$url(`/resources/${this.resourceName}/${this.resource.id.value}`);
    },

    availableActions() {
      return filter(this.resource.actions, a => a.showOnTableRow);
    },

    shouldShowTight() {
      return this.tableStyle == 'tight';
    },
  },
};
</script>
