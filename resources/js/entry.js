import ResourceTable from './components/ResourceTable';
import ResourceTableHeader from './components/ResourceTableRow';
import ResourceTableRow from './components/ResourceTableRow';
import ReorderButtons from './components/ReorderButtons';

Nova.booting((app, router, store) => {
  app.component('ResourceTable', require('./components/ResourceTable').default);
  app.component('ResourceTableHeader', require('./components/ResourceTableHeader').default);
  app.component('ResourceTableRow', require('./components/ResourceTableRow').default);
  app.component('ReorderButtons', require('./components/ReorderButtons').default);
});
