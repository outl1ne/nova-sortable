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
      try {
        await Nova.request().post('/nova-vendor/nova-sortable/sort/update-order', {
          resourceName: this.resourceName,
          resourceIds: newResourceOrder,
        });
        Nova.success(this.__('novaSortable.reorderSuccessful'));
      } catch (e) {
        Nova.error(this.__('novaSortable.reorderError'));
      }

      this.reorderLoading = false;
    },

    async moveToStart(resource) {
      this.reorderLoading = true;
      try {
        await Nova.request().post('/nova-vendor/nova-sortable/sort/move-to-start', {
          resourceName: this.resourceName,
          resourceId: resource.id.value,
        });
        await this.refreshResourcesList();
        Nova.success(this.__('novaSortable.moveToStartSuccessful'));
      } catch (e) {
        Nova.error(this.__('novaSortable.reorderError'));
      }
      this.reorderLoading = false;
    },

    async moveToEnd(resource) {
      this.reorderLoading = true;
      try {
        await Nova.request().post('/nova-vendor/nova-sortable/sort/move-to-end', {
          resourceName: this.resourceName,
          resourceId: resource.id.value,
        });
        await this.refreshResourcesList();
        Nova.success(this.__('novaSortable.moveToEndSuccessful'));
      } catch (e) {
        Nova.error(this.__('novaSortable.reorderError'));
      }
      this.reorderLoading = false;
    },

    async refreshResourcesList() {
      // ! Might break with new Laravel Nova versions
      let parent = this.$parent;
      while (parent && !parent.getResources) {
        parent = parent.$parent;
      }
      if (parent && parent.getResources) await parent.getResources();
    },
  },
};
