@extends('base.base')
@section('title', 'Akses User')
@section('content')

<style>
    .permission-group-header {
        background-color: #f8f9fa;
        padding: 12px 15px;
        border-left: 4px solid #f25920;
        cursor: pointer;
        user-select: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 5px;
        border-radius: 4px;
    }
    .permission-group-header:hover {
        background-color: #e9ecef;
    }
    .permission-group-header.active {
        background-color: #f25920;
        color: white;
    }
    .permission-group-content {
        display: none;
        padding-left: 20px;
        margin-bottom: 10px;
    }
    .permission-group-content.show {
        display: block;
    }
    .permission-item {
        padding: 10px 15px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .permission-item:last-child {
        border-bottom: none;
    }
    .permission-item:hover {
        background-color: #f8f9fa;
    }
    .permission-actions {
        display: flex;
        gap: 20px;
        align-items: center;
    }
    .action-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #f25920;
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    .action-badge {
        min-width: 70px;
        text-align: center;
        font-size: 0.75rem;
    }
    .select-all-group {
        margin-left: auto;
    }
    .permission-from-role {
        background-color: #e8f4fd;
        border-left: 3px solid #0d6efd;
    }
    .permission-from-role .form-check-input:disabled {
        cursor: not-allowed;
    }
    .badge-from-role {
        font-size: 0.65rem;
        background-color: #0d6efd;
    }
</style>

<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Akses User</h5>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="users-table" class="table table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Permissions</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $user->nama }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge" style="background-color: #f25920 !important; color: white !important; padding: 0.35em 0.65em;">
                                        {{ $user->roles->first()?->name ?? 'Tidak ada role' }}
                                    </span>
                                </td>
                                <td>
                                    @if($user->permissions->count() > 0)
                                        <span class="badge bg-info">{{ $user->permissions->count() }} permission</span>
                                    @else
                                        <span class="text-muted">Tidak ada</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" 
                                            class="btn btn-sm btn-primary btn-manage-access" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalAccess{{ $user->id }}"
                                            data-user-id="{{ $user->id }}">
                                        <i class="ri-settings-3-line me-1"></i>
                                        Kelola Akses
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal untuk setiap user -->
                            <div class="modal fade" id="modalAccess{{ $user->id }}" tabindex="-1">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header" style="background-color: #f25920;">
                                            <h5 class="modal-title text-white">
                                                <i class="ri-user-settings-line me-2"></i>
                                                Kelola Akses - {{ $user->nama }}
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Ubah Role -->
                                            <div class="mb-4">
                                                <label class="form-label fw-semibold">Role</label>
                                                <select class="form-select select-role" id="userRole{{ $user->id }}" data-user-id="{{ $user->id }}">
                                                    @foreach($roles as $role)
                                                        @if($role->name === 'Admin' && !$user->roles->contains('name', 'Admin'))
                                                            @continue
                                                        @endif
                                                        <option value="{{ $role->name }}" 
                                                                {{ $user->roles->first()?->name == $role->name ? 'selected' : '' }}>
                                                            {{ ucfirst($role->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Info: Permission dari Role -->
                                            @php
                                                $userRole = $user->roles->first();
                                                $rolePermissions = $userRole?->permissions ?? collect();
                                            @endphp
                                            @if($rolePermissions->isNotEmpty())
                                            <div class="alert alert-info mb-4">
                                                <div class="d-flex align-items-start">
                                                    <i class="ri-information-line fs-4 me-2 mt-1"></i>
                                                    <div>
                                                        <strong>Permission dari Role {{ ucfirst($userRole->name) }}:</strong>
                                                        <p class="mb-0 small mt-1">
                                                            User ini otomatis mendapat permission berikut dari role. Permission ini tidak perlu ditambahkan lagi.
                                                        </p>
                                                        <div class="d-flex flex-wrap gap-1 mt-2">
                                                            @foreach($rolePermissions as $rp)
                                                                <span class="badge bg-primary">{{ $rp->name }}</span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <!-- Search Menu -->
                                            <div class="mb-4">
                                                <label class="form-label fw-semibold">Cari menu</label>
                                                <input type="text" 
                                                       class="form-control search-menu-input" 
                                                       id="searchMenu{{ $user->id }}"
                                                       placeholder="Cari menu atau permission..."
                                                       data-user-id="{{ $user->id }}">
                                            </div>

                                            <!-- Permissions List -->
                                            <div class="mb-2">
                                                <strong>Permission tambahan:</strong> Centang untuk memberi akses ekstra di luar permission dari role. Yang bertanda "Dari Role" sudah otomatis didapat user.
                                            </div>
                                            <div class="permissions-container" id="permissionsContainer{{ $user->id }}">
                                                @foreach($permissions as $moduleName => $modulePermissions)
                                                    <div class="permission-group" data-module="{{ strtolower($moduleName) }}">
                                                        <div class="permission-group-header" 
                                                             onclick="toggleGroup('{{ $user->id }}', '{{ strtolower($moduleName) }}')"
                                                             id="groupHeader{{ $user->id }}_{{ strtolower($moduleName) }}">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <i class="ri-arrow-down-s-line group-icon" id="icon{{ $user->id }}_{{ strtolower($moduleName) }}"></i>
                                                                <span class="fw-semibold">{{ ucwords(str_replace('-', ' ', $moduleName)) }}</span>
                                                            </div>
                                                            <div class="select-all-group">
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-outline-primary btn-select-all-group"
                                                                        data-user-id="{{ $user->id }}"
                                                                        data-module="{{ strtolower($moduleName) }}"
                                                                        onclick="event.stopPropagation(); selectAllGroup('{{ $user->id }}', '{{ strtolower($moduleName) }}')">
                                                                    <i class="ri-checkbox-multiple-line me-1"></i>
                                                                    Pilih Semua
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="permission-group-content show" 
                                                             id="groupContent{{ $user->id }}_{{ strtolower($moduleName) }}">
                                                            @foreach($modulePermissions as $permission)
                                                                @php
                                                                    $parts = explode('-', $permission->name);
                                                                    $action = $parts[0] ?? 'other';
                                                                    $menuName = $parts[1] ?? $permission->name;
                                                                    $isFromRole = $rolePermissions->contains('name', $permission->name);
                                                                    $isChecked = $user->hasPermissionTo($permission->name);
                                                                @endphp
                                                                
                                                                <div class="permission-item {{ $isFromRole ? 'permission-from-role' : '' }}" 
                                                                     data-menu="{{ strtolower($moduleName) }}" 
                                                                     data-permission="{{ $permission->name }}"
                                                                     data-from-role="{{ $isFromRole ? '1' : '0' }}">
                                                                    <div class="d-flex align-items-center gap-2">
                                                                        <input type="checkbox" 
                                                                               class="form-check-input permission-checkbox" 
                                                                               id="perm{{ $user->id }}_{{ $permission->id }}"
                                                                               value="{{ $permission->name }}"
                                                                               {{ $isChecked ? 'checked' : '' }}
                                                                               {{ $isFromRole ? 'disabled' : '' }}
                                                                               data-user-id="{{ $user->id }}"
                                                                               data-module="{{ strtolower($moduleName) }}"
                                                                               data-from-role="{{ $isFromRole ? '1' : '0' }}">
                                                                        <label class="form-check-label ms-2" for="perm{{ $user->id }}_{{ $permission->id }}">
                                                                            {{ ucwords(str_replace('-', ' ', $menuName)) }}
                                                                        </label>
                                                                        @if($isFromRole)
                                                                            <span class="badge badge-from-role">Dari Role</span>
                                                                        @endif
                                                                    </div>
                                                                    <div class="permission-actions">
                                                                        <div class="action-toggle">
                                                                            @if(!$isFromRole)
                                                                            <label class="switch">
                                                                                <input type="checkbox" 
                                                                                       class="permission-checkbox permission-toggle" 
                                                                                       id="perm{{ $user->id }}_{{ $permission->id }}_toggle"
                                                                                       value="{{ $permission->name }}"
                                                                                       {{ $isChecked ? 'checked' : '' }}
                                                                                       data-user-id="{{ $user->id }}"
                                                                                       data-checkbox-id="perm{{ $user->id }}_{{ $permission->id }}">
                                                                                <span class="slider"></span>
                                                                            </label>
                                                                            @endif
                                                                            <span class="action-badge badge bg-{{ $action == 'view' ? 'info' : ($action == 'create' ? 'success' : ($action == 'edit' || $action == 'update' ? 'warning' : ($action == 'delete' ? 'danger' : 'secondary'))) }}">
                                                                                {{ ucfirst($action) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="button" class="btn btn-primary btn-save-permissions" data-user-id="{{ $user->id }}">
                                                <i class="ri-save-line me-1"></i>
                                                Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleGroup(userId, moduleName) {
    const content = document.getElementById('groupContent' + userId + '_' + moduleName);
    const icon = document.getElementById('icon' + userId + '_' + moduleName);
    const header = document.getElementById('groupHeader' + userId + '_' + moduleName);
    
    if (content.classList.contains('show')) {
        content.classList.remove('show');
        icon.classList.remove('ri-arrow-down-s-line');
        icon.classList.add('ri-arrow-right-s-line');
        header.classList.remove('active');
    } else {
        content.classList.add('show');
        icon.classList.remove('ri-arrow-right-s-line');
        icon.classList.add('ri-arrow-down-s-line');
        header.classList.add('active');
    }
}

function selectAllGroup(userId, moduleName) {
    const container = document.getElementById('permissionsContainer' + userId);
    // Hanya toggle yang bukan dari role (bisa diedit)
    const checkboxes = container.querySelectorAll(`input.permission-checkbox[data-module="${moduleName}"][data-from-role="0"]:not(.permission-toggle)`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
        const toggleId = checkbox.id + '_toggle';
        const toggle = document.getElementById(toggleId);
        if (toggle) {
            toggle.checked = !allChecked;
        }
    });
}

function filterPermissions(userId) {
    const searchTerm = document.getElementById('searchMenu' + userId).value.toLowerCase();
    const container = document.getElementById('permissionsContainer' + userId);
    const groups = container.querySelectorAll('.permission-group');
    
    groups.forEach(group => {
        const moduleName = group.getAttribute('data-module');
        const items = group.querySelectorAll('.permission-item');
        let hasVisible = false;
        
        items.forEach(item => {
            const menuText = item.getAttribute('data-menu') || '';
            const permissionText = item.getAttribute('data-permission') || '';
            
            if (menuText.includes(searchTerm) || permissionText.includes(searchTerm)) {
                item.style.display = 'flex';
                hasVisible = true;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Show/hide group based on visible items
        if (hasVisible) {
            group.style.display = 'block';
            // Auto expand if has search term
            if (searchTerm) {
                const content = group.querySelector('.permission-group-content');
                const icon = group.querySelector('.group-icon');
                const header = group.querySelector('.permission-group-header');
                if (content && !content.classList.contains('show')) {
                    content.classList.add('show');
                    if (icon) {
                        icon.classList.remove('ri-arrow-right-s-line');
                        icon.classList.add('ri-arrow-down-s-line');
                    }
                    if (header) header.classList.add('active');
                }
            }
        } else {
            group.style.display = 'none';
        }
    });
}

function updateRole(userId) {
    const roleSelect = document.getElementById('userRole' + userId);
    const role = roleSelect.value;
    
    fetch(`/access-management/${userId}/role`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ role: role })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Reload agar tampilan permission dari role terupdate
                location.reload();
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal mengupdate role'
        });
    });
}

function copyFromRole(userId) {
    const roleSelect = document.getElementById('copyRole' + userId);
    const roleName = roleSelect.value;
    
    if (!roleName) {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Pilih role terlebih dahulu'
        });
        return;
    }
    
    Swal.fire({
        title: 'Konfirmasi',
        text: `Copy semua permission dari role "${roleName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f25920',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Copy',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/access-management/${userId}/copy-role`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ role_name: roleName })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadUserPermissions(userId);
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal copy permission'
                });
            });
        }
    });
}

function loadUserPermissions(userId) {
    fetch(`/access-management/${userId}/permissions`)
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('permissionsContainer' + userId);
            const checkboxes = container.querySelectorAll('input.permission-checkbox:not(.permission-toggle)');
            const rolePerms = data.role_permissions || [];
            const directPerms = data.permissions || [];
            
            checkboxes.forEach(checkbox => {
                const permissionName = checkbox.value;
                const fromRole = checkbox.getAttribute('data-from-role') === '1';
                const isChecked = fromRole ? rolePerms.includes(permissionName) : directPerms.includes(permissionName);
                checkbox.checked = isChecked;
                
                const toggleId = checkbox.id + '_toggle';
                const toggle = document.getElementById(toggleId);
                if (toggle) {
                    toggle.checked = isChecked;
                }
            });
        })
        .catch(error => {
            console.error('Error loading permissions:', error);
        });
}

function savePermissions(userId) {
    const container = document.getElementById('permissionsContainer' + userId);
    // Hanya simpan permission TAMBAHAN (bukan dari role) - permission dari role otomatis dari role user
    const checkboxes = container.querySelectorAll('input.permission-checkbox:not(.permission-toggle)[data-from-role="0"]:checked');
    const extraPermissions = Array.from(checkboxes).map(cb => cb.value);
    
    fetch(`/access-management/${userId}/permissions`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ permissions: extraPermissions })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal menyimpan permissions'
        });
    });
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Load permissions when modal opens
    document.querySelectorAll('.btn-manage-access').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            loadUserPermissions(userId);
        });
    });
    
    // Update role on change
    document.querySelectorAll('.select-role').forEach(select => {
        select.addEventListener('change', function() {
            const userId = this.getAttribute('data-user-id');
            updateRole(userId);
        });
    });
    
    // Copy from role
    document.querySelectorAll('.btn-copy-role').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            copyFromRole(userId);
        });
    });
    
    // Search menu
    document.querySelectorAll('.search-menu-input').forEach(input => {
        input.addEventListener('keyup', function() {
            const userId = this.getAttribute('data-user-id');
            filterPermissions(userId);
        });
    });
    
    // Save permissions
    document.querySelectorAll('.btn-save-permissions').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            savePermissions(userId);
        });
    });
    
    // Sync toggle switch with checkbox
    document.querySelectorAll('.permission-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const checkboxId = this.getAttribute('data-checkbox-id');
            const checkbox = document.getElementById(checkboxId);
            if (checkbox) {
                checkbox.checked = this.checked;
            }
        });
    });
    
    // Sync checkbox with toggle
    document.querySelectorAll('input.permission-checkbox:not(.permission-toggle)').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const toggleId = this.id + '_toggle';
            const toggle = document.getElementById(toggleId);
            if (toggle) {
                toggle.checked = this.checked;
            }
        });
    });
});

// Initialize DataTable
$(document).ready(function() {
    $('#users-table').DataTable({
        responsive: true,
        pageLength: 10,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/id.json'
        }
    });
});
</script>

@endsection
