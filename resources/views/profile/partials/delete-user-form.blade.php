<section class="space-y-6" x-data="{ confirmingUserDeletion: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button type="button"
        @click="confirmingUserDeletion = true"
        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
        {{ __('Delete Account') }}
    </button>

    <div x-show="confirmingUserDeletion"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center bg-gray-500 bg-opacity-75"
         style="display: none;">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-lg p-6" @click.outside="confirmingUserDeletion = false">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="mt-6">
                    <label for="password" class="sr-only">{{ __('Password') }}</label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3/4 rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="{{ __('Password') }}"
                    />

                    @error('password', 'userDeletion')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" @click="confirmingUserDeletion = false" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="ms-3 inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Delete Account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
