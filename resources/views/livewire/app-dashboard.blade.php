<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-button class="mb-4" wire:click='openModalCreate'>
            {{ __('Create Project') }}
        </x-button>
        @if (session()->has('success'))
            <div class="bg-sky-600 text-white p-4 text-center" id="flashMessage">
                <h3>{{ session()->get('success') }}</h3>
            </div>
        @endif

        {{-- MODAL CREATE --}}
        <x-modal wire:model='openCreate'>
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
                                    <h3 class="text-xl font-semibold mt-3">Teammates:</h3>
                                    @if (count($badges) > 0)
                                        <div class="mt-2">
                                            @foreach ($badges as $user)
                                                <span wire:click='deletedTeammate({{ $user[0]->id }})'
                                                    class="cursor-pointer hover:bg-red-400 hover:text-white inline-block items-center rounded-md bg-gray-50 px-2 py-1 mt-3 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                                    {{ $user[0]->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
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

                                        <div class="md:col-span-5 mb-3">
                                            <label for="search" class="sr-only">Add Collaborator</label>
                                            <input type="text" name="search" id="search" wire:model="search"
                                                class="h-10 border mt-1 rounded  w-full bg-gray-50"
                                                placeholder="Collaborator..." value="" />
                                            <x-button class="mt-2" wire:click="searchDB">
                                                {{ __('Add Collaborator') }}
                                            </x-button>
                                            @if (session()->has('userNotFound'))
                                                <div class="bg-red-600 mt-3 text-white p-4 text-center"
                                                    id="userNotFoundMessage">
                                                    <h3>{{ session()->get('userNotFound') }}</h3>
                                                </div>
                                            @endif

                                        </div>

                                        <div class="md:col-span-5 text-right mt-6">
                                            <div class="inline-flex items-end">
                                                <x-button wire:click="createProject">
                                                    {{ __('Create Project') }}
                                                </x-button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>



                        </div>
                        <x-button wire:click="closeModalCreate" wire:loading.attr="disabled">
                            {{ __('Close') }}
                        </x-button>
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
            <!-- component -->
            <div class="min-h-screen p-6 flex items-center justify-center">
                <div class="container max-w-screen-lg mx-auto">
                    <div>
                        <h2 class="font-semibold text-xl text-gray-600">Edit Project</h2>

                        <div class="bg-white rounded shadow-lg p-4 px-4 md:p-8 mb-6">
                            <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3">
                                <div class="text-gray-600">
                                    <p class="font-medium text-lg">Project Details</p>
                                    <h3 class="text-xl font-semibold mt-3">Teammates:</h3>
                                    @if (count($badges) > 0)
                                        <div class="mt-2">
                                            @foreach ($badges as $user)
                                                <span wire:click='deletedTeammate({{ $user[0]->id }})'
                                                    class="cursor-pointer hover:bg-red-400 hover:text-white inline-block items-center rounded-md bg-gray-50 px-2 py-1 mt-3 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">
                                                    {{ $user[0]->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

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

                                        <div class="md:col-span-5 mb-3">
                                            <label for="search" class="sr-only">Add Collaborator</label>
                                            <input type="text" name="search" id="search" wire:model="search"
                                                class="h-10 border mt-1 rounded  w-full bg-gray-50"
                                                placeholder="Collaborator..." value="" />
                                            <x-button class="mt-2" wire:click="searchDB">
                                                {{ __('Add Collaborator') }}
                                            </x-button>
                                            @if (session()->has('userNotFound'))
                                                <div class="bg-red-600 mt-3 text-white p-4 text-center"
                                                    id="userNotFoundMessage">
                                                    <h3>{{ session()->get('userNotFound') }}</h3>
                                                </div>
                                            @endif

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
                        <x-button wire:click="closeModalEdit" wire:loading.attr="disabled">
                            {{ __('Close') }}
                        </x-button>
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
        {{-- <x-modal wire:model='openTasks'>
            <!-- component -->
            <div class="min-h-screen p-6 flex items-center justify-center">
                <div class="container max-w-screen-lg mx-auto">
                    <div>
                        <h2 class="font-semibold text-xl text-gray-600">Tasks</h2>

                        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                            @if ($this->showGIF === true)
                                <img src="{{ asset('img/gifs/loading-icon-animated-gif-19.jpg') }}" class="img-fluid w-36" alt="loader">
                            @else
                                @foreach ($chirps as $chirp)
                                    <div class="p-6 flex space-x-2" wire:key="{{ $chirp->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    @if ($chirp->user->email === auth()->user()->email)
                                                        <span class="text-gray-800">Me</span>
                                                    @else
                                                        <span class="text-gray-800">{{ $chirp->user->name }} - {{ $chirp->user->email }}</span>
                                                    @endif
                                                    <small
                                                        class="ml-2 text-sm text-gray-600">{{ $chirp->created_at->format('j M Y, g:i a') }}</small>
                                                    @unless ($chirp->created_at->eq($chirp->updated_at))
                                                        <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                                                    @endunless
                                                </div>
                                                @if ($chirp->user->is(auth()->user()))
                                                    <x-dropdown>
                                                        <x-slot name="trigger">
                                                            <button>
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400"
                                                                    viewBox="0 0 20 20" fill="currentColor">
                                                                    <path
                                                                        d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                                                </svg>
                                                            </button>
                                                        </x-slot>
                                                        <x-slot name="content">
                                                            <x-dropdown-link wire:click="edit({{ $chirp->id }})">
                                                                {{ __('Edit') }}
                                                            </x-dropdown-link>
                                                            <x-dropdown-link wire:click="delete({{ $chirp->id }})">
                                                                {{ __('Delete') }}
                                                            </x-dropdown-link>
                                                        </x-slot>
                                                    </x-dropdown>
                                                @else
                                                    @php
                                                        $following = false;
                                                        foreach ($chirp->user->following as $item) {
                                                            if ($item->id === auth()->user()->id) {
                                                                $following = true;
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    @if ($following)
                                                        <div class="cursor-pointer" wire:click="unfollow({{ $chirp }})">
                                                            Unfollow
                                                        </div>
                                                    @else
                                                        <div class="cursor-pointer" wire:click="follow({{ $chirp }})">
                                                            Follow
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                            @if ($chirp->is($editing))
                                                <livewire:chirps.edit :chirp="$chirp" :key="$chirp->id" />
                                            @else
                                                <p class="mt-4 text-lg text-gray-900">{{ $chirp->message }}</p>
                                            @endif
                        
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        
                        </div>
                        <x-button wire:click="closeModalEdit" wire:loading.attr="disabled">
                            {{ __('Close') }}
                        </x-button>
                    </div>

                    <a class="md:absolute bottom-0 right-0 p-4 float-right">
                        <img src="https://www.buymeacoffee.com/assets/img/guidelines/logo-mark-3.svg"
                            alt="Buy Me A Coffee"
                            class="transition-all rounded-full w-14 -rotate-45 hover:shadow-sm shadow-lg ring hover:ring-4 ring-white">
                    </a>
                </div>
            </div>
        </x-modal> --}}

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg pt-4">
            <div class="flex justify-center">
                <div class="flex flex-col max-w-6xl min-w-6xl md:grid grid-cols-4 justify-center items-center m-2">
                    @forelse ($projects as $project)
                        <div
                            class="py-4 bg-white m-4 shadow-xl h-72 min-h-72 w-72 min-w-72 flex flex-col justify-center text-black hover:text-white hover:bg-stone-700 hover:scale-105 ">
                            <div class="m-8 h-32">
                                <div class="m-2">
                                    <h1 class="text-xl ">
                                        {{ $project->title }}
                                    </h1>
                                </div>

                                <x-button wire:click='openModalEdit({{ $project->id }})'>
                                    {{ __('Details') }}
                                </x-button>
                                {{-- <x-button class="ml-4" wire:click='openModalTasks({{ $project->id }})'>
                                    {{ __('Add Tasks') }}
                                </x-button> --}}
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
