<div class="sidebar sidebar-hidden" id="sidebar">
    <div class="sidebar-close" onclick="sidebar_close()">
        <img src="{{ asset('images/sidebar_close.png') }}" alt="Close">
    </div>

    @foreach ($items as $item)
        <div class="side-list">
            <div class="side-list-img">
                <img src="{{ asset($item['icon']) }}" alt="{{ $item['title'] }}">
            </div>
            <p class="txt-20 color-white txt-hidden">{{ $item['title'] }}</p>
        </div>
    @endforeach
</div>