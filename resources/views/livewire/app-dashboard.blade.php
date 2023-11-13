<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-button class="ms-4" wire:click='openModal'>
            {{ __('Create Project') }}
        </x-button>
        <x-modal wire:model='open'>
            <!-- component -->
            <div class="min-h-screen p-6 flex items-center justify-center">
                <div class="container max-w-screen-lg mx-auto">
                    <div>
                        <h2 class="font-semibold text-xl text-gray-600">New Project</h2>

                        <div class="bg-white rounded shadow-lg p-4 px-4 md:p-8 mb-6">
                            <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3">
                                <div class="text-gray-600">
                                    <p class="font-medium text-lg">Project Details</p>
                                    <p>Please fill out all the fields.</p>
                                </div>

                                <div class="lg:col-span-2">
                                    <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 md:grid-cols-5">
                                        <div class="md:col-span-5">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title"
                                                class="h-10 border mt-1 rounded px-4 w-full bg-gray-50"
                                                value="" />
                                        </div>

                                        <div class="md:col-span-5">
                                            <label for="description">Description</label>
                                            <textarea name="" id="" cols="10" rows="3" placeholder="Describe project here." class="border p-2 mt-3 w-full" id="description">
                                                
                                            </textarea>
                                        </div>



                                        <div class="md:col-span-5 text-right">
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
                        <x-button wire:click="closeModal" wire:loading.attr="disabled">
                            {{ __('Close') }}
                        </x-button>
                    </div>

                    <a href="https://www.buymeacoffee.com/dgauderman" target="_blank"
                        class="md:absolute bottom-0 right-0 p-4 float-right">
                        <img src="https://www.buymeacoffee.com/assets/img/guidelines/logo-mark-3.svg"
                            alt="Buy Me A Coffee"
                            class="transition-all rounded-full w-14 -rotate-45 hover:shadow-sm shadow-lg ring hover:ring-4 ring-white">
                    </a>
                </div>
            </div>
        </x-modal>

        @if (count($projects) >= 0)
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            </div>
        @endif
    </div>
</div>
