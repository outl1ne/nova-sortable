import ResourceTable from './components/ResourceTable';
import ResourceTableRow from './components/ResourceTableRow';

Nova.booting((Vue, router, store) => {
  Vue.component('resource-table', ResourceTable);
  Vue.component('resource-table-row', ResourceTableRow);
});
