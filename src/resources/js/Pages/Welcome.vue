<script setup>
import { Head, Link } from '@inertiajs/vue3';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'; // LanguageSwitcher をインポート
import { useI18n } from 'vue-i18n'; // useI18n をインポート

const { t } = useI18n(); // 翻訳関数 t を取得

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    status: {
        type: String,
    },
});
</script>

<template>
  <Head :title="t('Welcome')" />

  <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
    <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
      <Link
        v-if="$page.props.auth.user"
        :href="route('dashboard')"
        class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:outline-red-500"
      >
        {{ t('Dashboard') }}
      </Link>

      <template v-else>
        <Link
          :href="route('login')"
          class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:outline-red-500"
        >
          {{ t('Login') }}
        </Link>

        <Link
          v-if="canRegister"
          :href="route('register')"
          class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:outline-red-500"
        >
          {{ t('Register') }}
        </Link>
      </template>
      <LanguageSwitcher class="ml-4" />
    </div>

    <div class="max-w-7xl mx-auto p-6 lg:p-8">
      <div class="flex justify-center">
        </div>

      <div class="mt-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
          <div class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
            <div>
              <h2 class="mt-8 text-xl font-semibold text-gray-900 dark:text-white">{{ t('Welcome') }}!</h2>
              <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                Laravel, Jetstream (Inertia), Vue i18n を使った多言語対応のデモです。
              </p>
            </div>
          </div>
          </div>
      </div>
    </div>
  </div>
</template>