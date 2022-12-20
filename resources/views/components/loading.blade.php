<div wire:loading wire:target="{{ $target }}">
    {{-- <img src="{{ asset('assets/Dual Ring-1s-25px-(2).svg') }}" alt="Loading.."> --}}

    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="18px"
        viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
        <circle cx="50" cy="50" r="40" stroke-width="8" stroke="#ffffff"
            stroke-dasharray="62.83185307179586 62.83185307179586" fill="none" stroke-linecap="round">
            <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" keyTimes="0;1"
                values="0 50 50;360 50 50"></animateTransform>
        </circle>
    </svg>
</div>
