<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';
import TaskDeleted from '@/Components/Task/TaskDeleted.vue';
import Create from '@/Components/Task/Create.vue';
import { Link } from '@inertiajs/vue3'
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps(['tasks']);

</script>

<template>
    <Head title="Trashed" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Trashed</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                <div class="bg-white shadow sm:rounded-lg">
                    <div class="divide-y p-4 sm:p-8" v-if="props.tasks.length > 0">
                        <TaskDeleted
                            v-for="task in tasks"
                            :key="task.id"
                            :task="task"
                        />
                    </div>
                    <div class="divide-y p-4 sm:p-8">
                        Empty Bin!
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
