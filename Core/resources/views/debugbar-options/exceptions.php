<div x-show="selectedOption === 'exceptions'" class="debugbar-body-item">
    <ul>
        <template x-for="(exception) in items.exceptions.elements">
            <li class="list-item">
                <p :class="`exception exception-${exception.severity}`" x-text="exception.severity"></p>
                <p x-show="exception.severity === 'error'">
                    <span title="Exception code" x-text="exception.code"></span>:<span title="Exception message" x-text="exception.message"></span>
                </p>
            </li>
        </template>
    </ul>
</div>