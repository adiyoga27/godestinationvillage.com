@extends('customer/layout')

@section('content')

<x-partials.page-hero
    title="{{ __('My Account') }}"
    subtitle="{{ __('Update your profile details and keep your information up to date.') }}"
    image="assets/customer/img/page-title-area/account.jpg"
    :crumbs="[__('Home') => '/', __('My Account') => '']"
/>

<section class="section-pad bg-cream-50">
    <div class="container-gd max-w-3xl">
        <form action="{{ url('account/' . $user->id) }}" method="post" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="card p-8 text-center sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">{{ __('Profile Picture') }}</h2>
                <div class="mt-6 flex flex-col items-center gap-5">
                    <div class="relative">
                        <div class="h-28 w-28 overflow-hidden rounded-full bg-cream-100 ring-4 ring-white shadow-lift">
                            @if (Auth::user()->avatar)
                                <img src="{{ url('storage/users/' . Auth::user()->avatar) }}" id="imgPlaceholder" alt="Profile avatar" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-brand-50">
                                    <span class="font-display text-4xl font-bold text-brand-600">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <label for="chooseFile" class="absolute -bottom-1 -right-1 flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-brand-600 text-white shadow-lg transition hover:bg-brand-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z" /></svg>
                        </label>
                    </div>
                    <label for="chooseFile" class="cursor-pointer text-sm font-semibold text-brand-600 hover:underline">{{ __('Click to upload a new picture') }}</label>
                    <input type="file" name="uploadfile" id="chooseFile" class="hidden" accept="image/*">
                </div>
            </div>

            <div class="card p-8 sm:p-10">
                <h2 class="font-display text-xl font-bold text-ink-950">{{ __('Customer Information') }}</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <input type="hidden" name="customerid" value="{{ $user->id }}">
                    <label class="block">
                        <span class="label-gd">{{ __('Customer Name') }}</span>
                        <input type="text" name="customername" value="{{ $user->name }}" class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Email') }}</span>
                        <input type="email" name="email" value="{{ $user->email }}" class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Phone') }}</span>
                        <input type="text" name="phone" value="{{ $user->phone }}" class="input-gd">
                    </label>
                    <label class="block">
                        <span class="label-gd">{{ __('Country') }}</span>
                        <select name="country" class="input-gd">
                            <option value="{{ $user->country }}" selected>{{ $user->country }}</option>
                            <option value="United States">United States</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="Australia">Australia</option>
                            <option value="Canada">Canada</option>
                            <option value="Afghanistan">Afghanistan</option>
                            <option value="Albania">Albania</option>
                            <option value="Algeria">Algeria</option>
                            <option value="Argentina">Argentina</option>
                            <option value="Armenia">Armenia</option>
                            <option value="Austria">Austria</option>
                            <option value="Bangladesh">Bangladesh</option>
                            <option value="Belgium">Belgium</option>
                            <option value="Brazil">Brazil</option>
                            <option value="Cambodia">Cambodia</option>
                            <option value="Chile">Chile</option>
                            <option value="China">China</option>
                            <option value="Colombia">Colombia</option>
                            <option value="Croatia">Croatia</option>
                            <option value="Czech Republic">Czech Republic</option>
                            <option value="Denmark">Denmark</option>
                            <option value="Egypt">Egypt</option>
                            <option value="Finland">Finland</option>
                            <option value="France">France</option>
                            <option value="Germany">Germany</option>
                            <option value="Greece">Greece</option>
                            <option value="Hong Kong">Hong Kong</option>
                            <option value="Hungary">Hungary</option>
                            <option value="Iceland">Iceland</option>
                            <option value="India">India</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Ireland">Ireland</option>
                            <option value="Israel">Israel</option>
                            <option value="Italy">Italy</option>
                            <option value="Japan">Japan</option>
                            <option value="Korea, Republic of">Korea, Republic of</option>
                            <option value="Malaysia">Malaysia</option>
                            <option value="Mexico">Mexico</option>
                            <option value="Netherlands">Netherlands</option>
                            <option value="New Zealand">New Zealand</option>
                            <option value="Norway">Norway</option>
                            <option value="Pakistan">Pakistan</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Poland">Poland</option>
                            <option value="Portugal">Portugal</option>
                            <option value="Qatar">Qatar</option>
                            <option value="Romania">Romania</option>
                            <option value="Saudi Arabia">Saudi Arabia</option>
                            <option value="Singapore">Singapore</option>
                            <option value="South Africa">South Africa</option>
                            <option value="Spain">Spain</option>
                            <option value="Sri Lanka">Sri Lanka</option>
                            <option value="Sweden">Sweden</option>
                            <option value="Switzerland">Switzerland</option>
                            <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                            <option value="Thailand">Thailand</option>
                            <option value="Turkey">Turkey</option>
                            <option value="United Arab Emirates">United Arab Emirates</option>
                            <option value="Viet Nam">Viet Nam</option>
                        </select>
                    </label>
                    <label class="block sm:col-span-2">
                        <span class="label-gd">{{ __('Address') }}</span>
                        <input type="text" name="address" value="{{ $user->address }}" class="input-gd">
                    </label>
                </div>
                <div class="mt-8 flex flex-col items-center justify-between gap-4 sm:flex-row">
                    <p class="text-xs text-ink-400">{{ __('* Please check your form before submitting.') }}</p>
                    <button type="submit" class="btn btn-primary shrink-0">{{ __('Update Profile') }}</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('js')
<script>
    (function () {
        var file = document.getElementById('chooseFile');
        var img = document.getElementById('imgPlaceholder');
        if (file && img) {
            file.addEventListener('change', function () {
                if (file.files && file.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) { img.src = e.target.result; };
                    reader.readAsDataURL(file.files[0]);
                }
            });
        }
    })();
</script>
@endsection