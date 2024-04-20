<div x-show="activeTab === 'response'" class="debug-tab" x-cloak>
    <table>
        <tbody>
            <tr>
                <th>Status Code</th>
                <td x-text="response.statusCode"></td>
            </tr>
        </tbody>
    </table>

    <h4>Headers</h4>
    <table>
        <tbody>
            <template x-for="(value, key) in response.headers">
                <tr>
                    <th x-text="key"></th>
                    <td x-text="value"></td>
                </tr>
            </template>
        </tbody>
    </table>
</div>