<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import { useI18n } from 'vue-i18n'; // i18n導入
const { t } = useI18n(); // 翻訳関数
</script>

<template>
    <AppLayout title="t('Dashboard')"> 
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('Dashboard') }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <Welcome />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
