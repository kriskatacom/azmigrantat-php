<?php
$name = $name ?? 'parent_id';
$label = $label ?? 'Категория';
$selectedId = $selectedId ?? null;
$excludeId = $excludeId ?? null;
$options = $options ?? [];
$placeholder = $placeholder ?? 'Изберете категория';
?>

<div class="space-y-2">
    <?php if ($label): ?>
        <label class="text-sm font-semibold text-gray-600"><?= $label ?></label>
    <?php endif; ?>
    
    <select name="<?= $name ?>" class="form-control bg-white cursor-pointer focus:border-indigo-300">
        <option value=""><?= $placeholder ?></option>
        
        <?php foreach ($options as $parent): ?>
            <?php
                if ($excludeId && $parent['id'] == $excludeId) continue; 
            ?>

            <option value="<?= $parent['id'] ?>" <?= ($selectedId == $parent['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($parent['name']) ?>
            </option>

            <?php if (!empty($parent['children'])): ?>
                <?php foreach ($parent['children'] as $child): ?>
                    <?php 
                        if ($excludeId && $child['id'] == $excludeId) continue; 
                    ?>
                    <option value="<?= $child['id'] ?>" <?= ($selectedId == $child['id']) ? 'selected' : '' ?>>
                        &nbsp;&nbsp;— <?= htmlspecialchars($child['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
</div>
