<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                <?php foreach ($headers as $header): ?>
                    <th class="px-6 py-4 font-semibold <?= isset($header['align']) ? 'text-' . $header['align'] : '' ?>">
                        <?= $header['label'] ?>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?= $slot ?>
        </tbody>
    </table>
</div>