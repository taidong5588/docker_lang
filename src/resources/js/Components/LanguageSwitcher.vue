<script setup>
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();
const { locale } = useI18n(); // 現在のVue I18nのロケール

// Inertiaから共有されたサポートされているロケールと現在のロケールを取得
const supportedLocales = page.props.supported_locales || { en: 'English', ja: '日本語', ko: '한국어', zh:'中文（简体）'};
const currentLocale = page.props.locale || 'en';

const selectedLocale = ref(currentLocale);

// Inertiaのページプロパティ（locale）が変更されたら、Vue I18nのlocaleも更新
watch(() => page.props.locale, (newLocale) => {
  if (newLocale && newLocale !== locale.value) {
    locale.value = newLocale;
    selectedLocale.value = newLocale; // selectボックスも更新
  }
});

const switchLanguage = async () => {
  const newLocale = selectedLocale.value;

  // 1. Vue I18nのロケールを即座に更新 (UXのため)
  locale.value = newLocale;

  // 2. InertiaのルーターでURLを更新し、Laravel側にロケール変更を通知
  // 現在のURLパスの最初のセグメント（ロケール）を置き換える
  const currentPath = window.location.pathname;
  const pathSegments = currentPath.split('/').filter(s => s !== ''); // 空のセグメントを除外

  if (pathSegments.length > 0 && Object.keys(supportedLocales).includes(pathSegments[0])) {
    // 例: /en/dashboard -> /ja/dashboard
    pathSegments[0] = newLocale;
  } else {
    // 例: /dashboard -> /en/dashboard （ルートにロケールがない場合）
    pathSegments.unshift(newLocale);
  }

  const newPath = '/' + pathSegments.join('/');

  // Inertiaを使って新しいロケール付きのURLに移動
  // preserveState: false で完全にリロードすることで、確実にLaravel側でもロケールが更新される
  router.visit(newPath, {
    method: 'get', // GETリクエストで新しいURLへ
    headers: { 'X-Locale': newLocale }, // Laravelのミドルウェアがこのヘッダーを読み取れるようにする
    preserveState: false,
  });
};
</script>

<template>
  <div class="language-switcher">
    <select v-model="selectedLocale" @change="switchLanguage" class="border rounded px-2 py-1 text-sm bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
      <option v-for="(label, code) in supportedLocales" :key="code" :value="code">
        {{ label }}
      </option>
    </select>
  </div>
</template>

<style scoped>
/* 必要に応じてスタイルを追加 */
.language-switcher {
  display: inline-block;
  margin-left: 1rem;
}
</style>