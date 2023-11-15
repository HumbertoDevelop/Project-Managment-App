<x-app-layout>
    <x-slot name="header">
        
        @role('admin')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Administrator Dashboard Projects') }}
        </h2>
        @endrole
        
        @role('productManager')
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Manager Dashboard Projects') }}
        </h2>
        @endrole
    </x-slot>

    <livewire:app-dashboard>

</x-app-layout>
