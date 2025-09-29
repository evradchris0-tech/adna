<div>
    @if (session('error') || !$errors->isEmpty())
        <div class="message error">
            <i class="fa fa-plus" wire:click="removeAlert"></i>
            @if (session('error'))
                {{ session('error') }}
            @endif
            @if (!$errors->isEmpty())
                <ul>
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
    @if (session('message'))
        <div class="message success">
            <i class="fa fa-plus" wire:click="removeAlert"></i>
            {{ session('message') }}
        </div>
    @endif

</div>
