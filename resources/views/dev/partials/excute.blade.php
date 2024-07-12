<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Input Sql') }}
        </h2>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="get" action="{{ route('dev.index') }}" class="mt-6 space-y-6">
        <div>
            <x-text-input id="sql" name="sql" type="text" class="mt-1 block w-full" :value="old('sql', $sql)" required autofocus autocomplete="sql" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Excute') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
{{--    <script>--}}
{{--        var form = document.getElementsByTagName('form')[0];--}}
{{--        form.addEventListener('submit',function(e){--}}
{{--            e.preventDefault();--}}
{{--        });--}}
{{--    </script>--}}
</section>
