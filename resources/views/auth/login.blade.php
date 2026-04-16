<x-guest-layout>
    <style>
        .auth-form {
            width: 100%;
            color: var(--ink);
        }

        .auth-form-head {
            margin-bottom: 18px;
        }

        .auth-form-title {
            margin: 0;
            font-size: clamp(1.5rem, 3vw, 1.95rem);
            letter-spacing: -0.03em;
            line-height: 1.14;
        }

        .auth-form-subtitle {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 0.89rem;
            line-height: 1.62;
        }

        .auth-group {
            margin-bottom: 14px;
            text-align: left;
        }

        .auth-group .field-error {
            margin-top: 6px;
            margin-bottom: 0;
            padding-left: 0;
            list-style: none;
        }

        .auth-meta {
            margin: 2px 0 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .auth-remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.84rem;
            color: var(--muted);
            font-weight: 700;
        }

        .auth-remember input {
            width: 16px;
            height: 16px;
            accent-color: #1f58d8;
            margin: 0;
        }

        .auth-link {
            font-size: 0.84rem;
            font-weight: 700;
        }

        .auth-submit {
            width: 100%;
            min-height: 48px;
            border: 0;
            border-radius: 12px;
            color: #ffffff;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            background: linear-gradient(145deg, var(--accent), var(--accent-strong));
            box-shadow: 0 14px 24px rgba(217, 79, 32, 0.28);
            transition: transform 0.16s ease, box-shadow 0.16s ease;
        }

        .auth-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 28px rgba(217, 79, 32, 0.31);
        }

        .auth-register-cta {
            margin-top: 14px;
            color: var(--muted);
            text-align: center;
            font-size: 0.84rem;
        }
    </style>

    <div class="auth-form">
        <div class="auth-form-head">
            <h2 class="auth-form-title">Sign in to your workspace</h2>
            <p class="auth-form-subtitle">Sign in to continue.</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="auth-group">
                <label for="email">{{ __('Email') }}</label>
                <input
                    id="email"
                    class="form-control"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                >
                <x-input-error :messages="$errors->get('email')" class="field-error" />
            </div>

            <div class="auth-group">
                <label for="password">{{ __('Password') }}</label>
                <input
                    id="password"
                    class="form-control"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                >
                <x-input-error :messages="$errors->get('password')" class="field-error" />
            </div>

            <div class="auth-meta">
                <label class="auth-remember" for="remember_me">
                    <input id="remember_me" type="checkbox" name="remember" @checked(old('remember'))>
                    <span>{{ __('Keep me logged in') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="auth-link" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <button type="submit" class="auth-submit">{{ __('Sign In') }}</button>
        </form>

        @if (Route::has('register'))
            <p class="auth-register-cta">
                Need an account?
                <a class="auth-link" href="{{ route('register') }}">Create one</a>
            </p>
        @endif
    </div>
</x-guest-layout>
