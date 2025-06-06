<!-- Responsive Settings Options -->
<div class="d-flex align-items-center justify-content-between border-top border-secondary-subtle px-2 py-2 gap-3 flex-wrap">
    @auth
        <div class="d-flex align-items-center gap-2">
            <div class="fw-semibold text-dark">{{ Auth::user()->name }}</div>
            <div class="text-muted small">({{ Auth::user()->email }})</div>
        </div>

        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">
            {{ __('Profile') }}
        </a>

        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-danger">
                {{ __('Log Out') }}
            </button>
        </form>
    @else
        <div class="d-flex align-items-center gap-2">
            <div class="fw-semibold text-dark">Visitante</div>
            <div class="text-muted small">Por favor, faça login</div>
        </div>

        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-success">
            {{ __('Login') }}
        </a>
        <a href="{{ route('register') }}" class="btn btn-sm btn-outline-primary">
            {{ __('Register') }}
        </a>
    @endauth
</div>




