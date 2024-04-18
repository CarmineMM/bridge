<div x-show="selectedOption === 'queries'" class="debugbar-body-item">
    <ul>
        <template x-for="item in items.queries.elements">
            <li class="list-item item-query">
                <p style="width: 75%;" x-text="item.query"></p>
                <p style="width: 7%;" x-text="item.memory"></p>
                <p style="width: 7%;" x-text="item.time"></p>
                <p style="width: 10%;">
                    <span title="Connection" x-text="item.connection"></span>:<span title="Driver" x-text="item.driver"></span>
                </p>
            </li>
        </template>
    </ul>
</div>