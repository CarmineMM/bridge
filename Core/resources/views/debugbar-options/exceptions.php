<div x-show="selectedOption === 'exceptions'" class="debugbar-body-item">
    <ul>
        <template x-for="(exception) in items.exceptions.elements">
            <li class="list-item">
                <p :class="`exception exception-${exception.severity}`" x-text="exception.severity.toUpperCase()"></p>
                <p x-show="exception.severity === 'error'">
                    <span title="Exception code" x-text="exception.code"></span>:<span title="Exception message" x-text="exception.message"></span>
                </p>
                <div style="margin-left: 1.5em;">
                    <p x-show="exception.severity === 'warning'" x-text="exception.message"></p>
                    <p class="small-monospace">
                        <span x-text="exception.file"></span>:<span x-text="exception.line"></span>
                    </p>
                </div>
            </li>
        </template>
    </ul>
</div>