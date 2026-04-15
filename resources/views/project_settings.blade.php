@section('header-title', 'Project settings')
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div x-data="projectForm({{ Js::from([
                        'id' => $project->id ?? null,
                        'name' => $project->name ?? '',
                        'base_url' => $project->base_url ?? '',
                        'description' => $project->description ?? '',
                        'url_list' => $project->url_list ?? '',
                        'user_id' => auth()->id(),
                        'selectors' => $project->selectors ?? []
                    ]) }})"
                     x-init="init"
                     class="p-6 text-gray-900 bg-gray-900">
                    @if(session('error'))
                        <div class="alert alert-danger text-white">
                            {{ session('error') }}
                        </div>
                    @endif
                        @if($errors->any())
                            <div class="alert alert-danger text-white">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
{{--                    <form @submit.prevent="submit" method="POST" action="{{isset($project) ? route('projects.update', $project->id) : route('projects.store')}}">--}}
                    <form @submit.prevent="submit">
                        @csrf
                        @if(isset($project))
                            @method('PUT')  <!-- Только при редактировании -->
                        @endif
                        <div class="space-y-12">
                            <div class="border-b border-white/10 pb-12">
                                <h2 class="text-base/7 font-semibold text-white">Project</h2>
                                <p class="mt-1 text-sm/6 text-gray-400">This information will be displayed project settings.</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-4">
                                        <label for="project_name" class="block text-sm/6 font-medium text-white">Project name</label>
                                        <div class="mt-2">
                                            <input id="project_name" type="text" x-model="form.name" autocomplete="given-name" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                                        </div>

                                    </div>

                                    <div class="sm:col-span-4">
                                        <label for="base_url" class="block text-sm/6 font-medium text-white">Base url</label>
                                        <div class="mt-2">
                                            <input id="base_url" type="text" x-model="form.base_url" autocomplete="given-name" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                                        </div>
                                    </div>

                                    <div class="col-span-full">
                                        <label for="description" class="block text-sm/6 font-medium text-white">Project description</label>
                                        <div class="mt-2">
                                            <textarea id="description" x-model="form.description" rows="3" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-span-full">
                                        <label for="url_list" class="block text-sm/6 font-medium text-white">Url list</label>
                                        <div class="mt-2">
                                            <textarea id="url_list" x-model="form.url_list" rows="3" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"></textarea>
                                        </div>
                                        <p class="mt-3 text-sm/6 text-gray-400">Each link to new string.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border-b border-white/10 pb-12">
                                <template x-for="(selector, index) in form.selectors" :key="index">
                                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                        <div class="sm:col-span-2 sm:col-start-1">
                                            <label for="title" class="block text-sm/6 font-medium text-white">Title</label>
                                            <div class="mt-2">
                                                <input id="title" type="text" name="title" autocomplete="address-level2" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="selector" class="block text-sm/6 font-medium text-white">Selector</label>
                                            <div class="mt-2">
                                                <input id="selector" type="text" x-model="form.selector" autocomplete="address-level1" class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2">
                                            <label for="selector_type" class="block text-sm/6 font-medium text-white">Selector type</label>
                                            <div class="mt-2 grid grid-cols-1">
                                                <select id="selector_type" x-model="form.selector_type" autocomplete="country-name" class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white/5 py-1.5 pr-8 pl-3 text-base text-white outline-1 -outline-offset-1 outline-white/10 *:bg-gray-800 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6">
                                                    <option>text</option>
                                                    <option>multiple</option>
                                                    <option>images</option>
                                                </select>
                                                <svg viewBox="0 0 16 16" fill="currentColor" data-slot="icon" aria-hidden="true" class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-400 sm:size-4">
                                                    <path d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div class="mt-6 flex items-center justify-end gap-x-6">
                                    <button @click="addSelector" type="button" class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Add row</button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <button type="button" class="text-sm/6 font-semibold text-white">Cancel</button>
                            <button type="submit" class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Save</button>
                            <button type="button" @click="startParse"
                                    x-show="form.id"
                                    class="rounded-md bg-indigo-500 px-3 py-2 text-sm font-semibold text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Start parser</button>
                            <button type="button" @click="deleteProject"
                                    x-show="form.id"
                                    class="rounded-md bg-red-500 px-3 py-2 text-sm font-semibold text-white hover:bg-red-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500">
                                Delete project
                            </button>
                        </div>
                        <input type="hidden" x-model="form.user_id">
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>



