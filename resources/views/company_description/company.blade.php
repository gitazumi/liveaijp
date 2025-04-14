@extends('company_description.description_layout')
@section('content')
    <h1 class="wf txt-42 txt-s f-weight-800"> {{ __('運営会社') }}</h1>

    <div class="wf mt-50 flex-col">
        <p class="wf txt-24 m-0 txt-s bold">{{ __('会社概要') }}</p>
        <div class="p-description wf m-0 flex-col mb-20">
            @php
                $companyDetails = [
                    '会社名' => 'Sound Graffiti 株式会社',
                    '所在地' => '〒160-0004　東京都新宿区四谷3-4-3 SCビルB1',
                    '連絡先' => ['TEL:03-5315-4781'],
                    '役員' => ['代表取締役　澤亜澄', '取締役　田中繁之', '取締役　神本圭祐'],
                    '設立年' => '2013年',
                    '事業内容' => ['・音楽を聴くことのできる飲食店の経営', '・レンタルスペース及び貸しスタジオの経営', '・インターネットを利用した音楽、映像、音声等の配信サービス業', '・デザイン、映像、音楽及びプログラムの企画、制作及び販売', '・上記各号に付随する一切の事業'],
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
