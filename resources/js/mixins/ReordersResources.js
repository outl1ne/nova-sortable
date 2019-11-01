export default {
  data: () => ({
    reorderLoading: false,
  }),
  computed: {
    reorderDisabled() {
      return !!this.$route.query[this.orderByParameter];
    },
    orderByParameter() {
      return this.viaRelationship ? this.viaRelationship + '_order' : this.resourceName + '_order';
    },
  },
  methods: {
    async updateOrder(event) {
      this.reorderLoading = true;

      const newResourceOrder = this.resources.map(r => r.id.value);
      console.info('New resources order', newResourceOrder);

      try {
        await Nova.request().post('/nova-vendor/nova-sortable/sort/update-order', {
          resourceName: this.resourceName,
          resourceIds: newResourceOrder,
        });
        Nova.success('Ordering has been updated!');
      } catch (e) {
        Nova.error('An error occurred while trying to reorder the resources.');
      }

      this.reorderLoading = false;
    },

    async moveToStart(resource) {
      this.reorderLoading = true;
      try {
        console.info('Move resource to START:', resource.id.value);
        Nova.success('Resource moved to first position.');
      } catch (e) {
        Nova.error('An error occurred while trying to reorder the resource.');
        this.reorderLoading = false;
      }
      this.reorderLoading = false;
    },

    async moveToEnd(resource) {
      this.reorderLoading = true;
      try {
        console.info('Move resource to END:', resource.id.value);
        Nova.success('Resource moved to last position.');
      } catch (e) {
        Nova.error('An error occurred while trying to reorder the resource.');
        this.reorderLoading = false;
      }
      this.reorderLoading = false;
    },
  },
};
