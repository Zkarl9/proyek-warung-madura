<a href="{{ route('admin.dashboard') }}" class="nav-tag {{ Route::is('admin.dashboard') ? 'is-active' : '' }}">
    <i class="fas fa-chart-line"></i> Dashboard
</a>

<a href="{{ route('admin.training.index') }}" class="nav-tag {{ Route::is('admin.training.*') ? 'is-active' : '' }}">
    <i class="fas fa-brain"></i> Training AI
</a>

<a href="{{ route('admin.users.index') }}" class="nav-tag {{ Route::is('admin.users.*') ? 'is-active' : '' }}">
    <i class="fas fa-users-cog"></i> Akun Owner
</a>