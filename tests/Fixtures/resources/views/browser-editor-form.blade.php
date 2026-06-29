<form wire:submit="save" class="w-full space-y-6">
    {{ $this->form }}

    <div class="flex items-center gap-4">
        <button
            type="submit"
            class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500"
        >
            Save
        </button>

        @if ($savedContent)
            <output data-testid="saved-content">{{ $savedContent }}</output>
        @endif
    </div>
</form>
