<script setup>
import {computed, onBeforeMount, onBeforeUpdate, onMounted, onUpdated, ref, watch} from 'vue';

const props = defineProps({
  country: Number,
  state: Number,
  timeout: Number,
});

const emit = defineEmits({
  'update:city': Object,
});

const listLoaded = ref(false);
const lastCountry = ref(0);
const lastCall = ref(0);
const searching = ref(false);
const tmr = ref(null);
const city = ref('');
let loader;

onMounted(() => {
  lastCall.value = Date.now();
});

const cityList = ref([])
const lockCity = ref(false);
const selectedCountry = ref(null)
const selectedState = ref(null)
const selectedCity = ref(null)
const citySelected = ref(false)
const searchTerm = ref('')

const selectCity = (value) => {
  listLoaded.value = false;
  selectedCountry.value = value.country.name;
  selectedState.value = states(value.states);
  selectedCity.value = value.name
  citySelected.value = true;
  emit("update:city", value);
}

async function loadList() {
  if (!searchTerm.value) {
    listLoaded.value = true;
    searching.value = false;
    return [];
  }
  listLoaded.value = false;
  if (loader) {
    loader.abort();
  }
  searching.value = true;
  console.log("city = ", searchTerm.value)
  lastCall.value = Date.now();
  console.log("searching for " + searchTerm.value);
  let url = '/api/cities?type=simple&like=' + encodeURIComponent(searchTerm.value);
  if (props.country) {
    url += '&country=' + props.country;
  }
  // if (state.value) {
  //   url += '&state=' + state.value;
  // }
  // loader = new AbortController();
  const list = await axios.get(url, {
    // signal: loader.signal
  })
      .then(
          (data) => {
            const res = data.data;
            // lastCountry.value = props.country;
            listLoaded.value = true;
            searching.value = false;
            return res;
            // emit('update:city', selectedCity);
          }
      ).catch(
          (err) => {
            console.error(err);
            listLoaded.value = true;
            return [];
          }
      );
  cityList.value = list;
}

watch(city, (value, oldValue) => {
  if (searchTerm.value.toLowerCase() !== value.toLowerCase()) {
    searchTerm.value = value.toLowerCase()
  }
  // if (lockCity.value) {
  //   return;
  // }
  if (tmr.value) {
    clearTimeout(tmr.value)
  }
  if (!value) {
    cityList.value = [];
    listLoaded.value = false;
    return;
  }
  tmr.value = setTimeout(loadList, 300)
  searching.value = true
})

watch(cityList, (value) => {
  if (value.length) {
    listLoaded.value = true;
  }
})

const states = (states) => {
  return states.reduce((carry, v) => {
    return carry ? carry + ', ' + v.name : v.name;
  }, '')
}
</script>

<template>
  <div class="m-2 border border-gray-300 rounded-t-md shadow-sm flex flex-col p-2">
    <input class="border border-transparent rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
           :class="{'spinner': searching, 'no-spinner': !searching}"
           v-model="city"
    />
    <div v-if="listLoaded" class="flex flex-col border border-gray-200 rounded-b-md">
      <div
          class="hover:bg-gray-100 hover:text-black hover:border hover:border-gray-500 text-gray-400 border-b border-b-black border-dotted last-of-type:border-b-transparent py-2 px-4"
          v-for="c in cityList"
          @click="selectCity(c)">{{ c.name }} ({{ states(c.states) }})
      </div>
    </div>
    <div v-if="citySelected">
      <div>City: {{ selectedCity }}</div>
      <div>Country: {{ selectedCountry }}</div>
      <div>State: {{ selectedState }}</div>
    </div>
  </div>
</template>

<style scoped>
.spinner {
  background-image: url('/vendor/geodb/Pulse-1s-68px.gif');
  background-position: center right;
  background-size: 48px 48px;
  background-repeat: no-repeat;
}

.no-spinner {
  background: none;
}
</style>
