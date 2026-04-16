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

        .auth-password-hint {
            margin-top: 6px;
            color: var(--muted);
            font-size: 0.77rem;
        }

        .auth-actions {
            margin-top: 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .auth-link {
            font-size: 0.84rem;
            font-weight: 700;
        }

        .auth-submit {
            min-height: 48px;
            border: 0;
            border-radius: 12px;
            padding: 0 22px;
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

        @media (max-width: 640px) {
            .auth-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .auth-submit {
                width: 100%;
            }

            .auth-actions .auth-link {
                text-align: center;
            }
        }
    </style>

    <div class="auth-form">
        <div class="auth-form-head">
            <h2 class="auth-form-title">Create your account</h2>
            <p class="auth-form-subtitle">Set up your identity to access role-specific thesis workflows and collaboration tools.</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="auth-group">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Your full name" />
                <x-input-error :messages="$errors->get('name')" class="field-error" />
            </div>

            <div class="auth-group">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="you@example.com" />
                <x-input-error :messages="$errors->get('email')" class="field-error" />
            </div>

            <div class="auth-group">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Create a secure password" />
                <p class="auth-password-hint">Use at least 8 characters with a mix of letters, numbers, and symbols.</p>
                <x-input-error :messages="$errors->get('password')" class="field-error" />
            </div>

            <div class="auth-group">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Re-enter your password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="field-error" />
            </div>

            <div class="auth-actions">
                <a class="auth-link" href="{{ route('login') }}">{{ __('Already registered? Sign in') }}</a>

                <button type="submit" class="auth-submit">
                    {{ __('Create account') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
