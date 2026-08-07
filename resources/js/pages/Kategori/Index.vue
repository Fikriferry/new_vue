<script setup>
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    kategoris: Array,
});

const hapusKategori = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
        router.delete(`/kategori/${id}`, { preserveScroll: true });
    }
};
</script>

<template>
    <Head title="Data Kategori" />

    <div class="min-h-screen p-6 lg:p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-400 mb-1">Master Data</p>
                    <h1 class="text-3xl font-bold text-white">Daftar Kategori</h1>
                    <p class="text-sm text-slate-400 mt-1">{{ kategoris.length }} kategori terdaftar dalam sistem</p>
                </div>
                <Link
                    href="/kategori/create"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold shadow-lg shadow-emerald-500/20 transition-all duration-200 hover:-translate-y-0.5"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Kategori
                </Link>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="kategoris.length === 0" class="flex flex-col items-center justify-center py-24 rounded-2xl border border-dashed border-slate-700 bg-slate-800/30">
            <div class="p-4 rounded-2xl bg-slate-700/50 mb-4">
                <svg class="h-10 w-10 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-white mb-1">Belum ada kategori</h3>
            <p class="text-sm text-slate-400 mb-6">Tambahkan kategori untuk mengorganisir barang Anda</p>
            <Link href="/kategori/create" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold transition-all">
                + Tambah Kategori Pertama
            </Link>
        </div>

        <!-- Table -->
        <div v-else class="rounded-2xl border border-slate-700/60 bg-slate-800/50 backdrop-blur-sm overflow-hidden">
            <div class="h-0.5 w-full bg-gradient-to-r from-emerald-500 to-teal-500"></div>

            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-slate-700/60">
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-slate-500 w-16">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-widest text-slate-500">Nama Kategori</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-widest text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/40">
                    <tr
                        v-for="(kategori, index) in kategoris"
                        :key="kategori.id"
                        class="group hover:bg-slate-700/30 transition-colors duration-150"
                    >
                        <td class="px-6 py-4 text-sm text-slate-600 font-mono">
                            {{ String(index + 1).padStart(2, '0') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-white">{{ kategori.nama_kategori }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <Link
                                    :href="`/kategori/${kategori.id}/edit`"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-400 hover:text-amber-300 hover:bg-amber-500/10 border border-transparent hover:border-amber-500/20 transition-all duration-200"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </Link>
                                <button
                                    @click="hapusKategori(kategori.id)"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 border border-transparent hover:border-rose-500/20 transition-all duration-200"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
