<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard } from '@/routes';

defineProps<{
    stats: {
        totalBarang: number;
        totalKategori: number;
        stokRendah: number;
        stokHabis: number;
    };
    barangStokRendah: Array<{
        id: number;
        nama_barang: string;
        stok: number;
        kategori?: { nama_kategori: string };
    }>;
    barangTerbaru: Array<{
        id: number;
        nama_barang: string;
        stok: number;
        kategori?: { nama_kategori: string };
    }>;
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});

const getStokColor = (stok: number) => {
    if (stok === 0) return 'text-red-400 bg-red-400/10 border-red-400/20';
    if (stok <= 10) return 'text-rose-400 bg-rose-400/10 border-rose-400/20';
    if (stok <= 30) return 'text-amber-400 bg-amber-400/10 border-amber-400/20';
    return 'text-emerald-400 bg-emerald-400/10 border-emerald-400/20';
};

const getStokLabel = (stok: number) => {
    if (stok === 0) return 'Habis';
    if (stok <= 10) return 'Kritis';
    if (stok <= 30) return 'Rendah';
    return 'Aman';
};
</script>

<template>
    <Head title="Dashboard" />

    <div class="min-h-screen p-6 lg:p-8">

        <!-- Page Header -->
        <div class="mb-8">
            <p class="text-xs font-semibold uppercase tracking-widest text-violet-400 mb-1">Selamat Datang</p>
            <h1 class="text-3xl font-bold text-white">Dashboard</h1>
            <p class="text-sm text-slate-400 mt-1">Ringkasan data inventaris dan sistem</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

            <!-- Total Barang -->
            <div class="relative group flex flex-col rounded-2xl border border-slate-700/60 bg-slate-800/50 backdrop-blur-sm p-5 overflow-hidden hover:border-violet-500/50 hover:shadow-lg hover:shadow-violet-500/10 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-violet-600/5 to-fuchsia-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="h-0.5 absolute top-0 left-0 right-0 bg-gradient-to-r from-violet-500 to-fuchsia-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="p-2.5 rounded-xl bg-violet-500/10 border border-violet-500/20">
                        <svg class="w-5 h-5 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total</span>
                </div>
                <div class="relative z-10">
                    <p class="text-3xl font-bold text-white">{{ stats.totalBarang }}</p>
                    <p class="text-sm text-slate-400 mt-0.5">Barang Terdaftar</p>
                </div>
            </div>

            <!-- Total Kategori -->
            <div class="relative group flex flex-col rounded-2xl border border-slate-700/60 bg-slate-800/50 backdrop-blur-sm p-5 overflow-hidden hover:border-blue-500/50 hover:shadow-lg hover:shadow-blue-500/10 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/5 to-cyan-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="h-0.5 absolute top-0 left-0 right-0 bg-gradient-to-r from-blue-500 to-cyan-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="p-2.5 rounded-xl bg-blue-500/10 border border-blue-500/20">
                        <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wide">Total</span>
                </div>
                <div class="relative z-10">
                    <p class="text-3xl font-bold text-white">{{ stats.totalKategori }}</p>
                    <p class="text-sm text-slate-400 mt-0.5">Kategori</p>
                </div>
            </div>

            <!-- Stok Rendah -->
            <div class="relative group flex flex-col rounded-2xl border border-slate-700/60 bg-slate-800/50 backdrop-blur-sm p-5 overflow-hidden hover:border-amber-500/50 hover:shadow-lg hover:shadow-amber-500/10 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-600/5 to-orange-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="h-0.5 absolute top-0 left-0 right-0 bg-gradient-to-r from-amber-500 to-orange-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/20">
                        <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-amber-500/70 uppercase tracking-wide">Peringatan</span>
                </div>
                <div class="relative z-10">
                    <p class="text-3xl font-bold text-white">{{ stats.stokRendah }}</p>
                    <p class="text-sm text-slate-400 mt-0.5">Stok Kritis (≤10)</p>
                </div>
            </div>

            <!-- Stok Habis -->
            <div class="relative group flex flex-col rounded-2xl border border-slate-700/60 bg-slate-800/50 backdrop-blur-sm p-5 overflow-hidden hover:border-red-500/50 hover:shadow-lg hover:shadow-red-500/10 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-red-600/5 to-rose-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="h-0.5 absolute top-0 left-0 right-0 bg-gradient-to-r from-red-500 to-rose-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="flex items-center justify-between mb-4 relative z-10">
                    <div class="p-2.5 rounded-xl bg-red-500/10 border border-red-500/20">
                        <svg class="w-5 h-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-red-500/70 uppercase tracking-wide">Darurat</span>
                </div>
                <div class="relative z-10">
                    <p class="text-3xl font-bold text-white">{{ stats.stokHabis }}</p>
                    <p class="text-sm text-slate-400 mt-0.5">Stok Habis</p>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Two Panels -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            <!-- Barang Stok Kritis -->
            <div class="rounded-2xl border border-slate-700/60 bg-slate-800/50 backdrop-blur-sm overflow-hidden">
                <!-- Panel Header -->
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700/60">
                    <div class="flex items-center gap-2.5">
                        <div class="p-1.5 rounded-lg bg-amber-500/10">
                            <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h2 class="text-sm font-semibold text-white">Stok Hampir Habis</h2>
                    </div>
                    <Link href="/barang" class="text-xs text-violet-400 hover:text-violet-300 font-medium transition-colors">
                        Lihat Semua →
                    </Link>
                </div>

                <!-- Panel Body -->
                <div class="divide-y divide-slate-700/50">
                    <div v-if="barangStokRendah.length === 0" class="flex flex-col items-center justify-center py-12 text-center px-5">
                        <div class="p-3 rounded-xl bg-slate-700/50 mb-3">
                            <svg class="w-7 h-7 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-slate-300">Semua stok aman</p>
                        <p class="text-xs text-slate-500 mt-0.5">Tidak ada barang dengan stok kritis</p>
                    </div>

                    <div
                        v-for="item in barangStokRendah"
                        :key="item.id"
                        class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-700/30 transition-colors duration-150"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-slate-700 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-white capitalize truncate">{{ item.nama_barang }}</p>
                                <p class="text-xs text-slate-500">{{ item.kategori?.nama_kategori ?? 'Tanpa Kategori' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span :class="['inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold border', getStokColor(item.stok)]">
                                {{ item.stok }} unit
                            </span>
                            <span :class="['inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium border', getStokColor(item.stok)]">
                                {{ getStokLabel(item.stok) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barang Terbaru Ditambahkan -->
            <div class="rounded-2xl border border-slate-700/60 bg-slate-800/50 backdrop-blur-sm overflow-hidden">
                <!-- Panel Header -->
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-700/60">
                    <div class="flex items-center gap-2.5">
                        <div class="p-1.5 rounded-lg bg-violet-500/10">
                            <svg class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h2 class="text-sm font-semibold text-white">Barang Terbaru</h2>
                    </div>
                    <Link href="/barang/create" class="text-xs text-violet-400 hover:text-violet-300 font-medium transition-colors">
                        + Tambah →
                    </Link>
                </div>

                <!-- Panel Body -->
                <div class="divide-y divide-slate-700/50">
                    <div v-if="barangTerbaru.length === 0" class="flex flex-col items-center justify-center py-12 text-center px-5">
                        <div class="p-3 rounded-xl bg-slate-700/50 mb-3">
                            <svg class="w-7 h-7 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-slate-300">Belum ada barang</p>
                        <p class="text-xs text-slate-500 mt-0.5">Mulai tambahkan barang pertama Anda</p>
                    </div>

                    <div
                        v-for="(item, index) in barangTerbaru"
                        :key="item.id"
                        class="flex items-center justify-between px-5 py-3.5 hover:bg-slate-700/30 transition-colors duration-150"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Rank badge -->
                            <div class="w-8 h-8 rounded-lg bg-violet-500/10 border border-violet-500/20 flex items-center justify-center flex-shrink-0">
                                <span class="text-xs font-bold text-violet-400">#{{ index + 1 }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-white capitalize truncate">{{ item.nama_barang }}</p>
                                <p class="text-xs text-slate-500">{{ item.kategori?.nama_kategori ?? 'Tanpa Kategori' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span :class="['inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold border', getStokColor(item.stok)]">
                                {{ item.stok }} unit
                            </span>
                            <Link
                                :href="`/barang/${item.id}/edit`"
                                class="inline-flex items-center text-xs font-medium text-slate-500 hover:text-violet-300 transition-colors p-1 rounded"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Quick Actions -->
        <div class="mt-5 rounded-2xl border border-slate-700/60 bg-slate-800/50 backdrop-blur-sm p-5">
            <h2 class="text-sm font-semibold text-slate-300 mb-4">Aksi Cepat</h2>
            <div class="flex flex-wrap gap-3">
                <Link
                    href="/barang"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-700/60 hover:bg-slate-700 border border-slate-600/60 hover:border-violet-500/50 text-sm text-slate-300 hover:text-white font-medium transition-all duration-200 hover:-translate-y-0.5"
                >
                    <svg class="w-4 h-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Lihat Semua Barang
                </Link>
                <Link
                    href="/barang/create"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold shadow-lg shadow-violet-500/20 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-violet-500/30"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Barang
                </Link>
                <Link
                    href="/kategori"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-700/60 hover:bg-slate-700 border border-slate-600/60 hover:border-blue-500/50 text-sm text-slate-300 hover:text-white font-medium transition-all duration-200 hover:-translate-y-0.5"
                >
                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    Kelola Kategori
                </Link>
            </div>
        </div>

    </div>
</template>
