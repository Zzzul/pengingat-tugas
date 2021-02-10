{{-- search --}}
<div class="form-group">
    <div class="input-group mb-4">
        <input type="text" class="form-control" id="search" placeholder="Cari... (Tekan &quot;/&quot; untuk fokus)"
            wire:model="search">
        <div class="input-group-append">
            <span class="input-group-text">
                <i class="fas fa-search" wire:loading.remove wire:target="search"></i>

                <div wire:loading wire:target="search">
                    <img src="{{ asset('assets/Dual Ring-1s-16px-(2).svg') }}" alt="Loading..">
                </div>
            </span>
        </div>
    </div>
</div>
{{-- end of form-grpup --}}
