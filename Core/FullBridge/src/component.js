document.addEventListener('alpine:init', () => {
    /**
     * Componentes de Full Bridge
     */
    Alpine.data('FullBridgeComponent', (receivedData) => {
        return {
            loading: false,
            data: receivedData?.data ?? {},

            init() {

            }
        };
    });
});