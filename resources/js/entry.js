import ResourceTable from './components/ResourceTable';
import ReorderButtons from './components/ReorderButtons';

import { createVNode, render } from 'vue'
import { VueDraggableNext } from 'vue-draggable-next'

const handleDarkMode = () => {
  const cls = document.documentElement.classList;
  const isDarkMode = cls.contains('dark');

  if (isDarkMode && !cls.contains('o1-dark')) {
    cls.add('o1-dark');
  } else if (!isDarkMode && cls.contains('o1-dark')) {
    cls.remove('o1-dark');
  }
};

const config = Nova.config('nova-sortable')

Nova.booting((app, router, store) => {
  handleDarkMode();
  new MutationObserver(handleDarkMode).observe(document.documentElement, {
    attributes: true,
    attributeOldValue: true,
    attributeFilter: ['class'],
  });

  app.mixin({
        data() {
            return {
                container: null,
                toDestroy: [],
            }
        },
        unmounted() {

            for (const element of this.toDestroy) {
                render(null, element)
            }

        },

        mounted() {
            if (this._.type?.__file?.endsWith('ResourceTableRow.vue')) {
                const rowId = this.resource.id.value

                const handleContainer = document.createElement('div');
                handleContainer.classList.add('inline-flex','align-middle');

                const tbody = document.querySelector(`table[data-testid="resource-table"] > tbody`);

                const element = document.querySelector(`table[data-testid="resource-table"] tr[dusk="${ rowId }-row"]`);
                const checkbox = document.querySelector(`table[data-testid="resource-table"] tr[dusk="${ rowId }-row"] > td`);

                if (element) {
                    checkbox.appendChild(handleContainer);

			/*
                    const tbody = document.createElement('tbody')
                    element.parentNode.insertBefore(tbody, element);
                    tbody.appendChild(element);
                    tbody.insertAdjacentElement('afterend',dropTbody);
			*/

                    const ReorderButtonsVNode = createVNode(ReorderButtons, {
                       resource: this.resource,
                       viaResourceId: this.viaResourceId,
                       relationshipType: this.relationshipType,
                       viaRelationship: this.viaRelationship,
                       resourceName: this.resourceName,
                       displayMoveToButtons: config.displayMoveToButtons
                    });

                    ReorderButtonsVNode.appContext = app._context
                    render(ReorderButtonsVNode, handleContainer)

                    this.toDestroy.push(handleContainer)
                }
            }
        },
   });

  app.component('ResourceTable', ResourceTable);

});
