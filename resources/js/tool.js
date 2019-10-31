import ResourceTable from './components/ResourceTable';

Nova.booting((Vue, router, store) => {
  Vue.component('resource-table', ResourceTable);
});
