<script setup>
import {onBeforeMount, onBeforeUpdate, onMounted, onUpdated, ref, watch, watchEffect} from 'vue';

const props = defineProps({
    country: Number,
});

const emit = defineEmits({'update:state': Object});

const stateList = ref(null)
const listLoaded = ref(false);
const lastCountry = ref(0);

const selectValue = (event) => {
    if (listLoaded.value) {
        const selectedState = stateList.value.find((v) => v.id == event.target.value);
        emit('update:state', selectedState);
    }
}

onMounted(() => {
    if (!listLoaded.value && props.country) {
        updateList();
    }
});

onUpdated(() =>{
    if (props.country != lastCountry.value) {
        updateList();
    }
})

const updateList = () => {
    console.log("updating state list");
    axios.get('/api/countries/' + props.country + '/states?type=simple')
        .then((data) => {
            stateList.value = data.data;
            console.log(stateList.value);
            listLoaded.value = true;
            lastCountry.value = props.country;
        }).catch((err) => {
        console.error(err);
    });
}

// defineExpose({focus: () => select.value.focus()});
</script>

<template>
    <select
        class="block m-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        v-model="state"
        @change="selectValue"
    >
        <option value="0" :selected="state==0">---</option>
        <option v-for="c in stateList" :value="c.id" :selected="state==c.id">{{ c.name }}</option>
    </select>
</template>

<style scoped>

</style>
