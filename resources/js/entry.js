import ResourceTable from './components/ResourceTable';
import ResourceTableHeader from './components/ResourceTableRow';
import ResourceTableRow from './components/ResourceTableRow';
import ReorderButtons from './components/ReorderButtons';

Nova.booting((app, router, store) => {
  app.component('ResourceTable', require('./components/ResourceTable').default);
  app.component('ResourceTableHeader', require('./components/ResourceTableHeader').default);
  app.component('ResourceTableRow', require('./components/ResourceTableRow').default);
  app.component('ReorderButtons', require('./components/ReorderButtons').default);

  new MutationObserver(() => {
    const cls = document.documentElement.classList;
    const isDarkMode = cls.contains('dark');

    if (isDarkMode && !cls.contains('o1-dark')) {
      cls.add('o1-dark');
    } else if (!isDarkMode && cls.contains('o1-dark')) {
      cls.remove('o1-dark');
    }
  }).observe(document.documentElement, {
    attributes: true,
    attributeOldValue: true,
    attributeFilter: ['class'],
  });
});
