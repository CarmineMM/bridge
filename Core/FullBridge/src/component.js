import Alpine from './alpinejs/index';

document.addEventListener('alpine:init', () => {
    /**
     * Componentes de Full Bridge
     */
    Alpine.data('FullBridgeComponent', (receivedData) => {
        return {
            loading: false,
            ...receivedData?.data,

            init() {
                console.log(['receivedData', receivedData]);
            }
        };
    });
});