Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'nova-sortable',
      path: '/nova-sortable',
      component: require('./components/Tool'),
    },
  ]);
});
