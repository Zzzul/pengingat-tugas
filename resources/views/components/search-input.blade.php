{{-- search --}}
<div class="form-group row">
    <div class="col-md-2">
        <label for="paginate">Pagination</label>
        <img wire:loading wire:target="paginate_per_page" src="{{ asset('assets/Dual Ring-1s-16px-(2).svg') }}"
            class="mb-1" alt="Loading..">
        <select name="paginate" id="paginate" class="form-control" wire:model="paginate_per_page">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>

    </div>

    <div class="col-md-6"></div>

    <div class="col-md-4">
        <label class="control-label" for="search">Cari</label>
        <div class="input-group">
            <input type="text" class="form-control" id="search" placeholder="(Tekan &quot;/&quot; untuk fokus)"
                wire:model="search" autocomplete="off">
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
</div>
{{-- end of form-grpup --}}
