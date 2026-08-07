<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    barang: Object,
    kategoris: Array,
});

const form = useForm({
    nama_barang: props.barang.nama_barang,
    kategori_id: props.barang.kategori_id,
    stok: props.barang.stok,
});

const updateData = () => {
    form.put(`/barang/${props.barang.id}`, { preserveScroll: true });
};
</script>

<template>
    <Head title="Edit Barang" />

    <div class="min-h-screen p-6 lg:p-8">
        <div class="flex items-center gap-2 text-sm text-slate-500 mb-8">
            <Link href="/barang" class="hover:text-violet-400 transition-colors">Barang</Link>
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            <span class="text-slate-300">Edit</span>
        </div>

        <div class="max-w-2xl">
            <div class="mb-8">
                <p class="text-xs font-semibold uppercase tracking-widest text-amber-400 mb-1">Perbarui Data</p>
                <h1 class="text-3xl font-bold text-white">Edit Barang</h1>
                <p class="text-sm text-slate-400 mt-1 capitalize">Mengubah: <span class="text-slate-200 font-medium">{{ barang.nama_barang }}</span></p>
            </div>

            <div class="rounded-2xl border border-slate-700/60 bg-slate-800/50 backdrop-blur-sm overflow-hidden">
                <div class="h-0.5 w-full bg-gradient-to-r from-amber-500 to-orange-500"></div>

                <form @submit.prevent="updateData" class="p-6 lg:p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-300">Nama Barang <span class="text-rose-400">*</span></label>
                        <input
                            v-model="form.nama_barang"
                            type="text"
                            class="w-full px-4 py-3 rounded-xl bg-slate-900/80 border text-white text-sm outline-none focus:ring-2 transition-all"
                            :class="form.errors.nama_barang ? 'border-rose-500/50 focus:ring-rose-500/30' : 'border-slate-700 focus:border-amber-500/50 focus:ring-amber-500/20'"
                        />
                        <p v-if="form.errors.nama_barang" class="text-xs text-rose-400">{{ form.errors.nama_barang }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-300">Kategori <span class="text-rose-400">*</span></label>
                        <select
                            v-model="form.kategori_id"
                            class="w-full px-4 py-3 rounded-xl bg-slate-900/80 border text-white text-sm outline-none focus:ring-2 transition-all appearance-none"
                            :class="form.errors.kategori_id ? 'border-rose-500/50 focus:ring-rose-500/30' : 'border-slate-700 focus:border-amber-500/50 focus:ring-amber-500/20'"
                        >
                            <option value="" disabled class="bg-slate-900">Pilih Kategori</option>
                            <option v-for="kategori in kategoris" :key="kategori.id" :value="kategori.id" class="bg-slate-900">
                                {{ kategori.nama_kategori }}
                            </option>
                        </select>
                        <p v-if="form.errors.kategori_id" class="text-xs text-rose-400">{{ form.errors.kategori_id }}</p>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-slate-300">Jumlah Stok <span class="text-rose-400">*</span></label>
                        <input
                            v-model="form.stok"
                            type="number"
                            min="0"
                            class="w-full px-4 py-3 rounded-xl bg-slate-900/80 border text-white text-sm outline-none focus:ring-2 transition-all"
                            :class="form.errors.stok ? 'border-rose-500/50 focus:ring-rose-500/30' : 'border-slate-700 focus:border-amber-500/50 focus:ring-amber-500/20'"
                        />
                        <p v-if="form.errors.stok" class="text-xs text-rose-400">{{ form.errors.stok }}</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-700/60">
                        <Link href="/barang" class="px-5 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-700/60 transition-all">
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-900 text-sm font-semibold shadow-lg shadow-amber-500/20 transition-all hover:-translate-y-0.5 disabled:opacity-60 disabled:cursor-not-allowed"
                        >
                            <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
