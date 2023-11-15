<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @role('admin')
            <x-button class="mb-4" wire:click='openModalCreate'>
                {{ __('Create Project') }}
            </x-button>
            @if (session()->has('success'))
                <div class="bg-sky-600 text-white p-4 text-center" id="flashMessage">
                    <h3>{{ session()->get('success') }}</h3>
                </div>
            @endif
        @endrole

        {{-- MODAL CREATE --}}
        <x-modal wire:model='openCreate'>
            <x-button wire:click="closeModalCreate" wire:loading.attr="disabled" class="absolute top-10 right-10">
                {{ __('X') }}
            </x-button>
            <!-- component -->
            <div class="min-h-screen p-6 flex items-center justify-center">
                <div class="container max-w-screen-lg mx-auto">
                    <div>
                        <h2 class="font-semibold text-xl text-gray-600">New Project</h2>

                        <div class="bg-white rounded shadow-lg p-4 px-4 md:p-8 mb-6">
                            <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3">
                                <div class="text-gray-600" style="max-height: 300px; overflow-y: auto;">
                                    <p class="font-medium text-lg">Project Details</p>
                                    <p>Please fill out all the fields.</p>

                                </div>


                                <div class="lg:col-span-2">
                                    <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 md:grid-cols-5">
                                        <div class="md:col-span-5">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" wire:model='title'
                                                class="h-10 border mt-1 rounded px-4 w-full bg-gray-50"
                                                value="" />
                                            @error('title')
                                                <p class="text-red-700 font-bold py-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="md:col-span-5">
                                            <label for="description">Description</label>
                                            <textarea name="" id="" cols="10" rows="3" placeholder="Describe project here."
                                                class="border p-2 mt-3 w-full" id="description" wire:model='description'>
                                                
                                            </textarea>
                                            @error('description')
                                                <p class="text-red-700 font-bold py-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="md:col-span-5 text-right mt-6">
                                            <div class="inline-flex items-end">
                                                <x-general-button wire:click="createProject">
                                                    {{ __('Create Project') }}
                                                </x-general-button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
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

        {{-- MODAL EDIT --}}
        <x-modal wire:model='openEdit'>
            <x-button wire:click="closeModalEdit" wire:loading.attr="disabled" class="absolute top-10 right-10">
                {{ __('X') }}
            </x-button>
            <!-- component -->
            <div class="min-h-screen p-6 flex items-center justify-center">
                <div class="container max-w-screen-lg mx-auto">
                    <div>
                        <h2 class="font-semibold text-xl text-gray-600">Edit Project</h2>

                        <div class="bg-white rounded shadow-lg p-4 px-4 md:p-8 mb-6">
                            <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3">
                                <div class="text-gray-600">
                                    <p class="font-medium text-lg">Project Details</p>


                                </div>

                                <div class="lg:col-span-2">
                                    <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 md:grid-cols-5">
                                        <div class="md:col-span-5">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" wire:model='title'
                                                class="h-10 border mt-1 rounded px-4 w-full bg-gray-50"
                                                value="{{ $selectedProject ? $selectedProject->title : '' }}" />
                                            @error('title')
                                                <p class="text-red-700 font-bold py-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="md:col-span-5">
                                            <label for="description">Description</label>
                                            <textarea name="" id="" cols="10" rows="3" placeholder="Describe project here."
                                                class="border p-2 mt-3 w-full" id="description" wire:model='description'>
                                                {{ $selectedProject ? $selectedProject->description : '' }}
                                            </textarea>
                                            @error('description')
                                                <p class="text-red-700 font-bold py-2">{{ $message }}</p>
                                            @enderror
                                        </div>



                                        <div class="md:col-span-5 text-right">
                                            <div class="inline-flex items-end">
                                                <x-button wire:click="editProject">
                                                    {{ __('Edit Project') }}
                                                </x-button>
                                                <x-button class="ml-4 bg-red-400" wire:click="deletingProject">
                                                    {{ __('Delete') }}
                                                </x-button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
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

        {{-- MODAL TASKS --}}
        <livewire:tasks-section>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg pt-4">
                <div class="flex justify-center">
                    <div class="flex flex-col max-w-6xl min-w-6xl md:grid grid-cols-4 justify-center items-center m-2">
                        @forelse ($projects as $project)
                            <div wire:key='{{ $project->id }}'
                                class="py-4 bg-white m-4 shadow-xl h-72 min-h-72 w-72 min-w-72 flex flex-col justify-center text-black hover:text-white hover:bg-stone-700 hover:scale-105 ">
                                <div class="m-8 h-32">
                                    <div class="m-2">
                                        <h1 class="text-xl ">
                                            {{ $project->title }}
                                        </h1>
                                    </div>

                                    @role('admin')
                                        <x-button wire:click='openModalEdit({{ $project->id }})'>
                                            {{ __('Details') }}
                                        </x-button>
                                    @endrole

                                    @role(['productManager','admin','collaborator'])
                                        <x-general-button class="ml-4" wire:click='openModalTasks({{ $project->id }})'>
                                            {{ __('Tasks') }}
                                        </x-general-button>
                                    @endrole
                                </div>
                            </div>
                        @empty
                            <p class="text-zinc-900 p-4 text-center text-xl">There's no projects yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var flashMessage = document.getElementById('flashMessage');
                if (flashMessage) flashMessage.style.display = 'none';
            }, 5000); // <-- tiempo en milisegundos
            setTimeout(function() {
                var userNotFoundMessage = document.getElementById('userNotFoundMessage');
                if (userNotFoundMessage) userNotFoundMessage.style.display = 'none';
            }, 5000);

        });
    </script>
</div>
