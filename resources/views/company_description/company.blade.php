@extends('company_description.description_layout')
@section('content')
    <h1 class="wf txt-42 txt-s f-weight-800"> {{ __('運営会社') }}</h1>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-24 m-0 txt-s bold"> {{ __('企業理念') }}</p>
        <div class="p-description wf m-0 flex-col">
            <p class="wf txt-16 m-0 txt-s">
                {{ __('視力低下を食い止め、トレーニングにより視力を向上させる『眼育』の研究・指導・普及を推進します。
                                ') }}
            </p>
        </div>
    </div>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-24 m-0 txt-s bold">{{ __('会社概要') }}</p>
        <div class="p-description wf m-0 flex-col mb-20">
            @php
                $companyDetails = [
                    '屋号' => '',
                    '運営会社' => '',
                    '代表者名' => '',
                    '所在地' => '',
                    '連絡先' => ['TEL: 045-988-5124', 'FAX: 045-988-5304', 'E-Mail: info@test.com'],
                    '事業内容' => ['目のポータルサイト 視力ランドの運営', '視力回復 アプリ制作・販売'],
                    '取引銀行' => ['三井住友銀行 青葉台支店', '横浜銀行 青葉台支店'],
                ];
            @endphp

            @foreach ($companyDetails as $title => $detail)
                <div class="summary wf m-0 border-b">
                    <p class="txt-16 txt-5 m-0">{{ $title }}</p>
                    <div class="flex-col">
                        @if (is_array($detail))
                            @foreach ($detail as $item)
                                <p class="wf txt-16 m-0 txt-s">{{ __($item) }}</p>
                            @endforeach
                        @else
                            <p class="wf txt-16 m-0 txt-s">{{ __($detail) }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
