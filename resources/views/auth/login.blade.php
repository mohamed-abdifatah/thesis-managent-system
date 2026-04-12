<x-guest-layout>
    <style>
        .signin-shell {
            width: 100%;
            max-width: 410px;
            margin: 0 auto;
            color: #101828;
        }

        .signin-head {
            margin-bottom: 24px;
        }

        .signin-title {
            margin: 0;
            font-family: 'Space Grotesk', 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(1.95rem, 3.2vw, 2.35rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            color: #111928;
        }

        .signin-subtitle {
            margin: 8px 0 0;
            color: #5a6b82;
            font-size: 0.92rem;
            line-height: 1.5;
        }

        .signin-status {
            margin-bottom: 14px;
            background: #e8f1ff;
            border: 1px solid #bfd5ff;
            color: #0f2f75;
            border-radius: 12px;
            font-size: 0.85rem;
            padding: 10px 12px;
        }

        .signin-group {
            text-align: left;
            margin-bottom: 16px;
        }

        .signin-label {
            display: block;
            margin-bottom: 8px;
            font-size: 11px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #344054;
            font-weight: 700;
        }

        .signin-control {
            width: 100%;
            border: 1px solid #d0dbea;
            background: #ffffff;
            border-radius: 14px;
            height: 50px;
            padding: 0 14px;
            font-size: 0.92rem;
            font-weight: 600;
            color: #111928;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .signin-control::placeholder {
            color: #98a6ba;
            font-weight: 500;
        }

        .signin-control:focus {
            outline: 0;
            border-color: #4e7cff;
            box-shadow: 0 0 0 4px rgba(78, 124, 255, 0.15);
        }

        .signin-control.is-invalid {
            border-color: #ef4444;
            box-shadow: none;
        }

        .signin-feedback {
            margin-top: 6px;
            color: #cf2121;
            font-size: 0.81rem;
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .signin-meta {
            margin: 2px 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .signin-remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.86rem;
            color: #475467;
            font-weight: 600;
        }

        .signin-remember input {
            width: 16px;
            height: 16px;
            accent-color: #375dfb;
            margin: 0;
        }

        .signin-forgot {
            color: #355ef8;
            text-decoration: none;
            font-size: 0.86rem;
            font-weight: 700;
        }

        .signin-forgot:hover {
            text-decoration: underline;
            color: #1939b7;
        }

        .signin-submit {
            width: 100%;
            height: 50px;
            border: 0;
            border-radius: 14px;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #ffffff;
            background: linear-gradient(135deg, #375dfb 0%, #1d4ed8 100%);
            box-shadow: 0 16px 26px rgba(29, 78, 216, 0.22);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .signin-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 20px 28px rgba(29, 78, 216, 0.26);
        }
    </style>

    <div class="signin-shell">
        <div class="signin-head">
            <h1 class="signin-title">Sign In</h1>
            <p class="signin-subtitle">Use your account credentials to access the dashboard.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="signin-status" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="signin-group">
                <label class="signin-label" for="email">{{ __('Email') }}</label>
                <input
                    id="email"
                    class="signin-control @error('email') is-invalid @enderror"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                >
                <x-input-error :messages="$errors->get('email')" class="signin-feedback" />
            </div>

            <!-- Password -->
            <div class="signin-group">
                <label class="signin-label" for="password">{{ __('Password') }}</label>
                <input
                    id="password"
                    class="signin-control @error('password') is-invalid @enderror"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Enter your password"
                >
                <x-input-error :messages="$errors->get('password')" class="signin-feedback" />
            </div>

            <div class="signin-meta">
                <label class="signin-remember" for="remember_me">
                    <input id="remember_me" type="checkbox" name="remember" @checked(old('remember'))>
                    <span>{{ __('Keep me logged in') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="signin-forgot" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <button type="submit" class="signin-submit">{{ __('Sign In') }}</button>
        </form>
    </div>
</x-guest-layout>
