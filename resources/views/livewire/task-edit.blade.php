<div>
    <div class="container shadow-xl p-4 rounded-md bg-white">
        <div class="grid grid-cols-1 py-5">
            <label>
                Description task
                <textarea wire:model="description" placeholder="{{ __('Describe a task') }}"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mb-4"
                    required></textarea>
            </label>
            @error('description')
                <p class="text-red-500 text-xs mt-3 block">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-2">
            <label>Title task
                <input wire:model="title"
                    class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    type="string" name="title" value="" required>
                @error('title')
                    <p class="text-red-500 text-xs mt-3 ">{{ $message }}</p>
                @enderror
            </label>
            <label>Due Date
                <input wire:model="due_date"
                    class="mb-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                    type="date" name="due_date" required>
                @error('due_date')
                    <p class="text-red-500 text-xs mt-3 block">{{ $message }}</p>
                @enderror
            </label>
            @role('productManager')
            <div class="md:col-span-5 mb-3">
                <label class="sr-only">Add Collaborator</label>
                <input type="text" name="search" id="search" wire:model="search"
                    class="h-10 border mt-1 rounded  w-full bg-gray-50" placeholder="Collaborator..." value="" />
                <x-general-button class="mt-2" wire:click="searchDB">
                    {{ __('Add Collaborator') }}
                </x-general-button>
                @if (session()->has('userNotFound'))
                    <div class="bg-red-600 mt-3 text-white p-4 text-center" id="userNotFoundMessage">
                        <h3>{{ session()->get('userNotFound') }}</h3>
                    </div>
                @endif

            </div>
            @endrole
        </div>
        <div class="grid grid-cols-1 my-3">
            <label class="mb-2 block">Task complete
                <input type="checkbox" class="appearance-none checked:bg-blue-500 rounded" wire:model='completed'>
            </label>
        </div>
        <div class="flex">
            <h3 class="text-xl font-semibold">Teammates:</h3>
            @if (count($badges) > 0)
                <div class="mt-2 ml-2">
                    @foreach ($badges as $user)
                        <span wire:click='deletedTeammate({{ $user[0]->id }})'
                            class="cursor-pointer hover:bg-red-400 hover:text-white inline-block items-center rounded-md bg-gray-50 px-2 py-1 mt-3 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                            {{ $user[0]->name }}
                        </span>
                    @endforeach

                </div>
            @endif
        </div>


        <x-general-button wire:click="updateTask(({{ $task }}))"
            class="mt-4">{{ __('Save') }}</x-general-button>
        <x-general-button class="mt-4 hover:bg-red-400" wire:click.prevent="cancel">Cancel</x-general-button>
    </div>
</div>
