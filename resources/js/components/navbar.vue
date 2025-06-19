<template>
    <div class="row">
            <!-- upload area -->
            <div class="col-12 py-3 px-5">
                <div class="card">
                    <div class="card-body text-center">
                        <button class="btn btn-outline-primary" @click="uploadFile" v-if="route.name === 'home' ">
                            Upload file Here
                        </button>
                        <button class="m-2 btn btn-outline-primary" @click="goToList('/list')" v-if="route.name!=='list'">
                            List View 
                        </button>
                        <button   class="m-2 btn btn-outline-primary" @click="goToList('/')" v-if="route.name !== 'home' ">
                            Home 
                        </button>
                        <input ref="fileInput" type="file" accept=".xml" class="hidden" @change="handleFileUpload">
                    </div>
                </div>
            </div>
        </div>
</template>

<script setup>
import { ref,onMounted } from "vue";
import { xml2json } from 'xml-js'
import { useRoute,useRouter } from "vue-router";


const router = useRouter()
const route = useRoute()



  const fileInput = ref(null)
  const data = ref(null)


  const uploadFile = ()=>{
        fileInput.value?.click();
  }

  const handleFileUpload  = (event) =>{
    const file = event.target.files[0];

    if(!file)
    {
      return
    }
    
    let isValid = checkForValidExtension(file.name);

    if(isValid)
    {
        const reader = new FileReader();
        reader.onload = () => {            
            const rawXml = reader.result;
            data.value = jsonConverter(rawXml);
            emits('xmlData',data.value)
        }
        reader.readAsText(file);              
    }

  } 

  const checkForValidExtension = (fileName) =>{
      
      const isValid = /\.xml$/i.test(fileName);
      return isValid;
  }

  const jsonConverter = (string) => 
  {
    const json = JSON.parse(xml2json(string, { compact: true, spaces: 2 }))
    return json
  }

  const goToList = (location) =>{
    router.push(location)
  }
  

  const emits = defineEmits(["xmlData"]);

</script>

<style scoped>

</style>