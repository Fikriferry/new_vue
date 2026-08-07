<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
    items: Array,
});

// ─── Helpers: URL params + localStorage ──────────────────────────────────────
const STORAGE_KEY = 'barang-filters';

/** Baca nilai dari URL param, fallback ke localStorage, lalu default */
const readParam = (key, defaultVal) => {
    const urlVal = new URLSearchParams(window.location.search).get(key);
    if (urlVal !== null) return urlVal;
    try {
        const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) ?? '{}');
        return saved[key] ?? defaultVal;
    } catch {
        return defaultVal;
    }
};

/** Tulis state saat ini ke URL (tanpa server request) & localStorage */
const persistFilters = (search, filter, sort) => {
    // 1. URL query params
    const params = new URLSearchParams();
    if (search)          params.set('search', search);
    if (filter !== 'semua')  params.set('filter', filter);
    if (sort   !== 'terbaru') params.set('sort', sort);
    const qs = params.toString();
    history.replaceState(null, '', qs ? `${window.location.pathname}?${qs}` : window.location.pathname);

    // 2. localStorage (backup saat Inertia navigation reset URL)
    localStorage.setItem(STORAGE_KEY, JSON.stringify({ search, filter, sort }));
};

// ─── State (init dari URL / localStorage) ────────────────────────────────────
const searchQuery  = ref(readParam('search', ''));
const filterStatus = ref(readParam('filter', 'semua'));
const sortBy       = ref(readParam('sort',   'terbaru'));
const sortOpen     = ref(false);

// Simpan setiap kali ada perubahan
watch([searchQuery, filterStatus, sortBy], ([s, f, so]) => {
    persistFilters(s, f, so);
});

// Saat halaman dimount via Inertia (URL sudah di-reset Inertia), baca dari localStorage
onMounted(() => {
    const urlHasParams = new URLSearchParams(window.location.search).toString() !== '';
    if (!urlHasParams) {
        try {
            const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) ?? '{}');
            if (saved.search) searchQuery.value  = saved.search;
            if (saved.filter) filterStatus.value = saved.filter;
            if (saved.sort)   sortBy.value       = saved.sort;
        } catch { /* ignore */ }
    }
});

// ─── Sort options ─────────────────────────────────────────────────────────────
const sortOptions = [
    { value: 'terbaru',   label: 'Terbaru Ditambahkan' },
    { value: 'terlama',   label: 'Terlama Ditambahkan' },
    { value: 'stok_desc', label: 'Stok: Tertinggi' },
    { value: 'stok_asc',  label: 'Stok: Terendah' },
    { value: 'az',        label: 'Nama: A → Z' },
    { value: 'za',        label: 'Nama: Z → A' },
];

const sortLabel = computed(() => sortOptions.find(o => o.value === sortBy.value)?.label ?? '');

// ─── Filter tabs ─────────────────────────────────────────────────────────────
const filterTabs = [
    { value: 'semua',  label: 'Semua' },
    { value: 'habis',  label: 'Stok Habis' },
    { value: 'kritis', label: 'Kritis' },
    { value: 'rendah', label: 'Rendah' },
    { value: 'aman',   label: 'Aman' },
];

// ─── Computed filtered + sorted list ─────────────────────────────────────────
const filteredItems = computed(() => {
    let list = [...props.items];

    // Search
    if (searchQuery.value.trim()) {
        const q = searchQuery.value.toLowerCase();
        list = list.filter(i =>
            i.nama_barang.toLowerCase().includes(q) ||
            (i.kategori?.nama_kategori ?? '').toLowerCase().includes(q)
        );
    }

    // Status filter
    if (filterStatus.value !== 'semua') {
        list = list.filter(i => {
            if (filterStatus.value === 'habis')  return i.stok === 0;
            if (filterStatus.value === 'kritis') return i.stok > 0  && i.stok <= 10;
            if (filterStatus.value === 'rendah') return i.stok > 10 && i.stok <= 30;
            if (filterStatus.value === 'aman')   return i.stok > 30;
        });
    }

    // Sort
    list.sort((a, b) => {
        if (sortBy.value === 'terbaru')   return b.id - a.id;
        if (sortBy.value === 'terlama')   return a.id - b.id;
        if (sortBy.value === 'stok_desc') return b.stok - a.stok;
        if (sortBy.value === 'stok_asc')  return a.stok - b.stok;
        if (sortBy.value === 'az')        return a.nama_barang.localeCompare(b.nama_barang);
        if (sortBy.value === 'za')        return b.nama_barang.localeCompare(a.nama_barang);
        return 0;
    });

    return list;
});

// ─── Count per status (for badges) ───────────────────────────────────────────
const countByStatus = computed(() => ({
    semua:  props.items.length,
    habis:  props.items.filter(i => i.stok === 0).length,
    kritis: props.items.filter(i => i.stok > 0  && i.stok <= 10).length,
    rendah: props.items.filter(i => i.stok > 10 && i.stok <= 30).length,
    aman:   props.items.filter(i => i.stok > 30).length,
}));

// ─── Helpers ─────────────────────────────────────────────────────────────────
const hapusBarang = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
        router.delete(`/barang/${id}`, { preserveScroll: true });
    }
};

const getStokColor = (stok) => {
    if (stok === 0)   return 'text-red-400 bg-red-400/10';
    if (stok <= 10)   return 'text-rose-400 bg-rose-400/10';
    if (stok <= 30)   return 'text-amber-400 bg-amber-400/10';
    return 'text-emerald-400 bg-emerald-400/10';
};

const getStokLabel = (stok) => {
    if (stok === 0)   return 'Habis';
    if (stok <= 10)   return 'Kritis';
    if (stok <= 30)   return 'Rendah';
    return 'Aman';
};

const tabBadgeColor = (val) => {
    const map = {
        semua:  'bg-slate-600 text-slate-300',
        habis:  'bg-red-500/20 text-red-400',
        kritis: 'bg-rose-500/20 text-rose-400',
        rendah: 'bg-amber-500/20 text-amber-400',
        aman:   'bg-emerald-500/20 text-emerald-400',
    };
    return map[val] ?? 'bg-slate-600 text-slate-300';
};
</script>

<template>
    <Head title="Inventaris Barang" />

    <div class="min-h-screen p-6 lg:p-8">

        <!-- ── Page Header ──────────────────────────────────────────────── -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-violet-400 mb-1">Manajemen Inventaris</p>
                    <h1 class="text-3xl font-bold text-white">Daftar Barang</h1>
                    <p class="text-sm text-slate-400 mt-1">
                        Menampilkan <span class="text-white font-semibold">{{ filteredItems.length }}</span>
                        dari {{ items.length }} barang
                    </p>
                </div>
                <Link
                    href="/barang/create"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold shadow-lg shadow-violet-500/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-violet-500/30"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Barang
                </Link>
            </div>
        </div>

        <!-- ── Toolbar: Search + Filter tabs + Sort ─────────────────────── -->
        <div class="mb-6 space-y-3">

            <!-- Row 1: Search + Sort -->
            <div class="flex flex-col sm:flex-row gap-3">
                <!-- Search -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        v-model="searchQuery"
                        type="text"
                        placeholder="Cari nama barang atau kategori..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-800/60 border border-slate-700/60 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-violet-500/70 focus:ring-2 focus:ring-violet-500/20 transition-all duration-200"
                    />
                    <button
                        v-if="searchQuery"
                        @click="searchQuery = ''"
                        class="absolute inset-y-0 right-3 flex items-center text-slate-500 hover:text-white transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Sort Dropdown -->
                <div class="relative">
                    <button
                        @click="sortOpen = !sortOpen"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800/60 border border-slate-700/60 text-sm text-slate-300 hover:border-violet-500/50 hover:text-white transition-all duration-200 min-w-[200px] justify-between"
                    >
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                            </svg>
                            <span>{{ sortLabel }}</span>
                        </div>
                        <svg
                            class="w-4 h-4 text-slate-500 transition-transform duration-200"
                            :class="{ 'rotate-180': sortOpen }"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <Transition
                        enter-active-class="transition duration-150 ease-out"
                        enter-from-class="opacity-0 scale-95 -translate-y-1"
                        enter-to-class="opacity-100 scale-100 translate-y-0"
                        leave-active-class="transition duration-100 ease-in"
                        leave-from-class="opacity-100 scale-100 translate-y-0"
                        leave-to-class="opacity-0 scale-95 -translate-y-1"
                    >
                        <div
                            v-if="sortOpen"
                            class="absolute right-0 top-full mt-2 w-52 rounded-xl bg-slate-800 border border-slate-700/60 shadow-xl shadow-black/40 overflow-hidden z-50"
                        >
                            <button
                                v-for="opt in sortOptions"
                                :key="opt.value"
                                @click="sortBy = opt.value; sortOpen = false"
                                :class="[
                                    'w-full text-left px-4 py-2.5 text-sm transition-colors duration-150 flex items-center gap-2',
                                    sortBy === opt.value
                                        ? 'bg-violet-600/20 text-violet-300'
                                        : 'text-slate-400 hover:bg-slate-700/60 hover:text-white'
                                ]"
                            >
                                <svg
                                    v-if="sortBy === opt.value"
                                    class="w-3.5 h-3.5 text-violet-400 flex-shrink-0"
                                    fill="currentColor" viewBox="0 0 20 20"
                                >
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="w-3.5 flex-shrink-0" v-else></span>
                                {{ opt.label }}
                            </button>
                        </div>
                    </Transition>
                </div>
            </div>

            <!-- Row 2: Filter tabs by stok status -->
            <div class="flex flex-wrap gap-2">
                <button
                    v-for="tab in filterTabs"
                    :key="tab.value"
                    @click="filterStatus = tab.value"
                    :class="[
                        'inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold border transition-all duration-200',
                        filterStatus === tab.value
                            ? 'bg-violet-600 border-violet-500 text-white shadow-md shadow-violet-500/20'
                            : 'bg-slate-800/60 border-slate-700/60 text-slate-400 hover:border-slate-600 hover:text-slate-200'
                    ]"
                >
                    {{ tab.label }}
                    <span :class="['px-1.5 py-0.5 rounded-md text-xs font-bold', tabBadgeColor(tab.value)]">
                        {{ countByStatus[tab.value] }}
                    </span>
                </button>
            </div>
        </div>

        <!-- ── Empty State (no data at all) ─────────────────────────────── -->
        <div v-if="items.length === 0" class="flex flex-col items-center justify-center py-24 rounded-2xl border border-dashed border-slate-700 bg-slate-800/30">
            <div class="p-4 rounded-2xl bg-slate-700/50 mb-4">
                <svg class="h-10 w-10 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-white mb-1">Belum ada barang</h3>
            <p class="text-sm text-slate-400 mb-6">Mulai dengan menambahkan barang pertama Anda</p>
            <Link href="/barang/create" class="px-5 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold transition-all duration-200">
                + Tambah Barang Pertama
            </Link>
        </div>

        <!-- ── Empty State (filter active but no results) ────────────────── -->
        <div
            v-else-if="filteredItems.length === 0"
            class="flex flex-col items-center justify-center py-20 rounded-2xl border border-dashed border-slate-700 bg-slate-800/30"
        >
            <div class="p-4 rounded-2xl bg-slate-700/50 mb-4">
                <svg class="h-10 w-10 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-white mb-1">Tidak ada hasil</h3>
            <p class="text-sm text-slate-400 mb-4">Tidak ada barang yang cocok dengan filter saat ini</p>
            <button
                @click="searchQuery = ''; filterStatus = 'semua'"
                class="px-4 py-2 rounded-xl bg-slate-700 hover:bg-slate-600 text-sm text-slate-300 hover:text-white font-medium transition-colors"
            >
                Reset Filter
            </button>
        </div>

        <!-- ── Grid Cards ─────────────────────────────────────────────────── -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            <div
                v-for="item in filteredItems"
                :key="item.id"
                class="group relative flex flex-col rounded-2xl border border-slate-700/60 bg-slate-800/50 backdrop-blur-sm overflow-hidden hover:border-violet-500/50 hover:shadow-lg hover:shadow-violet-500/10 transition-all duration-300"
            >
                <!-- Card Accent Bar -->
                <div class="h-0.5 w-full bg-gradient-to-r from-violet-500 to-fuchsia-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="p-5 flex-1">
                    <!-- Category Badge + Stock -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-violet-500/15 text-violet-300 border border-violet-500/20">
                            {{ item.kategori?.nama_kategori || 'Tanpa Kategori' }}
                        </span>
                        <div class="flex flex-col items-end gap-0.5">
                            <span :class="['inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold', getStokColor(item.stok)]">
                                {{ item.stok }} unit
                            </span>
                            <span :class="['text-xs font-semibold', getStokColor(item.stok).split(' ')[0]]">
                                {{ getStokLabel(item.stok) }}
                            </span>
                        </div>
                    </div>

                    <!-- Item Name -->
                    <h2 class="text-base font-semibold text-white capitalize leading-snug mb-3">{{ item.nama_barang }}</h2>

                    <!-- Stock Bar -->
                    <div class="mt-auto">
                        <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                            <span>Stok</span>
                            <span>{{ item.stok }} unit</span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-slate-700 overflow-hidden">
                            <div
                                class="h-full rounded-full transition-all duration-500"
                                :class="{
                                    'bg-red-500':     item.stok === 0,
                                    'bg-rose-500':    item.stok > 0 && item.stok <= 10,
                                    'bg-amber-500':   item.stok > 10 && item.stok <= 30,
                                    'bg-emerald-500': item.stok > 30,
                                }"
                                :style="{ width: Math.min(item.stok, 100) + '%' }"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer Actions -->
                <div class="px-5 py-3 border-t border-slate-700/60 flex items-center justify-end gap-3">
                    <Link
                        :href="`/barang/${item.id}/edit`"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400 hover:text-violet-300 transition-colors"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit
                    </Link>
                    <div class="w-px h-3 bg-slate-700"></div>
                    <button
                        @click="hapusBarang(item.id)"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-400 hover:text-rose-400 transition-colors"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Close sort dropdown on outside click -->
    <Teleport to="body">
        <div v-if="sortOpen" class="fixed inset-0 z-40" @click="sortOpen = false"></div>
    </Teleport>
</template>