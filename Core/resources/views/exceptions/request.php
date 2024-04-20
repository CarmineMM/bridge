<div x-show="activeTab === 'request'" class="debug-tab" x-cloak>
    <table>
        <tbody>
            <tr>
                <th>Method</th>
                <td x-text="request.method"></td>
            </tr>
            <tr>
                <th>URI</th>
                <td x-text="request.uri"></td>
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
                    <span style="font-weight: bold;" class="text-monospace" x-text="request.route.method"></span>:
                    <span style="font-weight: lighter;" x-text="_.isArray(request.route.callback) ? request.route.callback[0] : request.route.callback"></span>
                    <span style="font-weight: lighter;" x-show="_.isArray(request.route.callback) && request.route.callback[1]" x-text="request.route.callback[1]"></span>
                </td>
            </tr>
        </tbody>
    </table>

    <div x-show="_.size(request.vars?.get) > 0">
        <h4>GET</h4>
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
    </div>

    <div x-show="_.size(request.vars?.post) > 0">
        <h4>POST</h4>
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
</div>