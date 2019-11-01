import ResourceTable from './components/ResourceTable';
import ResourceTableRow from './components/ResourceTableRow';
import ReorderButtons from './components/ReorderButtons';

Nova.booting((Vue, router, store) => {
  Vue.component('resource-table', ResourceTable);
  Vue.component('resource-table-row', ResourceTableRow);
  Vue.component('reorder-buttons', ReorderButtons);
});
