<div x-data="{ open: false }" @click.away="open = false" class="relative inline-block text-left">
    
    {{-- Bouton de sélection --}}
    <div>
        <button 
            type="button" 
            @click="open = !open"
            class="inline-flex items-center justify-between w-full rounded-lg border border-gray-300 shadow-sm px-4 py-2.5 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200"
            :aria-expanded="open"
        >
            <div class="flex items-center space-x-3">
                {{-- Icône --}}
                <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                
                {{-- Nom de l'association --}}
                <div class="flex flex-col items-start">
                    @if($activeAssociation)
                        <span class="text-xs text-gray-500 font-normal">Association active</span>
                        <span class="font-semibold text-gray-900">{{ $activeAssociation->name }}</span>
                        @if(isset($activeAssociation->sigle) && $activeAssociation->sigle)
                            <span class="text-xs text-gray-500">({{ $activeAssociation->sigle }})</span>
                        @endif
                    @else
                        <span class="text-gray-500">Sélectionnez une association</span>
                    @endif
                </div>
            </div>

            {{-- Flèche dropdown --}}
            @if($hasMultipleAssociations)
                <svg 
                    class="ml-2 h-5 w-5 text-gray-400 transition-transform duration-200" 
                    :class="{ 'rotate-180': open }"
                    xmlns="http://www.w3.org/2000/svg" 
                    viewBox="0 0 20 20" 
                    fill="currentColor"
                >
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            @endif
        </button>
    </div>

    {{-- Menu déroulant --}}
    @if($hasMultipleAssociations)
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            @click="open = false"
            class="origin-top-right absolute right-0 mt-2 w-80 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-50"
            style="display: none;"
        >
            {{-- Header --}}
            <div class="px-4 py-3 bg-gray-50 rounded-t-lg">
                <p class="text-sm font-semibold text-gray-900">
                    Mes associations ({{ $userAssociations->count() }})
                </p>
                <p class="text-xs text-gray-500 mt-1">
                    Sélectionnez l'association avec laquelle vous souhaitez travailler
                </p>
            </div>

            {{-- Liste des associations --}}
            <div class="max-h-96 overflow-y-auto py-1">
                @forelse($userAssociations as $association)
                    <button 
                        wire:click="switchAssociation({{ $association->id }})"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50"
                        class="group w-full text-left px-4 py-3 hover:bg-blue-50 transition-colors duration-150 flex items-center justify-between {{ $association->id == $activeAssociationId ? 'bg-blue-50 border-l-4 border-blue-600' : '' }}"
                    >
                        <div class="flex items-center space-x-3 flex-1">
                            {{-- Indicateur actif --}}
                            <div class="flex-shrink-0">
                                @if($association->id == $activeAssociationId)
                                    <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <div class="h-5 w-5 rounded-full border-2 border-gray-300 group-hover:border-blue-400"></div>
                                @endif
                            </div>
                            
                            {{-- Info association --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">
                                    {{ $association->name }}
                                </p>
                                @if(isset($association->sigle) && $association->sigle)
                                    <p class="text-xs text-gray-500">
                                        {{ $association->sigle }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </button>
                @empty
                    <div class="px-4 py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">Aucune association disponible</p>
                    </div>
                @endforelse
            </div>

            {{-- Footer avec lien de gestion (optionnel) --}}
            @if(auth()->user() && method_exists(auth()->user(), 'can') && auth()->user()->can('manage associations'))
                <div class="px-4 py-2 bg-gray-50 rounded-b-lg">
                    <a href="{{ route('associations.index') }}" 
                       class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center space-x-2">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Gérer les associations</span>
                    </a>
                </div>
            @endif
        </div>
    @endif

    {{-- Indicateur de chargement --}}
    <div wire:loading wire:target="switchAssociation" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center rounded-lg">
        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>
</div>