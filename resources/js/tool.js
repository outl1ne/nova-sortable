import ResourceTable from './components/ResourceTable';
import ResourceTableRow from './components/ResourceTableRow';
import ReorderButtons from './components/ReorderButtons';

Nova.booting((app, router, store) => {
  app.component('resource-table', ResourceTable);
  app.component('resource-table-row', ResourceTableRow);
  app.component('reorder-buttons', ReorderButtons);
});

const canSortResource = (resource, relationshipType = void 0) => {
  if (resource.sort_not_allowed) return true; // Can see, but it's disabled

  let canSee = !!resource.has_sortable_trait;
  if (!relationshipType) {
    canSee = resource.sort_on_index;
  } else {
    if (relationshipType === 'belongsToMany' || relationshipType === 'morphToMany') {
      canSee = resource.sort_on_belongs_to;
    } else {
      canSee = resource.sort_on_has_many;
    }
  }

  if (resource.sortable && resource.sortable.ignore_policies) {
    return canSee;
  }

  return canSee && resource.authorizedToUpdate;
};

export { canSortResource };
