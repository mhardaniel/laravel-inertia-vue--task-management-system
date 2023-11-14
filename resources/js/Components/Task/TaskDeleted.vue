<script setup>

import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3'

dayjs.extend(relativeTime);

defineProps(['task']);
</script>

<template>
    <div class="p-6 flex space-x-2">
        <div class="flex-1">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-gray-800"> {{ task.user.name }} </span>
                    <small class="ml-2 text-sm text-gray-600"> {{ dayjs(task.created_at).fromNow() }} </small>
                    <small v-if="task.created_at !== task.updated_at" class="text-sm text-gray-600"> &middot; edited</small>
                </div>
                <Dropdown>
                    <template #trigger>
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </button>
                    </template>
                    <template #content>
                        <DropdownLink as="button" :href="route('tasks.forceDestroy', task.id)" method="delete">
                            Permanently Delete
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
            <div class="mt-4 flex items-center gap-4">
                <p class="text-lg text-gray-900">{{task.title}}</p>

                <span class="px-4 py-2 font-semibold text-sm text-white rounded-full shadow-sm" :class="{ 'bg-orange-500': task.status == 'Todo',
                'bg-lime-500': task.status == 'In Progress',
                'bg-sky-500': task.status == 'Completed' }"> {{task.status}} </span>
            </div>
        </div>
    </div>
</template>
