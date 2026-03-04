<?php

use App\Core\View;
use App\Services\HelperService;

$allRoles = [
    1 => ['name' => 'admin', 'label' => 'Администратор'],
    2 => ['name' => 'driver', 'label' => 'Шофьор'],
    3 => ['name' => 'user', 'label' => 'Потребител'],
    4 => ['name' => 'moderator', 'label' => 'Модератор']
];
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [['label' => 'Потребители', 'url' => '/admin/users']]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
        <h3 class="font-bold text-gray-800 text-lg">Списък с потребители</h3>
        <a href="/admin/users/create" class="btn-primary">+ Нов потребител</a>
    </div>

    <?php
    $headers = [
        ['label' => 'Име'],
        ['label' => 'Имейл'],
        ['label' => 'Роля'],
        ['label' => 'Статус', 'align' => 'center'],
        ['label' => 'Действия', 'align' => 'right']
    ];

    ob_start();
    foreach ($users as $user): ?>
        <tr class="hover:bg-gray-200/50 transition">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-500 uppercase">
                        <?= mb_substr($user['name'], 0, 1) ?>
                    </div>
                    <span class="font-medium text-gray-700"><?= htmlspecialchars($user['name']) ?></span>
                </div>
            </td>
            <td class="px-6 py-4 text-gray-600 text-sm italic"><?= htmlspecialchars($user['email']) ?></td>
            <td class="px-6 py-4">
                <?php
                $roleColors = [
                    'admin' => 'bg-purple-100 text-purple-600 border-purple-200',
                    'moderator' => 'bg-amber-100 text-amber-600 border-amber-200',
                    'driver' => 'bg-green-100 text-green-600 border-green-200',
                    'user' => 'bg-blue-100 text-blue-600 border-blue-200',
                ];
                $badgeClass = $roleColors[$user['role_name']] ?? 'bg-gray-100 text-gray-600';
                ?>
                <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase border <?= $badgeClass ?>">
                    <?= htmlspecialchars($user['role_label'] ?? $user['role_name']) ?>
                </span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="inline-block w-2 h-2 rounded-full <?= $user['is_active'] ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.5)]' : 'bg-red-400' ?>"></span>
            </td>
            <td class="p-0 w-56 border-l border-gray-100">
                <div class="flex items-stretch h-full min-h-15">
                    <button type="button"
                        onclick="openRoleModal('<?= $user['id'] ?>', '<?= $user['email'] ?>', '<?= $user['role_id'] ?>')"
                        class="group flex-1 flex flex-col justify-center px-5 py-3 text-left transition-all duration-200 hover:bg-gray-50 active:bg-gray-100 relative overflow-hidden">

                        <div class="flex items-center justify-between w-full">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-400 group-hover:text-blue-500 transition-colors">
                                    Текуща роля
                                </span>
                                <span class="text-sm font-bold text-gray-700 group-hover:text-gray-900 truncate">
                                    <?= htmlspecialchars($user['role_label']) ?>
                                </span>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300 group-hover:text-gray-500 transition-transform group-hover:translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="9 5l7 7-7 7" />
                            </svg>
                        </div>

                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-transparent group-hover:bg-blue-500 transition-all"></div>
                    </button>

                    <form action="/admin/users/delete/<?= $user['id'] ?>" method="POST" class="flex border-l border-gray-50" onsubmit="return confirm('Сигурни ли сте?')">
                        <button type="submit"
                            class="px-5 text-gray-300 hover:text-red-600 hover:bg-red-50/50 transition-all flex items-center justify-center group">
                            <span class="transform group-hover:scale-110 transition-transform">🗑️</span>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    <?php endforeach;
    $tableBody = ob_get_clean();

    View::component('table', 'admin/components', ['headers' => $headers, 'slot' => $tableBody]);
    ?>
</div>

<dialog id="global-role-modal" class="fixed inset-0 m-auto rounded-3xl shadow-2xl border border-gray-100 p-0 backdrop:bg-slate-900/40 backdrop:backdrop-blur-md w-[95%] max-w-sm overflow-hidden transition-all duration-300">
    <div class="p-5 border-b border-gray-200 flex justify-between items-center">
        <div>
            <h4 class="font-black text-slate-800 tracking-tight text-lg">Права за достъп</h4>
            <p class="text-[10px] text-slate-400 uppercase tracking-tighter font-bold mt-0.5">Контролен панел</p>
        </div>
        <button onclick="this.closest('dialog').close()" class="icon-button-primary">
            <?php HelperService::icon('times', 'w-6 h-6'); ?>
        </button>
    </div>

    <div class="p-5">
        <div class="mb-5 p-3 rounded-md bg-blue-50/50 border border-blue-100/50">
            <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                Актуализиране на профила на: <br>
                <span id="modal-user-email" class="text-blue-700 font-bold text-xs break-all"></span>
            </p>
        </div>

        <form action="/admin/users/update-role" method="POST" class="grid gap-2">
            <input type="hidden" name="user_id" id="modal-user-id">
            
            <?php foreach ($allRoles as $id => $r): ?>
                <button type="submit" name="role_id" value="<?= $id ?>"
                    id="role-btn-<?= $id ?>"
                    class="role-option-btn group w-full text-left px-5 py-4 rounded-2xl text-sm font-semibold transition-all duration-200 flex justify-between items-center border-2 border-transparent relative">
                    
                    <div class="flex items-center gap-3">
                        <div class="role-dot w-2 h-2 rounded-full bg-slate-300 group-hover:scale-125 transition-transform"></div>
                        <span class="role-label"><?= $r['label'] ?></span>
                    </div>

                    <div class="check-mark opacity-0 scale-50 transition-all duration-300">
                        <div class="bg-white text-blue-600 rounded-full p-1 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </button>
            <?php endforeach; ?>
        </form>
    </div>
</dialog>

<script>
const roleModal = document.getElementById('global-role-modal');
roleModal.addEventListener('click', (e) => {
    const dialogDimensions = roleModal.getBoundingClientRect();
    if (
        e.clientX < dialogDimensions.left ||
        e.clientX > dialogDimensions.right ||
        e.clientY < dialogDimensions.top ||
        e.clientY > dialogDimensions.bottom
    ) {
        roleModal.close();
    }
});

function openRoleModal(id, email, currentRoleId) {
    document.getElementById('modal-user-id').value = id;
    document.getElementById('modal-user-email').innerText = email;
    
    document.querySelectorAll('.role-option-btn').forEach(btn => {
        btn.classList.remove('bg-slate-900', 'text-white', 'border-slate-900', 'shadow-xl', 'shadow-slate-200');
        btn.classList.add('bg-slate-50', 'text-slate-600', 'hover:bg-slate-100');
        btn.querySelector('.check-mark').classList.add('opacity-0', 'scale-50');
        btn.querySelector('.role-dot').classList.remove('bg-blue-400');
        btn.querySelector('.role-dot').classList.add('bg-slate-300');
    });
    
    const activeBtn = document.getElementById('role-btn-' + currentRoleId);
    if(activeBtn) {
        activeBtn.classList.remove('bg-slate-50', 'text-slate-600', 'hover:bg-slate-100');
        activeBtn.classList.add('bg-slate-900', 'text-white', 'border-slate-900', 'shadow-xl', 'shadow-slate-200');
        activeBtn.querySelector('.check-mark').classList.remove('opacity-0', 'scale-50');
        activeBtn.querySelector('.role-dot').classList.remove('bg-slate-300');
        activeBtn.querySelector('.role-dot').classList.add('bg-blue-400');
    }
    
    roleModal.showModal();
}
</script>

<style>
#global-role-modal::backdrop {
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(8px);
    transition: opacity 0.3s ease;
}

#global-role-modal {
    transform: scale(0.95) translateY(10px);
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    display: block !important;
    pointer-events: none;
}

#global-role-modal[open] {
    transform: scale(1) translateY(0);
    opacity: 1;
    pointer-events: auto;
}
</style>
