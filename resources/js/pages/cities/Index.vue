<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Pagination from '@/components/Pagination.vue';

defineProps({
    cities: Object,
});

</script>

<template>

    <Head title="Cidades" />
    <AppLayout>
        <div class="flex justify-center p-6">
            <section
                class="bg-gray-500 h-[42rem] w-[64rem] rounded-md flex flex-col items-center px-4 py-4 overflow-hidden">

                <div class="flex justify-center h-[6rem] items-center">
                    <h1 class="text-2xl font-bold">Cidades do Brasil</h1>
                </div>

                <hr class="my-4 border-gray-300 w-full">

                <div v-if="cities" class="flex flex-col items-center">
                    <table class="table-auto">
                        <thead>
                            <tr>
                                <th class="text-xl px-6 py-4 text-center">Nome</th>
                                <th class="text-xl px-6 py-4 text-center">Estado</th>
                                <th class="text-xl px-6 py-4 text-center">N° IBGE</th>
                                <th class="text-xl px-6 py-4 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="city in cities.data" :key="city.id ?? city.ibge">
                                <td class="text-xl px-6 py-4 text-center border border-gray-300 font-semibold">
                                    {{ city.name }}</td>
                                <td class="text-md px-6 py-4 text-center border border-gray-300">{{ city.state.name }}</td>
                                <td class="text-md px-6 py-4 text-center border border-gray-300">{{ city.ibge }}</td>
                                <td class="text-md px-6 py-4 text-center border border-gray-300">
                                    <Link :href="route('cities.show', city.id)" class="bg-blue-600 px-4 py-2 rounded-full font-semibold
                                    hover:cursor-pointer
                                    hover:bg-blue-700">
                                    Detalhes
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-6 flex justify-center">
                        <Pagination :links="cities.links" wrapper-class="inline-flex"
                            active-class="bg-emerald-600 text-white border-emerald-600"
                            inactive-class="bg-white text-gray-700 border-gray-300 hover:bg-emerald-50"
                            disabled-class="text-gray-400 bg-gray-100 border-gray-100"
                            :labels="{ previous: 'Anterior', next: 'Próximo', dots: '...' }" />
                    </div>
                </div>
                <div v-else class="flex items-center h-[16rem]">
                    <h1 class="text-2xl bg-red-600 px-3 py-4 rounded-2xl font-semibold">Nenhuma cidade encontrada!</h1>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
