<script setup>

import { useForm } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps(['task']);

const form = useForm({
    title: '',
    parent_id: props.task ? props.task.id : '',
});

function submit() {
  form.post(route('tasks.store'), { onSuccess: () => form.reset() })
}
</script>

<template>
    <form class="space-y-6" @submit.prevent="submit">
        <div>
            <input
                v-if="task"
                type="hidden"
                v-model="form.parent_id"
            />
            <InputLabel for="title" value="Title" />
            <TextInput
                id="title"
                type="text"
                class="mt-1 block w-full"
                v-model="form.title"
                required
                autofocus
                autocomplete="title"
                :placeholder='props.task ? "Type your sub-task here..." : "Type your task here..."'
            />
            <InputError :message="form.errors.title" class="mt-2" />
        </div>
        <PrimaryButton class="mt-4" :disabled="form.processing">Create</PrimaryButton>

    </form>
</template>
