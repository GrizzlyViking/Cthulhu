<script setup>
import Weapons from '@/Pages/Components/Character/Weapons.vue';
import EquipmentList from '@/Pages/Components/Character/EquipmentList.vue';
import Wealth from '@/Pages/Components/Character/Wealth.vue';

/*
 * The Equipment tab. Money leads, because this is the tab things are bought on
 * and a player about to spend wants to see what they have. Weapons come next,
 * with their statistics and ammunition, because that is what gets reached for
 * mid-scene. Everything the investigator owns — the weapons among it — is
 * listed below, sorted by where it is kept.
 */
defineProps({
    character: Object,
    editable: Boolean,
    canEdit: Boolean,
    storageLocations: { type: Array, default: () => [] },
    /** The group's era, and every era the server knows about. */
    era: { type: String, default: null },
    eras: { type: Array, default: () => [] },
});
</script>

<template>
    <div class="flex flex-col gap-5">
        <Wealth :character="character" :can-edit="canEdit" />
        <Weapons :character="character" :editable="editable" :can-edit="canEdit" :era="era" :eras="eras" />
        <EquipmentList
            :character="character"
            :can-edit="canEdit"
            :storage-locations="storageLocations"
            :era="era"
            :eras="eras"
        />
    </div>
</template>
