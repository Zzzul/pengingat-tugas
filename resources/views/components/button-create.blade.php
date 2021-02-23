<div>
    <button class="btn btn-info btn-block" wire:loading.attr="disabled" wire:click="showForm('add')">
        <div wire:loading.remove wire:target="showForm">
            <i class="fas fa-plus mr-1"></i>
            Tambah Data
        </div>

        <x-loading target="showForm"></x-loading>
    </button>
</div>
