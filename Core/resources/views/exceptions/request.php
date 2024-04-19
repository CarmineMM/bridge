<div x-show="activeTab === 'request'" class="debug-tab">
    <table>
        <tbody>
            <tr>
                <th>Method</th>
                <td x-text="request.method"></td>
            </tr>
            <tr>
                <th>URL</th>
                <td x-text="request.url"></td>
            </tr>
        </tbody>
    </table>
</div>