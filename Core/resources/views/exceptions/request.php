<div x-show="activeTab === 'request'" class="debug-tab">
    <table>
        <tbody>
            <tr>
                <th>Method</th>
                <td x-text="request.method"></td>
            </tr>
            <tr>
                <th>URI</th>
                <td x-text="'/'+request.uri"></td>
            </tr>
            <tr>
                <th>URL</th>
                <td x-text="request.url"></td>
            </tr>
            <tr>
                <th>IP</th>
                <td x-text="request.ip"></td>
            </tr>
            <tr>
                <th>User Agent</th>
                <td x-text="request.user_agent"></td>
            </tr>
            <tr>
                <th>Is ajax</th>
                <td x-text="request.isAjax ? 'true' : 'false'"></td>
            </tr>
            <tr>
                <th>Router</th>
                <td>
                    <span style="font-weight: bold;" class="small-monospace" x-text="request.route.method"></span>: <span style="font-weight: lighter;" x-text="`${request.route.callback}`"></span>
                </td>
            </tr>
        </tbody>
    </table>

    <h4 style="margin-bottom: 0.9em;">GET</h4>
    <table>
        <tbody>
            <template x-for="(value, index) in request.vars?.get">
                <tr>
                    <th x-text="index"></th>
                    <td x-text="value"></td>
                </tr>
            </template>
        </tbody>
    </table>

    <h4 style="margin-bottom: 0.9em;">POST</h4>
    <table>
        <tbody>
            <template x-for="(value, index) in request.vars?.post">
                <tr>
                    <th x-text="index"></th>
                    <td x-text="value"></td>
                </tr>
            </template>
        </tbody>
    </table>
</div>