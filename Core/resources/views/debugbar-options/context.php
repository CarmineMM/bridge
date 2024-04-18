<div x-show="selectedOption === 'context'" class="regular-content">
    <div style="display: flex; gap: 1em; margin-bottom: 0.8em;">
        <template x-for="(item, tab) in items.context.tabs">
            <button @click="tabContext = tab" :class="{ active: tabContext === tab }" type="button" class="regular-button" x-text="item.title"></button>
        </template>
    </div>

    <template x-for="(item, tab) in items.context.tabs">
        <ul x-show="tabContext === tab">
            <template x-for="(values, key) in item.elements">
                <li x-html="renderOption(values, key)"></li>
            </template>
        </ul>
    </template>
</div>