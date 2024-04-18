<div x-show="selectedOption === 'config'" class="regular-content">
    <template x-for="(el, index) in items.config.elements">
        <div style="margin-bottom: 1em;">
            <h4 x-text="index" class="capitalize"></h4>
            <ul>
                <template x-for="(config, key) in el">
                    <li x-html="renderOption(config, key)"></li>
                </template>
            </ul>
        </div>
    </template>
</div>