<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';
import Task from '@/Components/Task/Task.vue';
import Create from '@/Components/Task/Create.vue';
import { router } from '@inertiajs/vue3'
import DangerButton from '@/Components/DangerButton.vue';
import { Link } from '@inertiajs/vue3'

const props = defineProps(['task', 'parent_url']);

const form = useForm({
    title: props.task.title,
    body: props.task.body,
    status: props.task.status,
});

const formImages = useForm({
    images: [],
});

function attachImages() {

  router.post(`/tasks/${props.task.id}/attach`, {
    _method: 'patch',
    images: formImages.images,
    }, { preserveState: false } )
}
</script>

<template>
    <Head title="Task" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" v-if="!props.task.parent_id">
            Task</h2>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight"  v-else>
            Sub-Task</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form class="space-y-6" @submit.prevent="form.patch(route('tasks.update', props.task.id))">
                        <div v-if="parent_url">
                            <InputLabel for="parent" value="Parent task" />
                            <Link class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out" :href="`/tasks/${props.task.parent_id}`">{{parent_url}}</Link>
                        </div>
                        <div>
                            <InputLabel for="title" value="Title" />
                            <TextInput
                                id="title"
                                type="text"
                                class="mt-1 block w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                v-model="form.title"
                                required
                                autofocus
                                autocomplete="title"
                                placeholder="Type your task here..."
                            />
                            <InputError :message="form.errors.title" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel for="status" value="Status" />
                            <select required v-model="form.status" class="mt-1 block w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" id="status">
                                <option value="Todo">Todo</option>
                                <option value="In Progress">In Progress</option>
                                <option value="Completed">Completed</option>
                            </select>

                            <InputError :message="form.errors.status" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel for="body" value="Body" />
                            <textarea id="body" v-model="form.body" class="mt-1 block w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                            <InputError :message="form.errors.body" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4" v-if="props.task.user.id === $page.props.auth.user.id">
                            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                            </Transition>

                            <Link v-if="!props.task.parent_id" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150" :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing" href="route('tasks.destroy', task.id)" method="delete" as="button" type="button">Delete</Link>

                        </div>

                    </form>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <p>Images: </p>

                    <form v-if="props.task.user.id === $page.props.auth.user.id" @submit.prevent="attachImages" enctype="multipart/form-data">
                        <input type="file" @input="formImages.images = $event.target.files" multiple accept="image/jpg, image/jpeg, image/png" />
                        <progress v-if="formImages.progress" :value="formImages.progress.percentage" max="100">
                        {{ formImages.progress.percentage }}%
                        </progress>
                        <PrimaryButton class="mt-4">Attach</PrimaryButton>
                    </form>

                    <table class="mt-5 w-2/4 bg-white text-sm shadow-sm" v-if="props.task.images">
                    <thead>
                        <tr>
                        <th class="w-1/2 font-semibold  text-left">Name</th>
                        <th class="w-1/2 font-semibold  text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="image in props.task.images.split('|')">
                        <td>
                            <a target="_blank" :href="`/images/${ image }`" class="text-blue-600 visited:text-purple-600">
                            {{ image }}
                            </a>
                        </td>
                        </tr>
                    </tbody>
                    </table>

                    <p v-else>Empty!</p>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg" v-if="!props.task.parent_id">
                    <p class="">Sub-tasks: </p>

                    <Create v-if="props.task.user.id === $page.props.auth.user.id" :task="task" class="my-5" />

                    <Task v-if="props.task.sub_tasks.length > 0"
                        v-for="subTask in props.task.sub_tasks"
                        :key="subTask.id"
                        :task="subTask"
                    />
                    <p v-else>Empty!</p>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
