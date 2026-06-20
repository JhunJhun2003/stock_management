<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            အကောင့်ဖျက်ရန်
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            သင့်အကောင့်ကို ဖျက်လိုက်ပါက ၎င်းတွင်ရှိသော ဒေတာနှင့် အချက်အလက်အားလုံးကို အပြီးတိုင် ဖျက်ပစ်မည် ဖြစ်သည်။ အကောင့်မဖျက်မီ သင်သိမ်းဆည်းထားလိုသည့် အချက်အလက် သို့မဟုတ် ဒေတာများကို ဦးစွာ ဒေါင်းလုဒ်လုပ်ထားပါ။
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >အကောင့်ဖျက်ရန်</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                အကောင့်ဖျက်မှာ သေချာပါသလား
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                သင့်အကောင့်ကို ဖျက်လိုက်ပါက ၎င်းတွင်ရှိသော ဒေတာနှင့် အချက်အလက်အားလုံးကို အပြီးတိုင် ဖျက်ပစ်မည် ဖြစ်သည်။ အကောင့်ကို အပြီးတိုင်ဖျက်လိုကြောင်း အတည်ပြုရန် သင့်စကားဝှက်ကို ရိုက်ထည့်ပေးပါ။
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    မလုပ်တေ့ာပါ။
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    အကောင့်ဖျက်ရန်။
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
