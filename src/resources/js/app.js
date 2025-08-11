import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import { createI18n } from 'vue-i18n';
import messages from './i18n';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {

        // 初期ロケールを決定: グローバル変数 > localStorage > サーバーから共有されたプロパティ
        // const initialLocale = window.__locale || localStorage.getItem('locale') || 'ja';
        const initialLocale = props.initialPage.props.locale || 'en';

        // Vue I18n インスタンスの作成
        const i18n = createI18n({
        legacy: false, // Vue 3 Composition API スタイルを使用
        locale: initialLocale, // 現在のロケールを設定
        // fallbackLocale: window.__fallback_locale || 'ja', // フォールバックロケール
        fallbackLocale: props.initialPage.props.fallback_locale || 'en', // フォールバックロケールを設定
        globalInjection: true, // コンポーネント内で $t などのグローバルプロパティを使用可能にする
        messages // 翻訳メッセージを登録
        });

        return createApp({ render: () => h(App, props) })
            .use(plugin) // Inertia プラグイン
            .use(i18n) // Vue I18n プラグイン
            .use(ZiggyVue) // Ziggy プラグイン
            .mount(el); // アプリケーションをマウント
    },
    progress: {
        color: '#4B5563',
    },
});
