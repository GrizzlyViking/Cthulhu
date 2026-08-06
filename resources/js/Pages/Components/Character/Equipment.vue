<script setup>
import Weapons from '@/Pages/Components/Character/Weapons.vue';
import EquipmentList from '@/Pages/Components/Character/EquipmentList.vue';

/*
 * The Equipment tab. Weapons keep the top of it, with their statistics and
 * ammunition, because that is what gets reached for mid-scene. Everything the
 * investigator owns — the weapons among it — is listed below, sorted by where
 * it is kept.
 */
defineProps({
    character: Object,
    editable: Boolean,
    canEdit: Boolean,
    storageLocations: { type: Array, default: () => [] },
});
</script>

<template>
    <div class="flex flex-col gap-5">
        <Weapons :character="character" :editable="editable" :can-edit="canEdit" />
        <EquipmentList :character="character" :can-edit="canEdit" :storage-locations="storageLocations" />
    </div>
</template>
