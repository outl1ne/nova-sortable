import ResourceTable from './components/ResourceTable';
import ResourceTableRow from './components/ResourceTableRow';
import ReorderButtons from './components/ReorderButtons';

Nova.booting((app, router, store) => {
  app.component('resource-table', ResourceTable);
  app.component('resource-table-row', ResourceTableRow);
  app.component('reorder-buttons', ReorderButtons);
});
