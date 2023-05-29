<template>
    <div class="perspektive-disclosure-container" v-if="pConfig.perspectives && Object.keys(pConfig.perspectives).length">
        <div v-if="item.label" class="perspektive-select-label mb-1">{{ item.label }}</div>
        <Disclosure v-slot="{ open }" as="div" class="form-input-bordered menu-disclosure">
            <DisclosureButton
                class="flex flex-wrap justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-800 block w-full text-left cursor-pointer py-2 px-3 focus:outline-none focus:ring rounded truncate whitespace-nowrap text-gray-500 active:text-gray-600 dark:text-gray-500 dark:hover:text-gray-400 dark:active:text-gray-600" v-on:click.stop
            >
                <div class="max-w-[9rem] flex gap-1">
                    <NHMenuIcon :icons="pConfig.current.icons" />
                    <div class="truncate">{{ pConfig.current.label }}</div>
                </div>
                <CollapseButton
                    class=""
                    :collapsed="!open"
                />
            </DisclosureButton>
            <DisclosurePanel class="text-gray-500" v-slot="{ close }">
                <button
                    type="button"
                    class="hover:bg-gray-50 dark:hover:bg-gray-800 block w-full text-left cursor-pointer py-2 px-3 focus:outline-none focus:ring rounded truncate whitespace-nowrap text-gray-500 active:text-gray-600 dark:text-gray-500 dark:hover:text-gray-400 dark:active:text-gray-600" v-for="perspective in pConfig.perspectives"
                    @click="switchPerspective(perspective.slug)"
                >
                    <span class="flex gap-2">
                        <NHMenuIcon :icons="perspective.icons" />
                        <span class="truncate">{{ perspective.label }}</span>
                    </span>
                </button>
            </DisclosurePanel>
        </Disclosure>
    </div>
</template>

<script>
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
} from '@headlessui/vue'
import {Inertia} from "@inertiajs/inertia";

export default {
    name: "PerspectiveDisclosure",
    components: {
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
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
            console.log('switchPerspective')
            Nova.request().post('/nova-vendor/nova-perspectives/switch-perspective', {
                perspective: perspective,
            }).then(response => {
                close()
                Inertia.reload()
            })
        },
    },
}
</script>
