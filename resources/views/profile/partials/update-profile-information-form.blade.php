<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            ပရိုဖိုင် အချက်အလက်
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            သင့်အကောင့်၏ ပရိုဖိုင်အချက်အလက်များနှင့် အီးမေးလ်လိပ်စာကို ပြင်ဆင်ပါ။
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        သင့်အီးမေးလ်လိပ်စာအား အတည်မပြုရသေးပါ။

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            အီးမေးလ်အတည်ပြုချက်လင့်ခ် ပြန်လည်ပေးပို့ရန် ဤနေရာကိုနှိပ်ပါ။
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            အီးမေးလ်အတည်ပြုချက်လင့်ခ်အသစ်ကို သင့်အီးမေးလ်လိပ်စာထံသို့ ပေးပို့လိုက်ပါပြီ။
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>သိမ်းဆည်းမည်။</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >သိမ်းဆည်းပြီးပါပြီ။</p>
            @endif
        </div>
    </form>
</section>
