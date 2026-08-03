<div
    x-show="loaded"
    x-init="if (sessionStorage.getItem('app_loaded')) {
        loaded = false;
    } else {
        sessionStorage.setItem('app_loaded', '1');
        setTimeout(() => loaded = false, 500);
    }"
    class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black"
>
    <div
        class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent"
    ></div>
</div>
