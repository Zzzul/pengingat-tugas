<div>
    <button type="button" class="btn btn-dark btn-block mr-2 mt-3" wire:loading.attr="disabled" wire:click="hideForm()">
        <div wire:loading.remove wire:target="hideForm">
            <i class="fas fa-times mr-1"></i>
            Batal
        </div>
        <x-loading target="hideForm"></x-loading>
    </button>
    <button type="submit" class="btn btn-success btn-block mt-2" wire:loading.attr="disabled"
        wire:target="{{ $target }}">
        <div wire:loading.remove wire:target="{{ $target }}">
            <i class="fas fa-save mr-1"></i>
            @if ($target === 'store')
                Submit
            @else
                Update
            @endif
        </div>

        <x-loading target="{{ $target }}"></x-loading>
    </button>
</div>
