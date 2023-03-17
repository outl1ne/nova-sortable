import ResourceTable from './components/ResourceTable';
import ResourceTableHeader from './components/ResourceTableHeader';
import ResourceTableRow from './components/ResourceTableRow';
import ReorderButtons from './components/ReorderButtons';

const handleDarkMode = () => {
  const cls = document.documentElement.classList;
  const isDarkMode = cls.contains('dark');

  if (isDarkMode && !cls.contains('o1-dark')) {
    cls.add('o1-dark');
  } else if (!isDarkMode && cls.contains('o1-dark')) {
    cls.remove('o1-dark');
  }
};

Nova.booting((app, router, store) => {
  handleDarkMode();
  new MutationObserver(handleDarkMode).observe(document.documentElement, {
    attributes: true,
    attributeOldValue: true,
    attributeFilter: ['class'],
  });

  app.component('ResourceTable', ResourceTable);
  app.component('ResourceTableHeader', ResourceTableHeader);
  app.component('ResourceTableRow', ResourceTableRow);
  app.component('ReorderButtons', ReorderButtons);
});
