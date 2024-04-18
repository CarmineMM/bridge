<div x-show="selectedOption === 'exceptions'" class="debugbar-body-item">
    <ul>
        <template x-for="(exception) in items.exceptions.elements">
            <li class="list-item item-query" x-text="exception.code +':'+ exception.message"></li>
        </template>
    </ul>
</div>