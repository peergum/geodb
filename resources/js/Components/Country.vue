<script setup>
import {onBeforeMount, onBeforeUpdate, onMounted, onUpdated, ref} from 'vue';

const props = defineProps({
});

const emit = defineEmits({'update:country': Object});

const countryList = ref(null)
const listLoaded = ref(false);

const selectValue = (event) => {
    if (listLoaded.value) {
        const selectedCountry = countryList.value.find((v) => v.id == event.target.value);
        emit('update:country', selectedCountry);
    }
}
onMounted(() => {
    // if (select.value.hasAttribute('autofocus')) {
    //     select.value.focus();
    // }

    if (!listLoaded.value) {
        console.log("OK")
        axios.get('/api/countries?type=simple')
            .then((data) => {
                countryList.value = data.data;
                console.log(countryList.value);
            }).catch((err) => {
            console.error(err);
        });
        listLoaded.value = true;
    }
});

// defineExpose({focus: () => select.value.focus()});
</script>

<template>
    <select
        class="block m-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="country"
        @change="selectValue"
    >
        <option value="0">---</option>
        <option v-for="c in countryList" :value="c.id" :selected="country==c.id">{{ c.name }}</option>
    </select>
</template>

<style scoped>

</style>
