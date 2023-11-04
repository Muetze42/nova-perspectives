<template>
    <div class="perspektive-select-container" v-if="pConfig.perspectives && Object.keys(pConfig.perspectives).length">
        <div v-if="item.label" class="perspektive-select-label mb-1">{{ item.label }}</div>
        <Dropdown class="perspektive-switch rounded w-full-child">
            <slot name="trigger">
                <button class="w-full form-control form-input form-input-bordered inline-flex items-center cursor-pointe justify-between px-2 rounded">
                    <span class="flex gap-2 max-w-[11rem]">
                        <NHMenuIcon :icons="pConfig.current.icons" />
                        <span class="truncate">{{ pConfig.current.label }}</span>
                    </span>
                </button>
            </slot>
            <template #menu>
                <DropdownMenu class="px-1 w-full" width="auto">
                    <DropdownMenuItem as="button" v-for="perspective in pConfig.perspectives" @click="switchPerspective(perspective.slug)">
                        <div class="flex gap-2">
                            <NHMenuIcon :icons="perspective.icons" />
                            <div class="truncate">{{ perspective.label }}</div>
                        </div>
                    </DropdownMenuItem>
                </DropdownMenu>
            </template>
        </Dropdown>
    </div>
</template>

<script>
import NHMenuIcon from '@norman-huth/helpers-collection-js/components/nova/Icon.vue'
import { Inertia } from '@inertiajs/inertia'

export default {
    name: "PerspektiveSelect",
    components: {
        NHMenuIcon,
    },
    props: [
        'item',
    ],
    data() {
        return {
            pConfig: Nova.config('perspectives'),
        }
    },
    methods: {
        switchPerspective(perspective) {
            Nova.request().post('/nova-vendor/nova-perspectives/switch-perspective', {
                perspective: perspective,
            }).then(response => {
                Inertia.reload()
            })
        },
    },
}
</script>
