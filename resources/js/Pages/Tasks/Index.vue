<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';
import Task from '@/Components/Task/Task.vue';
import Create from '@/Components/Task/Create.vue';
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps(['tasks', 'sortBy', 'filterBy']);

const form = useForm({
    title: '',
});
const filterForm = useForm({
    filterBy: props.filterBy || '',
    sortBy: props.sortBy || '',
});

const sortBy = ref(false);
</script>

<template>
    <Head title="Tasks" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tasks</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <Create />
                </div>

                    <form action="" method="get" id="filter">

                    <div class="flex items-center gap-4">
                        <input name="filterBy" type="text" v-model="filterForm.filterBy" autofocus placeholder="Search here...">

                        <div class="flex gap-2 items-center">
                            <label for="sortBy">Sort by:</label>
                            <select name="sortBy" id="sortBy" v-model="filterForm.sortBy">
                                <option value="desc">Desc</option>
                                <option value="asc">Asc</option>
                            </select>
                        </div>

                        <div class="flex gap-2 items-center">
                            <PrimaryButton :disabled="filterForm.processing">Filter</PrimaryButton>

                            <Link v-if="props.filterBy || props.sortBy" class="inline-block px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out" href="/tasks">Clear</Link>
                        </div>

                        <Link class="ml-auto inline-block px-4 py-2 text-left text-sm leading-5 text-red-700 hover:bg-red-100 focus:bg-red-100 transition duration-150 ease-in-out" href="/tasks/trashed">Trashed &#128465;</Link>
                    </div>

                    </form>

                <div class="bg-white shadow sm:rounded-lg">
                    <div class="divide-y p-4 sm:p-8">
                        <Task
                            v-for="task in tasks"
                            :key="task.id"
                            :task="task"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
