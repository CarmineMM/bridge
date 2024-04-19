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
                <th></th>
            </tr>
        </tbody>
    </table>
</div>