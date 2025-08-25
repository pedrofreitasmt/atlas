<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

type LinkItem = {
    url: string | null
    label: string
    active: boolean
}

const props = defineProps<{
    links: LinkItem[]
    preserveScroll?: boolean
    preserveState?: boolean
    only?: string[]
    wrapperClass?: string

    // customização de estilos
    baseClass?: string
    activeClass?: string
    inactiveClass?: string
    disabledClass?: string

    // customização de rótulos (nomes)
    labels?: {
        previous?: string
        next?: string
        dots?: string
    }
}>()

const preserveScroll = props.preserveScroll ?? true
const preserveState = props.preserveState ?? true

const baseClass = props.baseClass ?? 'px-3 py-2 border rounded text-sm'
const activeClass = props.activeClass ?? 'bg-blue-600 text-white border-blue-600'
const inactiveClass = props.inactiveClass ?? 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
const disabledClass = props.disabledClass ?? 'text-gray-500 cursor-not-allowed bg-gray-200 border-gray-200 select-none'

const getLabel = (raw: string) => {
    if (/(«|&laquo;|prev)/i.test(raw)) return props.labels?.previous ?? raw
    if (/(»|&raquo;|next)/i.test(raw)) return props.labels?.next ?? raw
    if (/(…|\.{3}|&hellip;)/i.test(raw)) return props.labels?.dots ?? raw
    return raw
}
</script>

<template>
    <nav :class="wrapperClass" aria-label="Paginação">
        <ul class="inline-flex items-center gap-1">
            <li v-for="(link, idx) in links" :key="idx">
                <span
                    v-if="!link.url"
                    :class="[baseClass, disabledClass]"
                    v-html="getLabel(link.label)"
                />

                <Link
                    v-else
                    :href="link.url"
                    :preserve-scroll="preserveScroll"
                    :preserve-state="preserveState"
                    :only="only"
                    :class="[baseClass, link.active ? activeClass : inactiveClass]"
                    v-html="getLabel(link.label)"
                />
            </li>
        </ul>
    </nav>
</template>
