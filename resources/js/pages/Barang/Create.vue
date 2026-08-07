<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    kategoris: Array,
});

const form = useForm({
    nama_barang: '',
    kategori_id: '',
    stok: '',
});

const simpanData = () => {
    form.post('/barang', { preserveScroll: true });
};
</script>

<template>
    <Head title="Tambah Barang" />

    <div class="min-h-screen p-6 lg:p-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-8">
            <Link href="/barang" class="hover:text-violet-400 transition-colors">Barang</Link>
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            <span class="text-slate-300">Tambah Baru</span>
        </div>

        <div class="max-w-2xl">
            <!-- Page Header -->
            <div class="mb-8">
                <p class="text-xs font-semibold uppercase tracking-widest text-violet-400 mb-1">Form Input</p>
                <h1 class="text-3xl font-bold text-white">Tambah Barang</h1>
                <p class="text-sm text-slate-400 mt-1">Isi detail barang yang ingin ditambahkan ke inventaris.</p>
            </div>

            <!-- Form Card -->
            <div class="rounded-2xl border border-slate-700/60 bg-slate-800/50 backdrop-blur-sm overflow-hidden">
                <div class="h-0.5 w-full bg-gradient-to-r from-violet-500 to-fuchsia-500"></div>

                <form @submit.prevent="simpanData" class="p-6 lg:p-8 space-y-6">
                    <!-- Nama Barang -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-300">Nama Barang <span class="text-rose-400">*</span></label>
                        <input
                            v-model="form.nama_barang"
                            type="text"
                            placeholder="Contoh: Laptop Asus VivoBook"
                            class="w-full px-4 py-3 rounded-xl bg-slate-900/80 border text-white placeholder-slate-500 text-sm transition-all duration-200 outline-none focus:ring-2"
                            :class="form.errors.nama_barang
                                ? 'border-rose-500/50 focus:ring-rose-500/30'
                                : 'border-slate-700 focus:border-violet-500/50 focus:ring-violet-500/20'"
                        />
                        <p v-if="form.errors.nama_barang" class="text-xs text-rose-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ form.errors.nama_barang }}
                        </p>
                    </div>

                    <!-- Kategori -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-300">Kategori <span class="text-rose-400">*</span></label>
                        <select
                            v-model="form.kategori_id"
                            class="w-full px-4 py-3 rounded-xl bg-slate-900/80 border text-sm transition-all duration-200 outline-none focus:ring-2 appearance-none"
                            :class="[
                                form.errors.kategori_id ? 'border-rose-500/50 focus:ring-rose-500/30' : 'border-slate-700 focus:border-violet-500/50 focus:ring-violet-500/20',
                                form.kategori_id ? 'text-white' : 'text-slate-500'
                            ]"
                        >
                            <option value="" disabled class="text-slate-500 bg-slate-900">Pilih Kategori</option>
                            <option v-for="kategori in kategoris" :key="kategori.id" :value="kategori.id" class="text-white bg-slate-900">
                                {{ kategori.nama_kategori }}
                            </option>
                        </select>
                        <p v-if="form.errors.kategori_id" class="text-xs text-rose-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ form.errors.kategori_id }}
                        </p>
                    </div>

                    <!-- Stok -->
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-300">Jumlah Stok <span class="text-rose-400">*</span></label>
                        <input
                            v-model="form.stok"
                            type="number"
                            min="0"
                            placeholder="Contoh: 50"
                            class="w-full px-4 py-3 rounded-xl bg-slate-900/80 border text-white placeholder-slate-500 text-sm transition-all duration-200 outline-none focus:ring-2"
                            :class="form.errors.stok
                                ? 'border-rose-500/50 focus:ring-rose-500/30'
                                : 'border-slate-700 focus:border-violet-500/50 focus:ring-violet-500/20'"
                        />
                        <p v-if="form.errors.stok" class="text-xs text-rose-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ form.errors.stok }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-700/60">
                        <Link href="/barang" class="px-5 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-700/60 transition-all duration-200">
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold shadow-lg shadow-violet-500/20 transition-all duration-200 hover:-translate-y-0.5 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                        >
                            <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Barang' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>