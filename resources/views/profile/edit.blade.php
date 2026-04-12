<x-app-layout>
    <div class="ta-page-head">
        <div>
            <span class="ta-page-kicker">Account</span>
            <h1 class="ta-page-title">Profile Settings</h1>
            <p class="ta-page-subtitle">Update your profile details, password, and account security preferences from one place.</p>
        </div>
        <div class="ta-page-actions">
            <a href="{{ route('dashboard') }}" class="ta-chip-link">
                <i class="feather-home"></i>
                Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-6">
            <div class="ta-panel mb-4">
                <div class="ta-panel-head">
                    <h3>Profile Information</h3>
                </div>
                <div class="ta-panel-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Danger Zone</h3>
                </div>
                <div class="ta-panel-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>

        <div class="col-xl-6">
            <div class="ta-panel">
                <div class="ta-panel-head">
                    <h3>Update Password</h3>
                </div>
                <div class="ta-panel-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
