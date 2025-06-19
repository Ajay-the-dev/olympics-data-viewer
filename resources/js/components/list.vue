<template>
    <Navbar/>
    <div>

        <div class=" py-3 px-5 container-fluid">
            <div class="row bg-body-secondary row">
                <div class="col-6 p-4">
                    <label for="country" class="fst-italic p-2">Country</label>
                    <select id="country" class="form-control" v-model="selectedCountry" :disabled="countries.length == 0">
                        <option value="">All</option>
                        <option v-for="country in countries" :value="country">{{ country }}</option>
                    </select>
                </div>
                <div class="col-6  p-4">
                    <label for="player" class="fst-italic p-2">Player</label>
                    <select id="player" class="form-control" v-model="selectedPlayer" >
                        <option value="">All</option>
                        <option v-for="player in players" :value="player.playerId">{{ player.playerName }}</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3" >
                <div class="col-12">
                    <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-end">
                        <!-- :class="page.label ? 'page-item disabled': 'page-item'" -->
                        <li  :class=" page.url == null ? 'page-item disabled' : 'page-item'" v-for="page,i in pagination.links">
                            <a :class="page.active ? 'page-link active' : 'page-link'" href="#" @click="getAllPlayers(page.url)">{{ htmlEntityDecode(page.label) }}</a>
                        </li>
                    </ul>
                    </nav>
                </div>
                <div class="col-6 col-md-4 p-3"  v-for="player in allPlayers">
                    <div class="card">
                        <div class="card-body">
                            {{ player.playerName }}
                            <p class="m-0">Age : <span class="text-muted"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script setup>
import { ref,onMounted,watch } from "vue"
import Navbar from "./navbar.vue"
import axios from 'axios'

import Swal from 'sweetalert2'


const countries = ref([])
const selectedCountry = ref("")
const players = ref([])
const allPlayers = ref({})
const selectedPlayer = ref("")
const pagination = ref({})



onMounted(() => {
    getAllPlayers()
    getAllTeams()
    
})

const showToast = (msg,icon,time = 2000) => {
  Swal.fire({
    toast: true,
    position: 'top-end',
    icon: icon,
    title: msg,
    showConfirmButton: false,
    timer: time,
    timerProgressBar: true,
  })
}

const htmlEntityDecode =(str) =>{
  const textarea = document.createElement('textarea');
  textarea.innerHTML = str;
  return textarea.value;
}

const getAllTeams = async() => {
    const response = await axios.get('/api/allteams').then((response)=>{
        countries.value = response.data.data
        if(countries.value.length == 0)
        {
            showToast('No countries found', 'error')
        }
    }).catch((error)=>{
                showToast('Something went wrong while fetching countries', 'error')
    })
}

const getPlayersByCountry = async(country)=>{
    const response = await axios.get('/api/getPlayers?country='+country).then((response)=>{
        
        // console.log(JSON.parse(response.data.data));
        


        players.value = JSON.parse(response.data.data)
        if(players.value.length == 0)
        {
            showToast('No players found', 'error')
        }
    }).catch((error)=>{
        showToast('Something went wrong while fetching players', 'error')
    })
}

const getAllPlayers = async(url = '/api/getPlayers?page=1')=>{
    const response = await axios.get(url).then((response)=>{
        const data = response.data.data
        pagination.value = JSON.parse(response.data.data)
        delete pagination.value.data
        console.log(pagination.value);        
        allPlayers.value = JSON.parse(data).data
        if(allPlayers.value.length == 0)
        {
            showToast('No players found', 'error')
        }
        
      
        // allPlayers.value = JSON.parse(response.data.data)
    }).catch((error)=>{
        showToast('Something went wrong while fetching players', 'error')
    })
}




watch(selectedCountry, (newCountry) => {
  console.log('New country:', newCountry);
  getPlayersByCountry(newCountry)
});

</script>

<style  scoped>

</style>