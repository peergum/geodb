<script setup>

import {ref, watch} from "vue";
import Country from "@/Components/GeoDB/Country.vue";
import State from "@/Components/GeoDB/State.vue";
import City from "@/Components/GeoDB/City.vue";

const props = defineProps({
    countries: Number,
    states: Number,
    cities: Number,
    version: String,
    phpVersion: String,
})

const selectedCountry = ref(null)
const selectedState = ref(null)
const selectedCity = ref(null)
const stateCountry = ref(0);

const selectCountry = (value) => {
    console.log("country:", value)
    selectedCountry.value = value;
    stateCountry.value = value.id;
}

const selectState = (value) => {
    console.log("state:", value)
    selectedState.value = value;
}

const selectCity = (value) => {
    console.log("city:", value)
    selectedCity.value = value;
}

const country = ref(0);
const state = ref(0);
const city = ref(0);

</script>

<template>
<!--    <GuestLayout>-->
        <div class="h-screen w-screen align-middle justify-center">
            <div class="geodb">
                <img src="/vendor/geodb/laravel-geodb.png" alt="geodb logo"/>
                <div class="items">
                    <div>
                        Countries: {{ countries }}
                    </div>
                    <div>
                        States: {{ states }}
                    </div>
                    <div>
                        Cities: {{ cities }}
                    </div>
                    <div>
                        <Country class="block" :country="country" @update:country="selectCountry"/>
<!--                    <div v-if="selectedCountry">-->
                        <State :state="state" :country="stateCountry" @update:state="selectState"/>
                        <City :city="city" :country="stateCountry" @update:city="selectCity"/>
                    </div>
<!--                    </div>-->

                </div>
                <footer>
                    <div class="sponsor">
                        <a href="https://github.com/sponsors/peergum"
                           class="">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="black" viewBox="0 0 24 24" stroke-width="1.5"
                                 class="heart">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                            </svg>
                            Sponsor
                        </a>
                    </div>
                    <div class="package">
                        GeoDB for Laravel {{ version }} (PHP v{{ phpVersion }})
                    </div>
                </footer>
            </div>
        </div>
<!--    </GuestLayout>-->
</template>

<style scoped>
.center {
    width: 100vw;
    height: 100vh;
    margin: 0 auto;
    font-family: Figtree sans-serif;
    background-color: #f7f7f7;
}

.geodb {
    position: relative;
    padding: 10px;
    top: 30%;
    border: #000 solid 1px;
    box-shadow: 0 0 10px 5px #c0c0c0;
    display: flex;
    flex-direction: column;
    margin: 0 auto;
    width: fit-content;
    background-color: white;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
}

.geodb .items {
    margin: 20px 0;
    justify-content: center;
}

.geodb img {
    width: 120px;
    height: 120px;
    margin-bottom: 10px;
    border-bottom: gray 1px solid;
}

.heart {
    max-width: 20px;
    vertical-align: center;
}

footer {
    width: 100%;
    margin-top: 20px;
    border-top: gray solid 1px;
    padding: 10px 0 0 0;
    vertical-align: center;
    display: flex;
    flex-direction: row;
}

.sponsor {
    vertical-align: center;
    display: inline-flex;
    flex-direction: row;
    flex-basis: auto;
    width: 20%;
}

.sponsor > * {
    display: inline-flex;
    flex-direction: row;
}

svg {
    width: 20px;
}

.package {
    display: flex;
    width: 80%;
    align-items: center;
    place-content: end;
}
</style>
