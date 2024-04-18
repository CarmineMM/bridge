document.addEventListener('DOMContentLoaded', () => {
    const debugbar = document.querySelector('#debug-bar');

    if (!debugbar) {
        return;
    }

    // Colocar espaciado al body
    document.body.style.paddingBottom = '40px';
});

// Alpine.js
document.addEventListener('alpine:init', () => {
    Alpine.data('debugbar', (renderItems) => {

        const secondsToMs = (time) => {
            return (time * 1000).toFixed(3);
        }

        const byteToKb = (bytes) => {
            return (bytes / 1024).toFixed(2);
        }

        return {
            bodyOpen: false,

            selectedOption: 'config',

            tabContext: 'store',

            items: [],

            init() {
                const parseRenderItems = {}

                _.each(renderItems, (item, key) => {
                    if (key === 'config') {
                        delete item.elements.routes;
                        delete item.elements.framework;
                    }

                    if (key === 'queries') {
                        item.elements = item.elements.map((query) => {
                            return {
                                ...query,
                                time: secondsToMs(query.time) + ' ms',
                                memory: byteToKb(query.memory) + ' KB'
                            };
                        });
                    }

                    parseRenderItems[key] = item;
                });

                this.items = parseRenderItems;
            },

            toggleBody() {
                this.bodyOpen = !this.bodyOpen
            },

            selectOption(option) {
                this.selectedOption = option;
                this.bodyOpen = true;
            },

            /**
             * Hace render de una opción dentro de un <li>
             */
            renderOption(config, key, configKey = false) {
                if (key === 'rate_limit') {
                    console.log({ config });
                }

                if (['string', 'number'].includes(typeof config)) {
                    /*html*/
                    return `
                        <li class="item-config">
                            <p class="first-element" x-text="key"></p>
                            <p class="medium-content" x-html="typeof config === 'boolean' ? (config ? 'true': 'false') : config"></p>
                        </li>
                    `
                }

                /*html*/
                return `
                    <li class="item-config">
                        <p class="first-element" x-text="key"></p>
                        <ul class="full-content">
                            <template x-for="(subValue, subKey) in config">
                                <li class="item-config">
                                    <p class="first-element" x-show="['string', 'number'].includes(typeof subKey)" x-text="subKey"></p>

                                    <p class="full-content" x-show="_.isArray(subValue)" x-text="'Array('+ _.size(subValue) +')'"></p>

                                    <p class="medium-content" x-show="['string', 'number', 'boolean'].includes(typeof subValue)" x-text="_.isBoolean(subValue) ? (subValue ? 'true': 'false') : subValue"></p>
                                </li>
                            </template>
                        </ul>
                    </li>
                `;
            },
        }
    })
});