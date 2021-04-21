<div>
    <button class="btn btn-primary btn-block" wire:loading.attr="disabled" wire:click="showForm('add')"
        data-toggle="modal" data-target="#exampleModal">
        <div wire:loading.remove wire:target="showForm">
            <i class="fas fa-plus mr-1"></i>
            Tambah Data
        </div>

        <x-loading target="showForm"></x-loading>
    </button>
</div>
