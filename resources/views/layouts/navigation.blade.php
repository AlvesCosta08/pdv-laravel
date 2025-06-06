<!-- Responsive Settings Options -->
<div class="pt-4 pb-1 border-top border-secondary-subtle">
    @auth
        <div class="px-3">
            <div class="fw-semibold text-dark">{{ Auth::user()->name }}</div>
            <div class="text-muted small">{{ Auth::user()->email }}</div>
        </div>

        <div class="mt-3">
            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                {{ __('Profile') }}
            </a>

            <!-- Authentication -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    @else
        <div class="px-3">
            <div class="fw-semibold text-dark">Visitante</div>
            <div class="text-muted small">Por favor, faça login</div>
        </div>
    @endauth
</div>


