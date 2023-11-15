<div>
    <x-modal wire:model='openModalTask' class="relative">
        <x-general-button wire:click="closeModalTasks" wire:loading.attr="disabled" class="m-8">
            {{ __('X') }}
        </x-general-button>
        <!-- component -->
        <div class="min-h-screen p-6 flex items-center justify-center">
            <div class="container max-w-screen-lg mx-auto">
                {{-- Input component --}}

                @role(['productManager','admin'])
                    <livewire:create-task>
                    @endrole

                    <div>
                        <div class="mt-6 bg-gray-200 shadow-sm rounded-lg divide-y"
                            style="height: 300px; overflow: auto;"">
                            @if (session()->has('messageEvent'))
                                <div class="bg-sky-600 text-white p-4 mt-2 text-center" id="messageEvent">
                                    <h3>{{ session()->get('messageEvent') }}</h3>
                                </div>
                            @endif
                            @forelse ($tasks as $task)
                                <div class="p-6 flex space-x-2" wire:key="{{ $task->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <span class="text-gray-800">{{ $task->title }}</span>
                                                <small class="ml-2 text-sm text-gray-600">{{ $task->due_date }}</small>
                                                @foreach ($task->users as $user)
                                                    <small
                                                        class="ml-2 text-sm text-gray-600">{{ $user->name }}</small>
                                                @endforeach
                                                @if ($task->completed === 1)
                                                    <small class="text-md text-green-600">
                                                        {{ __('Complete') }}</small>
                                                @endunless
                                                <p>{{ $task->description }}</p>
                                        </div>
                                        @if ($task->completed === 0 &&
                                                $task->users->contains(auth()->user()->id) || ($task->users->contains(auth()->user()->hasRole('admin')) && $task->completed === 1) || $task->users->contains(auth()->user()->hasRole('admin')))

                                            <x-dropdown dropdown>
                                                <x-slot name="trigger">
                                                    <button>
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4 text-gray-400" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path
                                                                d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                        </svg>
                                                    </button>
                                                </x-slot>
                                                <x-slot name="content">
                                                    @role(['collaborator', 'productManager','admin'])
                                                        <x-dropdown-link wire:model="editTask.{{ $task->id }}"
                                                            wire:click="edit({{ $task }})">
                                                            {{ __('Edit') }}
                                                        </x-dropdown-link>
                                                    @endrole
                                                    @role(['productManager','admin'])
                                                        <x-dropdown-link wire:model="deleteTask.{{ $task->id }}"
                                                            wire:click="delete({{ $task->id }})">
                                                            {{ __('Delete') }}
                                                        </x-dropdown-link>
                                                    @endrole
                                                </x-slot>
                                            </x-dropdown>
                                        @endif

                                    </div>
                                    @if ($editingTaskId === $task->id)
                                        <livewire:task-edit :taskId="$task->id" :key="$task->id" />
                                    @else
                                        <p class="mt-4 text-lg text-gray-900">{{ $task->message }}</p>
                                    @endif


                                </div>
                            </div>
                        @empty

                            <p class="text-zinc-900 p-4 text-center text-xl">There's no tasks on this project
                                yet.</p>
                        @endforelse

                    </div>
                </div>

                <a class="md:absolute bottom-0 right-0 p-4 float-right">
                    <img src="https://www.buymeacoffee.com/assets/img/guidelines/logo-mark-3.svg"
                        alt="Buy Me A Coffee"
                        class="transition-all rounded-full w-14 -rotate-45 hover:shadow-sm shadow-lg ring hover:ring-4 ring-white">
                </a>
        </div>
    </div>
</x-modal>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var messageEvent = document.getElementById('messageEvent');
            if (messageEvent) messageEvent.style.display = 'none';
        }, 5000); // <-- tiempo en milisegundos

    });
</script>
</div>
